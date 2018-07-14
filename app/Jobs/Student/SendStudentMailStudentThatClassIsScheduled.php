<?php

namespace Aham\Jobs\student;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Managers\SuperMail;
use Aham\Helpers\PushHelper;

class SendStudentMailStudentThatClassIsScheduled implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $enrollment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(StudentEnrollment $enrollment)
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
        $user = $enrollment->student->user;
        $class = $enrollment->ahamClass;

        SuperMail::mail(
            'emails_new.student.class_is_scheduled',
            ['user' => $user, 'class' => $class],
            $class->topic->name . ' is scheduled',
                [$user->email => $user->name]
        );

        //Push notification

        $tokens = $user->pushTokens()->where(['source' => 'aham_learner', 'type' => 'android'])->get();

        $title = 'Scheduled';
        $body = 'Class is scheduled ' . $class->topic->name;
        $data = [];

        $data['type'] = 'class_details';
        $data['title'] = $title;
        $data['body'] = $body;
        $data['class_id'] = $class->id;

        foreach ($tokens as $token) {
            PushHelper::sendFCMMessageLearnerApp($title, $body, $token->push_id, $data);
        }

        $tokens = $user->pushTokens()->where(['source' => 'aham_learner', 'type' => 'ios'])->get();

        foreach ($tokens as $token) {
            PushHelper::sendAppleMessageLearnerApp($title, $body, $token->push_id, $data);
        }
    }
}
