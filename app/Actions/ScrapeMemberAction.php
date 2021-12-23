<?php

namespace App\Actions;

use App\Models\Member;
use App\Models\Post;
use App\Models\Watchword;

class ScrapeMemberAction
{
    public function __invoke(): void
    {
        //
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

function GetPage($url, $redirect = 10)
{
    $loop = true;
    for ($i = 0; $loop == true; $i++) {
        $loop = false;
        $i++;
        $redirect--;
        $session = GetSession();
        $data = httpGet($url, $session);
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

function httpPost($url, $data)
{
    $domain = parse_url($url, PHP_URL_HOST);

    $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($data, $domain);
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

function member_id_exists($id)
{
    $count = Member::where('id', $id)->count();
    return $count ? true : false;
}

function create_member($data)
{
    $new_member = new Member();
    $new_member->id = $data['member_id'];
    $new_member->username = $data['user'];
    $new_member->email = $data['email'];
    $new_member->joined_recently = $data['joined_recently'];
    $new_member->created_at = $data['dated'];
    $new_member->updated_at = $data['dated'];
    $new_member->save();
}

function update_member($data)
{
    Member::where('id', $data['member_id'])->where('created_at', '>', $data['dated'])->update(['created_at' => $data['dated']]);
}
