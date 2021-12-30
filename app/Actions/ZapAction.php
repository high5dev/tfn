<?php

namespace App\Actions;

use App\Helpers\ScrapeHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ZapAction
{
    public function execute($id): void
    {
        $scrapeHelper = new ScrapeHelper();

        $pageUrl = config('app.tfn_base_url') . '/zap_member';
        $user = config('app.tfn_username');
        $password = config('app.tfn_password');

        // check if we're logged in
        if (!$scrapeHelper->isLoggedIn()) {
            // login
            $status = $scrapeHelper->Login($user, $password);
            if ($status !== true) {
                Log::debug('Scrape: error logging in');
                return;
            }
        }

        try {
            $page = $scrapeHelper->GetPage($pageUrl, ['user_id' => $id]);

            $DOM = new \DOMDocument('1.0', 'UTF-8');
            @$DOM->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

            $trNodes = $DOM->getElementsByTagName('tr');

            $idFound = false;
            $ipFound = false;
            $firstIP = '';

            foreach ($trNodes as $trNode) {
                $trContent = $trNode->textContent;

                $idContent = 'User ID: ' . $id;
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

        } catch (\Throwable $th) {
            Log::debug('ZapMember: Exception: ' . $th->getMessage());
        }

    }

}
