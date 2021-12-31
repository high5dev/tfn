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
        $scrapeHelper = new ScrapeHelper('getMember');

        $pageUrl = config('app.tfn_base_url') . '/view_member';
        $user = config('app.tfn_username');
        $password = config('app.tfn_password');

        // check if we're logged in
        if (!$scrapeHelper->isLoggedIn()) {
            // login
            $status = $scrapeHelper->Login($user, $password);
            if ($status !== true) {
                Log::debug('GetMemberDetails: error logging in');
                 return '';
            }
        }

        try {
            $page = $scrapeHelper->GetPage($pageUrl, ['user_id' => $member_id]);

            $dom = new \DOMDocument('1.0', 'UTF-8');
            @$dom->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

            $table1 = $dom->getElementsByTagName('table')->item(0);
            $table2 = $dom->getElementsByTagName('table')->item(1);
            $table3 = $dom->getElementsByTagName('table')->item(2);

            // iterate over each row in the table
            foreach($table1->getElementsByTagName('tr') as $tr)
            {
                $tds = $tr->getElementsByTagName('td'); // get the columns in this row
                foreach($tds as $td) {
                    echo $td->nodeValue;
                }
            }

            // iterate over each row in the table
            foreach($table2->getElementsByTagName('tr') as $tr)
            {
                $tds = $tr->getElementsByTagName('td'); // get the columns in this row
                foreach($tds as $td) {
                    echo $td->nodeValue;
                }
            }

            // iterate over each row in the table
            foreach($table3->getElementsByTagName('tr') as $tr)
            {
                $tds = $tr->getElementsByTagName('td'); // get the columns in this row
                foreach($tds as $td) {
                    echo $td->nodeValue;
                }
            }

        } catch (\Throwable $th) {
            Log::debug('GetMemberDetails: Exception: ' . $th->getMessage());
        }
        return '';
    }

}
