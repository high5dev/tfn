<?php

namespace App\Actions;

use App\Helpers\ScrapeHelper;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GetAllMembersAction
{
    public function __invoke(): void
    {
        Log::debug('ScrapeMember: Started');
        $scrapeHelper = new ScrapeHelper('scrapeMember');

        $pageUrl = config('app.tfn_base_url') . '/view_member';
        $user = config('app.tfn_username');
        $password = config('app.tfn_password');

        // check if we're logged in
        if (!$scrapeHelper->isLoggedIn()) {
            // login
            Log::debug('ScrapeMember: Logging in');
            $status = $scrapeHelper->Login($user, $password);
            if ($status !== true) {
                Log::debug('Scrape: Error logging in');
                return;
            }
        }

        // check all IDs starting at 1
        for ($member_id = 1; $member_id < 31522154; $member_id++) {
            Log::debug('GetAllMembers: checking id ' . $member_id);

            // check if the member exists in our database
            if (Member::where('id', $member_id)->exists()) {
                // if it exists don't do anymore, go get the next member id
                continue;
            }

            try {
                $page = $scrapeHelper->GetPage($pageUrl, ['user_id' => $member_id]);

                $DOM = new \DOMDocument('1.0', 'UTF-8');
                @$DOM->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

                $trNodes = $DOM->getElementsByTagName('tr');

                $firstIP = '';

                foreach ($trNodes as $trNode) {
                    $trContent = $trNode->textContent;

                    $ipContent = 'Username: ';
                    if (Str::contains($trContent, $ipContent)) {
                        $username = trim(str_replace($ipContent, "", $trContent));
                    }

                    $ipContent = 'Email: ';
                    if (Str::contains($trContent, $ipContent)) {
                        $email = trim(str_replace($ipContent, "", $trContent));
                    }

                    $ipContent = 'First IP address: ';
                    if (Str::contains($trContent, $ipContent)) {
                        $ip = trim(str_replace($ipContent, "", $trContent));
                        $firstIP = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
                    }
                }

                Member::create([
                    'id' => $member_id,
                    'username' => $username,
                    'email' => $email,
                    'firstip' => $firstIP,
                    'joined_recently' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                Log::debug('GetAllMembers: Added new member ID ' . $member_id);

            } catch (\Throwable $th) {
                Log::debug('ScrapeMember: Scrape error: ' . $th->getMessage());
                continue;
            }
        }
    }

}
