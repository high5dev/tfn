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
        $scrapeHelper = new ScrapeHelper();

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
            $start = strpos($page, '<table>');
            $stop = strpos($page, '</body>');
            return substr($page, $start, $stop);
        } catch (\Throwable $th) {
            Log::debug('GetMemberDetails: Exception: ' . $th->getMessage());
        }
        return '';
    }

}
