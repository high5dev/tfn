<?php

namespace App\Actions;

use App\Helpers\ScrapeHelper;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GetAllMembersAction
{
    /**
     * called from \App\Console\Kernel
     *
     * iterates over all User ID's
     * if not found in local database:
     *      download "User details" page from ST
     *      create member and update record
     */

    public function __invoke(): void
    {
        Log::debug('GetAllMembers: Started');

        // instantiate helper
        $scrapeHelper = new ScrapeHelper('scrapeMember');

        // some useful constants
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

        // iterate over all IDs starting at 1
        for ($member_id = 1; $member_id < 31522154; $member_id++) {
            Log::debug('GetAllMembers: checking id ' . $member_id);

            // check if the member exists in our database
            if (Member::where('id', $member_id)->exists()) {
                // if it exists don't do anymore, go get the next member id
                continue;
            }

            try {
                // grab the member's details from ST
                $page = $scrapeHelper->GetPage($pageUrl, ['user_id' => $member_id]);

                $dom = new \DOMDocument('1.0', 'UTF-8');
                @$dom->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

                // get the first table on the page (User details)
                $table1 = $dom->getElementsByTagName('table')->item(0);

                $username = '';
                $email = '';
                $first_ip = '';
                $status = 'Active';

                // iterate over each row in this table
                foreach ($table1->getElementsByTagName('tr') as $tr) {
                    // get the columns in this row
                    $tds = $tr->getElementsByTagName('td');

                    // find which row we are processing in this loop
                    switch (trim($tds->item(0)->nodeValue)) {
                        case('Username:'):
                            Log::debug('X:1');
                            $username = trim($tds->item(1)->nodeValue);
                            // check if this member has been zapped/deleted
                            if ('!' == $username[0]) {
                                // were they zapped or deleted?
                                // Zapped has member id in curly braces, deleted member id is round brackets.
                                if (false !== strpos($username, ' {')) {
                                    $status = 'Zapped';
                                } else {
                                    $status = 'Deleted';
                                }
                                // strip the leading '!' and copy up to, but not including the space
                                // Example: "!johndoe {32341212}"
                                $username = substr($username, 1, (strpos($username, ' ') - 1));
                            }
                            break;
                        case('Email:'):
                            Log::debug('X:2');
                            $email = trim($tds->item(1)->nodeValue);
                            break;
                        case('First IP address:'):
                            Log::debug('X:3');
                            $first_ip = trim($tds->item(1)->nodeValue);
                            $first_ip = filter_var($first_ip, FILTER_VALIDATE_IP) ? $first_ip : '';
                            break;
                    }
                }

                Log::debug('X:Z');
                // TODO: get the second table: "Auth tokens"
                //  and check for additional IP addresses

                // create the new member's record
                if (strlen($username)) {
                    Member::create([
                        'id' => $member_id,
                        'username' => $username,
                        'email' => $email,
                        'firstip' => $first_ip,
                        'status' => $status,
                        'joined_recently' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    Log::debug('GetAllMembers: Added new member ID ' . $member_id);
                }

            } catch (\Throwable $th) {
                Log::debug('GetAllMembers: Scrape error: ' . $th->getMessage());
                continue;
            }
        }

    }

}
