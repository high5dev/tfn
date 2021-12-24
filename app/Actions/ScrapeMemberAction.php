<?php

namespace App\Actions;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ScrapeMemberAction
{
    public function __invoke()
    {
        \Log::debug('ScrapeMember started');

        $pageUrl = 'https://spamcontrol.freecycle.org/view_member';
        $user = config('app.tfn_username');
        $password = config('app.tfn_password');

        $results = Member::where('updated_at', '>', Carbon::now()->subMonth())
            ->whereNull('firstip')
            ->pluck('id');

        if (!count($results)) {
            \Log::debug('No data to scrape');
            return;
        }

        $login = Login($user, $password);
        if ($login !== true) {
            \Log::debug('failed to log in');
            return;
        }

        foreach ($results as $member_id) {
            \Log::debug($member_id . ':::started');
            try {
                $page = GetPage($pageUrl, ['user_id' => $member_id]);

                $DOM = new \DOMDocument('1.0', 'UTF-8');
                @$DOM->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

                $trNodes = $DOM->getElementsByTagName('tr');

                $idFound = false;
                $ipFound = false;
                $firstIP = '';

                foreach ($trNodes as $trNode) {
                    $trContent = $trNode->textContent;

                    $idContent = 'User ID: ' . $member_id;
                    if (Str::contains($trContent, $idContent)) {
                        $idFound = true;
                    }

                    $ipContent = 'First IP address: ';
                    if (Str::contains($trContent, $ipContent)) {
                        $ipFound = true;
                        $ip = trim(str_replace($ipContent, "", $trContent));
                        $firstIP = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
                    }

                    if ($idFound && $ipFound && $firstIP != '') {
                        break;
                    }
                }

                if ($idFound && $ipFound && $firstIP != '') {
                    Member::where('id', $member_id)->update([
                        'firstip' => $firstIP,
                        'updated_at' => Carbon::now(),
                    ]);

                    \Log::debug($member_id . ' found ip');
                } else {
                    \Log::debug($member_id . ' not found ip');
                }
            } catch (\Throwable $th) {
                \Log::debug('scraping error. ' . $th->getMessage());
                continue;
            }
        }
    }

}

function Login($user, $password)
{
    $url = 'https://www.freecycle.org';
    $var = [];
    $vars = [];
    $vars['user'] = trim($user);
    $vars['password'] = trim($password);
    $var['Origin'] = $url;
    $data = httpPost($url . '/login', $vars);

    $xdata = SaveSession($data);
    $xpage = $data['body'];
    if (strpos($xpage, 'Invalid username/email or password.') === false) {
        return true;
    } else {
        return false;
    }
}

function GetPage($url, $vars = 0, $redirect = 10)
{
    $loop = true;
    for ($i = 0; $loop == true; $i++) {
        $loop = false;
        $i++;
        $redirect--;
        $session = GetSession();
        $data = $vars ? httpPost($url, $vars, $session) : httpGet($url, $session);
        $page = $data['body'];
        SaveSession($data);

        if ($data['status'] == '200' && $redirect) {
            $loop = true;
        }
    }
    return $page;
}

function SaveSession($cook)
{
    $sessionf = './session.json';
    if ($cook['cookie'] === null) {
        $cook1 = [];
    } else {
        $cook1 = $cook['cookie'];
    }

    file_put_contents($sessionf, json_encode($cook1->toArray()));
    return true;
}

function GetSession()
{
    $sessionf = './session.json';
    if (file_exists($sessionf)) {
        $cookies = json_decode(file_get_contents($sessionf), 1);
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        foreach ($cookies as $cookie) {
            $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie($cookie));
        }
        return $jar;
    } else {
        return [];
    }

}

function httpPost($url, $data, $cookie = 0)
{
    $cookieJar = $cookie;
    if (!$cookie) {
        $domain = parse_url($url, PHP_URL_HOST);
        $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($data, $domain);
    }

    $client = new \GuzzleHttp\Client([
        'base_uri' => $url,
        'cookies' => $cookieJar,
    ]);

    $headers = [];
    $headers['Content-Type'] = 'application/x-www-form-urlencoded;';

    $response = $client->request('POST', $url, ['form_params' => $data, 'headers' => $headers]);

    $result['body'] = ($response->getBody()->getContents());
    $result['cookie'] = $cookieJar;
    $result['header'] = $response->getHeaders();
    $result['status'] = $response->getStatusCode();

    return $result;
}

function httpGet($url, $cookie = 0)
{
    $domain = parse_url($url, PHP_URL_HOST);

    $client = new \GuzzleHttp\Client([
        'base_uri' => $url,
        'cookies' => $cookie ? $cookie : null,
    ]);

    $response = $client->request('GET', $url);

    $result['body'] = ($response->getBody()->getContents());
    $result['cookie'] = $cookie ? $cookie : null;
    $result['status'] = $response->getStatusCode();

    return $result;
}