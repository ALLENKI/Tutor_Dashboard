<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\TeacherCertification;

use Aham\Managers\SuperMail;

class SendTeacherCertifiedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $certification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TeacherCertification $certification)
    {
        $this->certification = $certification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->certification->teacher->user;

        SuperMail::mail('emails_new.teacher.certified',['certification' => $this->certification, 'user' => $user],'Your Certification Has Been Upgraded',
                [$user->email => $user->name]);
    }
}
