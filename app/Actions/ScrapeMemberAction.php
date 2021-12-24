<?php

namespace App\Actions;

use App\Helpers\ScrapeHelper;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ScrapeMemberAction
{
    // Sorry, I do not have a Skype account
    // You can email me: chris@comgw.co.uk

    public function __invoke(): void
    {
        Log::debug('ScrapeMember: started');
        $scrapeHelper = new ScrapeHelper();

        $pageUrl = config('app.tfn_base_url') . '/view_member';
        $user = config('app.tfn_username');
        $password = config('app.tfn_password');

        // get the ID of all members that were last updated over a month ago
        $results = Member::where('updated_at', '<', Carbon::now()->subMonth())
            ->whereNull('firstip')
            ->pluck('id');

        if (!count($results)) {
            Log::debug('ScrapeMember: No data to scrape');
            return;
        }

        $login = $scrapeHelper->Login($user, $password);
        if ($login !== true) {
            Log::debug('ScrapeMember: failed to log in');
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
                    Log::debug('ScrapeMember: No IP found for ' . $member_id);
                }
            } catch (\Throwable $th) {
                Log::debug('ScrapeMember: scraping error. ' . $th->getMessage());
                continue;
            }
        }
    }

}
