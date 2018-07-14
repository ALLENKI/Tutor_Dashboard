<?php

namespace Aham\Jobs\Teacher;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\ClassInvitation;

use Aham\Helpers\PushHelper;

class SendAwardedPushNotification implements ShouldQueue
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
        $invite = $this->invite;
        $ahamClass = $this->invite->ahamClass;

        $tokens = $user->pushTokens()->where(['source' => 'aham_tutor','type'=> 'android'])->get();

        $title = 'New Class';
        $body = 'You have been awarded class on '.$ahamClass->topic->name;
        $data = [];

        $data['type'] = 'class_invitation';
        $data['title'] = $title;
        $data['body'] = $body;
        $data['invitation_id'] = $invite->id;
        $data['class_id'] = $ahamClass->id;

        foreach($tokens as $token)
        {
            PushHelper::sendFCMMessageTutorApp($title, $body, $token->push_id, $data);
        }

    }
}
