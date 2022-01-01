<?php

namespace App\Actions;

use App\Helpers\ScrapeHelper;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ScrapeMemberAction
{
    public function __invoke(): void
    {
        Log::debug('ScrapeMember: started');
        $scrapeHelper = new ScrapeHelper('scrapeMember');

        $pageUrl = config('app.tfn_base_url') . '/view_member';
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

        // get the ID of members that were last updated over a month ago
        // get a maximum of 1000 at a time
        $results = Member::where('updated_at', '<', Carbon::now()->subMonth())
            ->whereNull('firstip')
            ->orderBy('id')
            ->take(1000)
            ->pluck('id');

        if (!count($results)) {
            Log::debug('ScrapeMember: No data to scrape');
            return;
        }

        foreach ($results as $member_id) {
            Log::debug('ScrapeMember: checking ' . $member_id);
            try {
                $page = $scrapeHelper->GetPage($pageUrl, ['user_id' => $member_id]);

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
                    Log::debug('ScrapeMember: Found IP ' . $firstIP . ' for ' . $member_id);
                } else {
                    Member::where('id', $member_id)->update([
                        'updated_at' => Carbon::now(),
                    ]);
                    Log::debug('ScrapeMember: No IP found for ' . $member_id);
                }

            } catch (\Throwable $th) {
                Log::debug('ScrapeMember: Scrape error: ' . $th->getMessage());
                continue;
            }
        }
    }

}
