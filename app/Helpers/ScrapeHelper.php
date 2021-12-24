<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ScrapeHelper
{
    public function Login($user, $password)
    {
        $url = 'https://www.freecycle.org';
        $var = [];
        $vars = [];
        $vars['user'] = trim($user);
        $vars['password'] = trim($password);
        $var['Origin'] = $url;
        $data = $this->httpPost($url . '/login', $vars);

        $xdata = $this->SaveSession($data);
        $xpage = $data['body'];
        if (strpos($xpage, 'Invalid username/email or password.') === false) {
            return true;
        } else {
            return false;
        }
    }

    public function GetPage($url, $vars = 0, $redirect = 10)
    {
        $loop = true;
        for ($i = 0; $loop == true; $i++) {
            $loop = false;
            $i++;
            $redirect--;
            $session = $this->GetSession();
            $data = $vars ? $this->httpPost($url, $vars, $session) : $this->httpGet($url, $session);
            $page = $data['body'];
            $this->SaveSession($data);

            if ($data['status'] == '200' && $redirect) {
                $loop = true;
            }
        }
        return $page;
    }

    public function SaveSession($cook)
    {
        $sessionf = './tfn_session';
        if ($cook['cookie'] === null) {
            $cook1 = [];
        } else {
            $cook1 = $cook['cookie'];
        }

        Storage::put('tfn_session', json_encode($cook1->toArray()));
        //file_put_contents($sessionf, json_encode($cook1->toArray()));
        return true;
    }

    public function GetSession()
    {
        //$sessionf = './tfn_session';
        //if (file_exists($sessionf)) {
        if (Storage::exists('tfn_session')) {
            //$cookies = json_decode(file_get_contents($sessionf), 1);
            $cookies = json_decode(Storage::get('tfn_session'), 1);
            $jar = new \GuzzleHttp\Cookie\CookieJar();
            foreach ($cookies as $cookie) {
                $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie($cookie));
            }
            return $jar;
        } else {
            return [];
        }

    }

    public function httpPost($url, $data, $cookie = 0)
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

    public function httpGet($url, $cookie = 0)
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

}
