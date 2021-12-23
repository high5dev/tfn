<?php

namespace App\Actions;

use App\Models\Member;
use App\Models\Post;
use App\Models\Watchword;
use Illuminate\Support\Facades\Log;

class ScrapeAction
{
    public function __invoke(): void
    {
        Log::debug('Scrape started');

        $initialID = "80000000";
        $forceInitialID = false;

        // Get the watchwords list
        $results = Watchword::all();

        $wwEmails = [];
        $wwSubjects = [];
        if ($results and count($results)) {
            foreach ($results as $row) {
                if ('EMAIL' == $row['type']) {
                    $wwEmails[] = trim($row['theword']);
                } else {
                    $wwSubjects[] = trim($row['theword']);
                }
            }
        }

        // Login
        // TODO: Store session cookie, then retrieve it on subsequent runs
        // TODO: so we don't have to login every time this script is run.
        $status = Login();
        if ($status !== true) {
            Log::debug('Scrape: Error logging in');
            return;
        }

        // get spamcontrol entry page
        $homepage = config('app.tfn_base_url');
        $page = GetPage($homepage);
        if (false === strpos($page, 'You must log in using')) {
            Log::debug('Scrape: ' . $page);
            return;
        }

        $num_rows = 0;

        if ($forceInitialID) {
            $currentID = $initialID;
        } else {
            // read the last db entry for OFFERs
            $results = Post::where('type', 'OFFER')->orderBy('id', 'desc')->first();
            if ($results) {
                $currentID = $results['id'];
            } else {
                $currentID = $initialID;
            }
        }

        // save start Post ID
        $lastID = $currentID;

        // loop round and grab up to 1000 OFFER posts at a time
        do {
            // make sure we are on OFFERs
            $url = config('app.tfn_base_url') . '/type_limit_direction?Type=OFFER&Limit=1000&OpenPostIDs:=Spamtool&TablePreferences=Direction';
            $page = GetPage($url);
            if (false === strpos($page, 'please try to limit heavy use')) {
                Log::debug('Scrape: ' . $page);
                return;
            }
            // select the page
            $url = config('app.tfn_base_url') . "/navigation?SelectIDorPage=PostID&GoToNumber={$currentID}&Jump=Jump";
            $page = GetPage($url);
            if (false === strpos($page, 'please try to limit heavy use')) {
                Log::debug('Scrape: ' . $page);
                return;
            }

            // get the page
            $url = config('app.tfn_base_url') . '/display_posts';
            $page = GetPage($url);

            $DOM = new \DOMDocument('1.0', 'UTF-8');
            @$DOM->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

            $Header = $DOM->getElementsByTagName('th');
            $Detail = $DOM->getElementsByTagName('td');

            // Get header name of the table
            $aDataTableHeaderHTML = [];
            foreach ($Header as $NodeHeader) {
                $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
            }
            $aDataTableHeaderHTML[6] = 'flags';

            // Get row data/detail table without header name as key
            $i = 0;
            $j = 0;
            $aDataTableDetailHTML = [];
            foreach ($Detail as $sNodeDetail) {
                $aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
                $i = $i + 1;
                $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
            }

            // Get row data/detail table with header name as key and outer array index as row number

            for ($i = 0; $i < count($aDataTableDetailHTML); $i++) {
                if (count($aDataTableDetailHTML[$i]) != 7) {
                    Log::debug('Scrape: ' . print_r($aDataTableDetailHTML[$i]));
                    return;
                }
                for ($j = 0; $j < count($aDataTableHeaderHTML); $j++) {
                    $aTempData[$i][$aDataTableHeaderHTML[$j]] = $aDataTableDetailHTML[$i][$j];
                }
            }
            $aDataTableDetailHTML = $aTempData;unset($aTempData);

            foreach ($aDataTableDetailHTML as $row) {

                // skip blank rows
                if (trim($row['User']) == '') {
                    continue;
                }

                // extract the username from the email
                $sPattern = '/([^<]*)?(<)?(([\w\-\.\+]+)@((?:[\w-]+\.)+)([a-zA-Z]{2,63}))?(>)?/';
                preg_match($sPattern, $row['User'] . " ", $aMatch);
                $user = (isset($aMatch[1])) ? $aMatch[1] : '';
                $user = trim($user);
                $email = (isset($aMatch[3])) ? $aMatch[3] : '';
                $email = trim($email);

                // extract the datetime
                $date = explode(' ', trim($row['Time']));
                $dated = date('Y') . '-' . trim($date[0]) . ' ' . $date[1] . ':00';
                $dated = trim($dated);

                // get the current post ID
                $currentID = intval(trim($row['Post ID']));

                // check it is incrementing
                if ($currentID < $lastID) {
                    Log::debug('Scrape: OFFERs not incrementing');
                    return;
                }

                // if this a new user?
                // depicted by an asterisk as first character
                $joined_recently = 0;
                $member_id = trim($row['User ID']);
                if ($member_id[0] == '*') {
                    $joined_recently = 1;
                    $member_id = substr($member_id, 1);
                }

                $subject = trim($row['Subject']);

                // default is not spam
                $spam = 0;
                // check for email watchwords
                foreach ($wwEmails as $word) {
                    if (false !== stripos($email, $word)) {
                        $spam = 1;
                    }
                }
                // check for subject watchwords
                foreach ($wwSubjects as $word) {
                    if (false !== stripos($subject, $word)) {
                        $spam = 1;
                    }
                }

                if (member_id_exists($member_id)) {
                    update_member([
                        'member_id' => $member_id,
                        'dated' => $dated,
                    ]);
                } else {
                    create_member([
                        'member_id' => $member_id,
                        'user' => $user,
                        'email' => $email,
                        'dated' => $dated,
                        'joined_recently' => $joined_recently,
                    ]);
                }

                // insert post
                $values = [
                    'member_id' => $member_id,
                    'subject' => $subject,
                    'grp' => trim($row['Group']),
                    'dated' => $dated,
                    'status' => strtoupper(trim($row['flags'])) == 'A' ? 'Active' : 'Pending',
                    'spam' => $spam,
                    'type' => "OFFER",
                ];
                Post::updateOrInsert(
                    ['id' => $currentID],
                    $values
                );
            }
            $num = count($aDataTableDetailHTML);

            $num_rows += $num;
            $lastID = $currentID;

        } while (1000 == count($aDataTableDetailHTML));

        if ($forceInitialID) {
            $currentID = $initialID;
        } else {
            // read the last db entry for WANTEDs
            $results = Post::where('type', 'WANTED')->orderBy('id', 'desc')->first();
            if ($results) {
                $currentID = $results['id'];
            } else {
                $currentID = $initialID;
            }
        }

        $lastID = $currentID;

        // loop round and grab up to 1000 WANTED posts at a time
        do {
            // make sure we are on WANTEDs
            $url = config('app.tfn_base_url') . '/type_limit_direction?Type=WANTED&Limit=1000&OpenPostIDs:=Spamtool&TablePreferences=Direction';
            $page = GetPage($url);
            if (false === strpos($page, 'please try to limit heavy use')) {
                Log::debug('Scrape: ' . $page);
                return;
            }

            // select the page
            $url = config('app.tfn_base_url') . "/navigation?SelectIDorPage=PostID&GoToNumber={$currentID}&Jump=Jump";
            $page = GetPage($url);
            if (false === strpos($page, 'please try to limit heavy use')) {
                Log::debug('Scrape: ' . $page);
                return;
            }

            // get the page
            $url = config('app.tfn_base_url') . '/display_posts';
            $page = GetPage($url);

            $DOM = new \DOMDocument('1.0', 'UTF-8');
            @$DOM->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));

            $Header = $DOM->getElementsByTagName('th');
            $Detail = $DOM->getElementsByTagName('td');

            //#Get header name of the table
            $aDataTableHeaderHTML = [];
            foreach ($Header as $NodeHeader) {
                $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
            }
            $aDataTableHeaderHTML[6] = 'flags';

            //#Get row data/detail table without header name as key
            $i = 0;
            $j = 0;
            $aDataTableDetailHTML = [];
            foreach ($Detail as $sNodeDetail) {
                $aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
                $i = $i + 1;
                $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
            }

            //#Get row data/detail table with header name as key and outer array index as row number
            for ($i = 0; $i < count($aDataTableDetailHTML); $i++) {
                if (count($aDataTableDetailHTML[$i]) != 7) {
                    Log::debug('Scrape: ' . print_r($aDataTableDetailHTML[$i]));
                    return;
                }
                for ($j = 0; $j < count($aDataTableHeaderHTML); $j++) {
                    $aTempData[$i][$aDataTableHeaderHTML[$j]] = $aDataTableDetailHTML[$i][$j];
                }
            }
            $aDataTableDetailHTML = $aTempData;unset($aTempData);

            foreach ($aDataTableDetailHTML as $row) {

                // skip blank rows
                if (trim($row['User']) == '') {
                    continue;
                }

                // extract the username and email
                $sPattern = '/([^<]*)?(<)?(([\w\-\.\+]+)@((?:[\w-]+\.)+)([a-zA-Z]{2,63}))?(>)?/';
                preg_match($sPattern, $row['User'] . " ", $aMatch);
                $user = (isset($aMatch[1])) ? $aMatch[1] : '';
                $user = trim($user);
                $email = (isset($aMatch[3])) ? $aMatch[3] : '';
                $email = trim($email);

                // extract the datetime
                $date = explode(' ', trim($row['Time']));
                $dated = date('Y') . '-' . trim($date[0]) . ' ' . $date[1] . ':00';
                $dated = trim($dated);

                // get the current post ID
                $currentID = intval(trim($row['Post ID']));

                // check it is incrementing
                if ($currentID < $lastID) {
                    Log::debug('Scrape: WANTEDs not incrementing');
                    return;
                }

                // if this a new user?
                // depicted by an asterisk as first character
                $joined_recently = 0;
                $member_id = trim($row['User ID']);
                if ($member_id[0] == '*') {
                    $joined_recently = 1;
                    $member_id = substr($member_id, 1);
                }

                $subject = trim($row['Subject']);

                // default is not spam
                $spam = 0;
                // check for email watchwords
                foreach ($wwEmails as $word) {
                    if (false !== stripos($email, $word)) {
                        $spam = 1;
                    }
                }
                // check for subject watchwords
                foreach ($wwSubjects as $word) {
                    if (false !== stripos($subject, $word)) {
                        $spam = 1;
                    }
                }

                if (member_id_exists($member_id)) {
                    update_member([
                        'member_id' => $member_id,
                        'dated' => $dated,
                    ]);
                } else {
                    create_member([
                        'member_id' => $member_id,
                        'user' => $user,
                        'email' => $email,
                        'dated' => $dated,
                        'joined_recently' => $joined_recently,
                    ]);
                }

                // insert post
                $values = [
                    'member_id' => $member_id,
                    'subject' => $subject,
                    'grp' => trim($row['Group']),
                    'dated' => $dated,
                    'status' => strtoupper(trim($row['flags'])) == 'A' ? 'Active' : 'Pending',
                    'spam' => $spam,
                    'type' => "WANTED",
                ];
                Post::updateOrInsert(
                    ['id' => $currentID],
                    $values
                );
            }
            $num = count($aDataTableDetailHTML);

            $num_rows += $num;
            $lastID = $currentID;

        } while (1000 == count($aDataTableDetailHTML));
    }
}

function Login()
{
    $url = config('app.tfn_base_url');
    $var = [];
    $vars = [];
    $vars['user'] = trim(config('app.tfn_username'));
    $vars['password'] = trim(config('app.tfn_password'));
    $var['Origin'] = $url;
    $data = httpPost($url . '/login', $vars);

    $xdata = SaveSession($data);
    $xpage = $data['body'];
    if (strpos($xpage, 'Invalid username/email or password.') === false) {
        return true;
    } else {
        return false;
    }
}

function GetPage($url, $redirect = 10)
{
    $loop = true;
    for ($i = 0; $loop == true; $i++) {
        $loop = false;
        $i++;
        $redirect--;
        $session = GetSession();
        $data = httpGet($url, $session);
        $page = $data['body'];
        SaveSession($data);

        if ($data['status'] == '200' && $redirect) {
            $loop = true;
        }
    }
    return $page;
}

function SaveSession($cook)
{
    if ($cook['cookie'] === null) {
        $cook1 = [];
    } else {
        $cook1 = $cook['cookie'];
    }

    Storage::put('tfn_session', json_encode($cook1->toArray()));
    return true;
}

function GetSession()
{
    $cookies = json_decode(Storage::get('tfn_session'), ASSOCIATIVE);
    if ($cookies) {
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        foreach ($cookies as $cookie) {
            $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie($cookie));
        }
        return $jar;
    } else {
        return [];
    }

}

function httpPost($url, $data)
{
    $domain = parse_url($url, PHP_URL_HOST);

    $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($data, $domain);
    $client = new \GuzzleHttp\Client([
        'base_uri' => $url,
        'cookies' => $cookieJar,
    ]);

    $headers = [];
    $headers['Content-Type'] = 'application/x-www-form-urlencoded;';

    $response = $client->request('POST', $url, ['form_params' => $data, 'headers' => $headers]);

    $result['body'] = ($response->getBody()->getContents());
    $result['cookie'] = $cookieJar;
    $result['header'] = $response->getHeaders();

    return $result;
}

function httpGet($url, $cookie = 0)
{
    $domain = parse_url($url, PHP_URL_HOST);

    $client = new \GuzzleHttp\Client([
        'base_uri' => $url,
        'cookies' => $cookie ? $cookie : null,
    ]);

    $response = $client->request('GET', $url);

    $result['body'] = ($response->getBody()->getContents());
    $result['cookie'] = $cookie ? $cookie : null;
    $result['status'] = $response->getStatusCode();

    return $result;
}

function member_id_exists($id)
{
    $count = Member::where('id', $id)->count();
    return $count ? true : false;
}

function create_member($data)
{
    $new_member = new Member();
    $new_member->id = $data['member_id'];
    $new_member->username = $data['user'];
    $new_member->email = $data['email'];
    $new_member->joined_recently = $data['joined_recently'];
    $new_member->created_at = $data['dated'];
    $new_member->updated_at = $data['dated'];
    $new_member->save();
}

function update_member($data)
{
    Member::where('id', $data['member_id'])->where('created_at', '>', $data['dated'])->update(['created_at' => $data['dated']]);
}
