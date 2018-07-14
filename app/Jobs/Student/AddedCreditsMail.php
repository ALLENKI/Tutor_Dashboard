<?php

namespace Aham\Jobs\Student;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Aham\Models\SQL\StudentCredits;
use Aham\Managers\SuperMail;
use Aham\Helpers\PushHelper;

class AddedCreditsMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $credits;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(StudentCredits $credits)
    {
        $this->credits = $credits;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $creditsModel = $this->credits;

        // dd($creditsModel->anyCoupon);

        $user = $creditsModel->student->user;

        SuperMail::mail(
                'emails_new.student.added_credits',
                ['user' => $user, 'creditsModel' => $creditsModel],
                'You have new credits in your account',
                    [$user->email => $user->name]
            );

        //Push notification

        $tokens = $user->pushTokens()->where(['source' => 'aham_learner', 'type' => 'android'])->get();

        $title = 'Added Credits';
        $body = 'You have new credits: ' . $creditsModel->credits;
        $data = [];

        $data['type'] = 'credits';
        $data['title'] = $title;
        $data['body'] = $body;

        foreach ($tokens as $token) {
            PushHelper::sendFCMMessageLearnerApp($title, $body, $token->push_id, $data);
        }

        $tokens = $user->pushTokens()->where(['source' => 'aham_learner', 'type' => 'ios'])->get();

        foreach ($tokens as $token) {
            PushHelper::sendAppleMessageLearnerApp($title, $body, $token->push_id, $data);
        }
    }
}
