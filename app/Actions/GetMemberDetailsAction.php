<?php

namespace App\Actions;

use App\Helpers\ScrapeHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GetMemberDetailsAction
{
    protected $member_id;
    protected $scrapeHelper;

    /**
     * create a new instance.
     */
    public function __construct($member_id = 0)
    {
        // store the member_id
        $this->member_id = $member_id;
        // helper class
        $this->scrapeHelper = new ScrapeHelper('getMember');

        // login creds
        $user = config('tfn.tfn_username');
        $password = config('tfn.tfn_password');

        // check if we're logged in
        if (!$this->scrapeHelper->isLoggedIn()) {
            Log::debug('GetMemberDetails: Logging in');
            // login
            $status = $this->scrapeHelper->Login($user, $password);
            if ($status !== true) {
                Log::debug('GetMemberDetails: Error logging in');
            }
        }
    }

    /**
     * retrieve the member's details
     */
    public function getMember(): array
    {
        Log::debug('getMember: start');

        // data to return
        $data = [];

        // get the "User details" page
        $url = config('tfn.tfn_base_url') . '/view_member';
        $page = $this->scrapeHelper->GetPage($url, ['user_id' => $this->member_id]);

        try {
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
                'user_details' => [
                    'user_id' => 0,
                    'username' => '',
                    'email' => '',
                    'first_ip' => '',
                    'status' => 'Active'
                ],
                'auth_tokens' => [],
                'group_membership' => [],
                'replies' => [],
                'post_details' => []
            ];

            /*
             * first table: User details
             *
             * User ID:             <integer>
             * Username:            <string>    Has leading ! if zapped or deleted
             * Email:               <string>
             * First IP address:    <string>
             */

            // iterate over each row in table1
            foreach ($table1->getElementsByTagName('tr') as $tr) {
                // get the columns in this row
                $tds = $tr->getElementsByTagName('td');

                // find which row we are processing
                switch (trim($tds->item(0)->nodeValue)) {
                    case('User ID:'):
                        $data['user_details']['user_id'] = trim($tds->item(1)->nodeValue);
                        break;
                    case('Username:'):
                        $username = trim($tds->item(1)->nodeValue);
                        // check if this member has been zapped/deleted
                        if ('!' == $username[0]) {
                            // were they zapped or deleted?
                            // Zapped has member id in curly braces, deleted member id is round brackets.
                            if (false !== strpos($username, '{')) {
                                $data['user_details']['status'] = 'Zapped';
                            } else {
                                $data['user_details']['status'] = 'Deleted';
                            }
                            // strip the leading '!' and copy up to, but not including the space
                            // Example: "!johndoe {32341212}"
                            $username = substr($username, 1, (strpos($username, ' ') - 1));
                        }
                        $data['user_details']['username'] = $username;
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
                if (count($tds)) {
                    // populate the array
                    $data['auth_tokens'][$i]['token'] = trim($tds->item(0)->nodeValue);
                    $data['auth_tokens'][$i]['created'] = trim($tds->item(1)->nodeValue);
                    $data['auth_tokens'][$i]['last_Seen'] = trim($tds->item(2)->nodeValue);
                    $data['auth_tokens'][$i]['ip'] = trim($tds->item(3)->nodeValue);
                    $data['auth_tokens'][$i]['country'] = trim($tds->item(4)->nodeValue);
                    $i++;
                }
            }

            /*
             * third table: Group membership
             *
             * first row: Group | Region
             * subsequent rows (zero or more) contain the actual data
             * Group:   <string>
             * Region:  <date>
             */

            // iterate over each row in table2
            $i = 0;
            foreach ($table3->getElementsByTagName('tr') as $tr) {
                // get all the columns in this row
                $tds = $tr->getElementsByTagName('td');
                if (count($tds)) {
                    // populate the array
                    $data['group_membership'][$i]['group'] = trim($tds->item(0)->nodeValue);
                    $data['group_membership'][$i]['region'] = trim($tds->item(1)->nodeValue);
                    $i++;
                }
            }

            /*
             * fourth table: Replies
             *
             * first row: ID | Recipient | Subject | Date/time | Post
             * subsequent rows (zero or more) contain the actual data
             * ID:          <integer>
             * Recipient:   <email>
             * Subject:     <string>
             * Date/time:   <datetime>
             * Post:        <string>
             */

            // iterate over each row in table2
            $i = 0;
            foreach ($table4->getElementsByTagName('tr') as $tr) {
                // get all the columns in this row
                $tds = $tr->getElementsByTagName('td');
                if (count($tds)) {
                    // populate the array
                    $data['replies'][$i]['id'] = trim($tds->item(0)->nodeValue);
                    $data['replies'][$i]['recipient'] = trim($tds->item(1)->nodeValue);
                    $data['replies'][$i]['subject'] = trim($tds->item(2)->nodeValue);
                    $data['replies'][$i]['dated'] = trim($tds->item(3)->nodeValue);
                    $data['replies'][$i]['post'] = trim($tds->item(4)->nodeValue);
                    $i++;
                }
            }

            /*
             * fifth table: Post details
             *
             * first row: Post ID | Type | Subject | Group | Post Date | Emails sent
             * subsequent rows (zero or more) contain the actual data
             * Post ID:     <integer>
             * Type:        <string>
             * Subject:     <string>
             * Group:       <string>
             * Post Date:   <date>
             * Emails sent: <string>
             */

            // iterate over each row in table2
            $i = 0;
            foreach ($table5->getElementsByTagName('tr') as $tr) {
                // get all the columns in this row
                $tds = $tr->getElementsByTagName('td');
                if (count($tds)) {
                    // populate the array
                    $data['post_details'][$i]['post_id'] = trim($tds->item(0)->nodeValue);
                    $data['post_details'][$i]['type'] = trim($tds->item(1)->nodeValue);
                    $data['post_details'][$i]['subject'] = trim($tds->item(2)->nodeValue);
                    $data['post_details'][$i]['group'] = trim($tds->item(3)->nodeValue);
                    $data['post_details'][$i]['post_date'] = trim($tds->item(4)->nodeValue);
                    $data['post_details'][$i]['emails_sent'] = trim($tds->item(5)->nodeValue);
                    $i++;
                }
            }

        } catch (\Throwable $th) {
            Log::debug('GetMemberDetails: Exception: ' . $th->getMessage());
        }

        return $data;

    }

    /**
     * retrieve any replies
     */
    public function getReplies(): array
    {
        Log::debug('getReplies: Start');

        // return data
        $data = [];

        // get the 'User details' page
        $url = config('tfn.tfn_base_url') . '/view_replies_received?user_id=' . $this->member_id;
        $page = $this->scrapeHelper->GetPage($url);
        Log::debug('getReplies: got page');

        try {
            // create the DOM then load the page
            $dom = new \DOMDocument('1.0', 'UTF-8');
            @$dom->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

            // get the textarea (there is only one and it contains the emails we want)
            $textarea = $dom->getElementsByTagName('textarea')->item(0);

            if ($textarea) {
                // get the contents of the textarea
                $emails = $textarea->textContent;
                if (strlen($emails)) {
                    $data = array_map('trim', explode(',', $emails));
                }
            }

        } catch (\Throwable $th) {
            Log::debug('GetMemberDetails: Exception: ' . $th->getMessage());
        }

        return $data;
    }

    /**
     * send a zap request to SpamTool
     *
     * <form method="post" action="https://spamcontrol.freecycle.org/zap_member">
     * <input type='hidden' name='user_id' id='user_id' value="31465118" />
     * <input type='submit' value="Zap Member" />
     * </form>
     */
    public function zapMember(): bool
    {
        // return value
        $result = false;

        // send the zap to SpamTool
        $url = config('tfn.tfn_base_url') . '/zap_member';
        $page = $this->scrapeHelper->GetPage($url, ['user_id' => $this->member_id]);

        if (stripos($page, 'The zap probably succeeded') !== false) {
            $result = true;
        }

        return $result;
    }

}
