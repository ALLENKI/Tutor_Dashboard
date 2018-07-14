<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Managers\SuperMail;
use Aham\Helpers\PushHelper;

class SendStudentEnrolledToClassMail implements ShouldQueue
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
            'emails_new.student.enrolled_to_class',
            ['user' => $user, 'class' => $class],
            'You have enrolled to ' . $class->topic->name,
                [$user->email => $user->name]
        );

        //Push notification

        $tokens = $user->pushTokens()->where(['source' => 'aham_learner', 'type' => 'android'])->get();

        $title = 'Enrolled';
        $body = 'You have enrolled to the class ' . $class->topic->name;
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
