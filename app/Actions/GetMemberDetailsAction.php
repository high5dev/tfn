<?php

namespace App\Actions;

use App\Helpers\ScrapeHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GetMemberDetailsAction
{
    public function execute($member_id): string
    {
        Log::debug('GetMemberDetails: Started');
        $scrapeHelper = new ScrapeHelper('getMember');

        $pageUrl = config('app.tfn_base_url') . '/view_member';
        $user = config('app.tfn_username');
        $password = config('app.tfn_password');

        // check if we're logged in
        if (!$scrapeHelper->isLoggedIn()) {
            Log::debug('GetMemberDetails: Logging in');
            // login
            $status = $scrapeHelper->Login($user, $password);
            if ($status !== true) {
                Log::debug('GetMemberDetails: Error logging in');
                return '';
            }
        }

        try {
            Log::debug('GetMemberDetails: Scraping');
            $page = $scrapeHelper->GetPage($pageUrl, ['user_id' => $member_id]);

            // create the DOM then load the page
            $dom = new \DOMDocument('1.0', 'UTF-8');
            @$dom->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

            // get the five tables in order
            $table1 = $dom->getElementsByTagName('table')->item(0);
            $table2 = $dom->getElementsByTagName('table')->item(1);
            $table3 = $dom->getElementsByTagName('table')->item(2);
            $table4 = $dom->getElementsByTagName('table')->item(3);
            $table5 = $dom->getElementsByTagName('table')->item(4);

            // create the array to hold the data
            $data = [
                'user_details' => [],
                'auth_tokens' => [],
                'group_membership' => [],
                'replies' => [],
                'post_details' => []
            ];

            // iterate over each row in the table
            $xr = 0;
            foreach ($table1->getElementsByTagName('tr') as $tr) {
                $tds = $tr->getElementsByTagName('td'); // get the columns in this row
                $xd = 0;
                foreach ($tds as $td) {
                    echo 'Row:' . $xr . ' | Col:' . $xd++ . ' | Data:' . $td->nodeValue . "\n";
                }
                $xr++;
            }
            dd('done');

            // first table: User details
            $tr = $table1->getElementsByTagName('tr')->item(0);
            $tds = $tr->getElementsByTagName('td');
            dd($tds);

            $data['user_details'] = [
                'user_id' => $tds->item(1)->nodeValue,
                'username' => $tds->item(3)->nodeValue,
                'email' => $tds->item(5)->nodeValue,
                'first_ip' => $tds->item(7)->nodeValue
            ];

            dd($data);

            // iterate over each row in the table
            foreach ($table2->getElementsByTagName('tr') as $tr) {
                $tds = $tr->getElementsByTagName('td'); // get the columns in this row
                foreach ($tds as $td) {
                    echo $td->nodeValue;
                    echo "\n";
                }
            }
            echo "\n";

            // iterate over each row in the table
            foreach ($table3->getElementsByTagName('tr') as $tr) {
                $tds = $tr->getElementsByTagName('td'); // get the columns in this row
                foreach ($tds as $td) {
                    echo $td->nodeValue;
                    echo "\n";
                }
            }

            echo '</pre>';

            dd('done');

        } catch (\Throwable $th) {
            Log::debug('GetMemberDetails: Exception: ' . $th->getMessage());
        }
        return '';
    }

}
