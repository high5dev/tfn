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

            /*
             * first table: User details
             *
             * User ID:             <integer>
             * Username:            <string>
             * Email:               <string>
             * First IP address:    <string>
             */

            // iterate over each row in table1
            foreach ($table1->getElementsByTagName('tr') as $tr) {
                // get the columns in this row
                $tds = $tr->getElementsByTagName('td');
                switch (trim($tds->item(0)->nodeValue)) {
                    case('User ID:'):
                        $data['user_details']['user_id'] = trim($tds->item(1)->nodeValue);
                        break;
                    case('Username:'):
                        $data['user_details']['username'] = trim($tds->item(1)->nodeValue);
                        break;
                    case('Email:'):
                        $data['user_details']['email'] = trim($tds->item(1)->nodeValue);
                        break;
                    case('First IP address:'):
                        $data['user_details']['first_ip'] = trim($tds->item(1)->nodeValue);
                        break;
                }
            }

            /*
             * second table: Auth tokens
             *
             * first row: Token | Created | Last seen | IP address | Country
             * subsequent rows (zero or more) contain the actual data
             * Token:       <string>
             * Created:     <date>
             * Last seen:   <datetime>
             * IP address:  <string>
             * Country:     <string>
             */

            // iterate over each row in table2
            $i = 0;
            foreach ($table2->getElementsByTagName('tr') as $tr) {
                // get all the columns in this row
                $tds = $tr->getElementsByTagName('td');
                // populate the array
                $data['auth_tokens'][$i]['token'] = trim($tds->item(0)->nodeValue);
                $data['auth_tokens'][$i]['created'] = trim($tds->item(1)->nodeValue);
                $data['auth_tokens'][$i]['last_Seen'] = trim($tds->item(2)->nodeValue);
                $data['auth_tokens'][$i]['ip'] = trim($tds->item(3)->nodeValue);
                $data['auth_tokens'][$i]['country'] = trim($tds->item(4)->nodeValue);
                $i++;
            }

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
