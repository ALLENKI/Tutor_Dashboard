<?php

namespace Aham\Jobs\Teacher;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\AhamClass;

use Aham\Helpers\PushHelper;

class SendClassPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ahamClass;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AhamClass $ahamClass)
    {
        $this->ahamClass = $ahamClass;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ahamClass = $this->ahamClass;
        $teacher = $ahamClass->teacher;
        $user = $teacher->user;

        $tokens = $user->pushTokens()->where(['source' => 'aham_tutor','type'=> 'android'])->get();

        $title = 'Upcoming Class';
        $body = 'You have a class on '.$ahamClass->topic->name;
        $data = [];

        $data['type'] = 'class_notification';
        $data['title'] = $title;
        $data['body'] = $body;
        $data['class_id'] = $ahamClass->id;

        foreach($tokens as $token)
        {
            PushHelper::sendFCMMessageTutorApp($title, $body, $token->push_id, $data);
        }
    }
}
