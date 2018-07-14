<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\ClassInvitation;

use Aham\Managers\SuperMail;

class SendTeacherInvitedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invite;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ClassInvitation $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->invite->teacher->user;

        SuperMail::mail('emails_new.teacher.invited',['invite' => $this->invite, 'user' => $user, 'class' =>$this->invite->ahamClass],'You got an Invitation',
                [$user->email => $user->name]);
    }
}
