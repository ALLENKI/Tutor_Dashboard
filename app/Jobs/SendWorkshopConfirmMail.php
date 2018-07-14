<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\User;
use Aham\Models\SQL\UserEnrollment;

use Aham\Managers\SuperMail;

class SendWorkshopConfirmMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $enrollment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $enrollment = $this->enrollment;
        $user = $enrollment->user;

        SuperMail::mail('emails_new.workshops.confirm',['user' => $user,'enrollment' => $enrollment],'You have enrolled to Art Workshop at Aham',
                [$user->email => $user->name]);
    }
}
