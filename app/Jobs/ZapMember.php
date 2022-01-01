<?php

namespace App\Jobs;

use App\Actions\GetMemberDetailsAction;
use App\Models\Member;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        /**
         * <form method="post" action="https://spamcontrol.freecycle.org/zap_member">
         * <input type='hidden' name='user_id' id='user_id' value="31465118" />
         * <input type='submit' value="Zap Member" />
         * </form>
         */

        // retrieve the report
        $report = Report::where('id', $this->report_id)->first();

        if ($report) {

            // get the member record
            $member = Member::where('id', $report->member_id)->first();

            if ($member) {

                // get user details
                $memberDetails = new GetMemberDetailsAction($report->member_id);
                $member_details = $memberDetails->getMember();
                $member_replies = $memberDetails->getReplies();

                $report->warning_emails = json_encode($member_replies);
                $report->body = json_encode($member_details);
                $report->save();

                // TODO: send zap request to SpamTool

                // delete all their posts if they have any
                if (isset($member->posts)) {
                    //$member->posts()->delete();
                }

                // delete the member
                //$member->delete();

                Log::debug('ZapMember: zap completed successfully');
                return;
            }
            Log::debug('ZapMember: failed to get member record ' . $report->member_id);
            return;
        }
        Log::debug('ZapMember: failed to get report id ' . $this->report_id);
    }
}
