<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetIPinfoAction
{
    /*
     * get info on an IP address
     */
    public function execute($ip): string
    {
        $url = config('app.ip_base_url') . config('app.ip_api_key') . '/' . $ip;
        $response = Http::get($url);
        return $response->json();
    }

    /**
     * retrieve the prefixes from the API
     */
    private function getPrefixes($range): array
    {
        // GuzzleHttp\Client
        $client = new Client();
        $url = $this->url . $range;

        // download the JSON
        $response = $client->request('GET', $url, [
            'verify' => false,
        ]);

        // get JSON into an array
        $responseBody = json_decode($response->getBody());

        if ($responseBody) {

            // check it's ok
            if ($responseBody->status == 'ok') {
                return $responseBody->data->related_prefixes;
            } else {
                Log::debug('Error getting BGP view: ' . print_r($responseBody, TRUE));
                return [];
            }

        } else {
            return [];
        }

    }
}

