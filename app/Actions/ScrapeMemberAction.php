<?php

namespace App\Actions;

use App\Helpers\ScrapeHelper;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ScrapeMemberAction
{
    /**
     * called from \App\Console\Kernel
     *
     * grab all members that were last updated over a month ago. 1,000 at a time.
     * update their details in the local database.
     */
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

        // get the ID of members that were last updated over a month ago
        // get a maximum of 1000 at a time
        $members = Member::where('updated_at', '<', Carbon::now()->subMonth())
            ->whereNull('firstip')
            ->orderBy('id')
            ->take(1000)
            ->get();

        if (!count($results)) {
            Log::debug('ScrapeMember: No data to scrape');
            return;
        }

        // iterate over each member from local database
        foreach ($members as $member) {
            Log::debug('ScrapeMember: checking ' . $member->id);

            // load the current values for this member
            $username = $member->username;
            $email = $member->email;
            $first_ip = $member->first_ip;
            $status = $member->status;

            try {
                // grab the member's details from ST
                $page = $scrapeHelper->GetPage($pageUrl, ['user_id' => $member_id]);

                $dom = new \DOMDocument('1.0', 'UTF-8');
                @$dom->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

                // get the first table on the page: "User details"
                $table1 = $dom->getElementsByTagName('table')->item(0);

                // iterate over each row in this table
                foreach ($table1->getElementsByTagName('tr') as $tr) {
                    // get the columns in this row
                    $tds = $tr->getElementsByTagName('td');

                    // find which row we are processing on this loop
                    switch (trim($tds->item(0)->nodeValue)) {
                        case('Username:'):
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
                            $email = trim($tds->item(1)->nodeValue);
                            break;
                        case('First IP address:'):
                            $first_ip = trim($tds->item(1)->nodeValue);
                            $first_ip = filter_var($first_ip, FILTER_VALIDATE_IP) ? $first_ip : '';
                            break;
                    }
                }

                // update the member's record
                Member::where('id', $member_id)->update([
                    'username' => $username,
                    'email' => $email,
                    'firstip' => $first_ip,
                    'status' => $status,
                    'updated_at' => Carbon::now(),
                ]);

                // TODO: get the second table: "Auth tokens"
                //  and check for additional IP addresses

                Log::debug('ScrapeMember: ID:' . $member_id . ' updated');

            } catch (\Throwable $th) {
                Log::debug('ScrapeMember: Scrape error: ' . $th->getMessage());
                continue;
            }

        }
    }

}
