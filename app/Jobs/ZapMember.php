<?php

namespace App\Jobs;

use App\Actions\GetMemberDetailsAction;
use App\Models\Member;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ZapMember implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $report_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($report_id)
    {
        $this->report_id = $report_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug('ZapMember: x1');
        // retrieve the report
        $report = Report::where('id', $this->report_id)->first();

        if ($report) {

            Log::debug('ZapMember: x2');
            // get the member record
            $member = Member::where('id', $report->member_id)->first();

            if ($member) {

                Log::debug('ZapMember: x3');
                // get user details
                $memberDetails = new GetMemberDetailsAction($report->member_id);
                Log::debug('ZapMember: x4');
                $member_details = $memberDetails->getMember();
                Log::debug('ZapMember: x5');
                $member_replies = $memberDetails->getReplies();

                Log::debug('ZapMember: x6');
                // update the zap report
                $report->warning_emails = json_encode($member_replies);
                $report->body = json_encode($member_details);
                $report->save();

                Log::debug('ZapMember: x7');
                // send zap request to SpamTool
                $memberDetails->zapMember();

                Log::debug('ZapMember: x7');
                // delete all their posts if they have any
                if (isset($member->posts)) {
                    $member->posts()->delete();
                }

                Log::debug('ZapMember: x8');
                // delete the member
                $member->delete();

                Log::debug('ZapMember: zap completed successfully');
                return;
            }
            Log::debug('ZapMember: failed to get member record ' . $report->member_id);
            return;
        }
        Log::debug('ZapMember: failed to get report id ' . $this->report_id);
    }
}
