<?php

namespace App\Helpers;

use App\Models\Remote;
use Illuminate\Support\Facades\Storage;

class ScrapeHelper
{
    protected $cookie_id;

    /**
     * create a new instance.
     */
    public function __construct($cookie_id = 'cli')
    {
        // store the cookie id
        $this->cookie_id = $cookie_id;
    }

    /**
     * Check if we are already logged in
     */
    public function isLoggedIn(): bool
    {
        // get spamcontrol entry page
        $homepage = config('app.tfn_base_url');
        $page = $this->GetPage($homepage);
        if (strpos($page, 'You must log in using')) {
            return false;
        }
        return true;
    }

    public function Login($user, $password)
    {
        $url = config('app.tfn_base_url');
        $var = [];
        $vars = [];
        $vars['user'] = trim($user);
        $vars['password'] = trim($password);
        $var['Origin'] = $url;
        $data = $this->httpPost($url . '/login', $vars);

        $this->SaveSession($data);
        $body = $data['body'];
        if (strpos($body, 'Invalid username/email or password.') === false) {
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

    public function SaveSession($data)
    {
        if ($data['cookie'] === null) {
            $cookie = [];
        } else {
            $cookie = $data['cookie'];
        }
        $payload = json_encode($cookie->toArray());

        Remote::updateOrCreate(['name' => $this->cookie_id], ['payload' => $payload]);
        //Storage::put('tfn_session', $payload);
        return true;
    }

    public function GetSession()
    {
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $session = Remote::firstOrCreate(['name' => $this->cookie_id]);
        if ($session) {
            $cookies = json_decode($session->payload, 1);
            if (is_array($cookies)) {
                foreach ($cookies as $cookie) {
                    $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie($cookie));
                }
                return $jar;
            }
            return $jar;
        }
        return $jar;

        /**
         * if (Storage::exists('tfn_session')) {
         * $cookies = json_decode(Storage::get('tfn_session'), 1);
         * $jar = new \GuzzleHttp\Cookie\CookieJar();
         * foreach ($cookies as $cookie) {
         * $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie($cookie));
         * }
         * return $jar;
         * } else {
         * return [];
         * }
         */

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
