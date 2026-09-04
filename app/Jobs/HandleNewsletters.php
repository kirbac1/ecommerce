<?php

namespace App\Jobs;

use App\Jobs\Job;
use Carbon\Carbon;
use App\Newsletter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleNewsletters extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newsletters = Newsletter::where('scheduled_at', '<', Carbon::now())->whereNull('launchedAt');
        foreach($newsletters as $newsletter) {
            $newsletter->update([
                'launched_at' => Carbon::now(),
            ]);

            foreach($newsletter->groups as $group) {
                foreach($group->customer as $customer) {
                    $message = "Launching NL " . $newsletter->id . " to " . $customer->name . ". The title is: " . $newsletter->title;
                    \Log::info($message);
                }
            }

            $newsletter->update([
                'completed_at' => Carbon::now(),
            ]);
        }
    }
}
