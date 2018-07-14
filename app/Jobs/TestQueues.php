<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Helpers\SMSHelper;

use Aham\Managers\SuperMail;

use Aham\Helpers\HipchatHelper;

class TestQueues implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        SuperMail::mail('emails_new.test',[],'Testing Queues For Aham Deployment Setup',
                ['rajivs.iitkgp@gmail.com' => 'Rajiv Seelam']);

        $smsHelper = new SMSHelper();

        $smsHelper->sendMessage('8897587656','Testing Queues For Aham Deployment Setup');

        $hipchat = new HipchatHelper();

        $hipchat->sendMessage('Aham',
        [
            'message' => 'Testing Queues For Aham Deployment Setup',
            'id' => 'Aham',
            'from' => env('APP_URL'),
            'notify' => true,
            'color' => 'green'
        ]);   
    }
}
