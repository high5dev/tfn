<?php

namespace App\Jobs;

use App\Actions\GetMemberDetailsAction;
use App\Mail\Warnings;
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

                // update the zap report
                $report->warning_emails = json_encode($member_replies);
                $report->body = json_encode($member_details);
                $report->save();

                // get the variables for the warning emails
                $data = [
                    'name' => $report->user->name,
                    'item' => $report->title,
                    'date' => $report->dated,
                ];

                // prepare the email
                $message = (new Warnings($data))
                    ->onConnection('database')
                    ->onQueue('emails');

                // send a separate email to each recipient
                foreach ($member_replies as $recipient) {
                    Mail::to($recipient)->queue($message);
                }

                // send zap request to SpamTool
                $memberDetails->zapMember();

                // delete all their posts if they have any
                if (isset($member->posts)) {
                    $member->posts()->delete();
                }

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
