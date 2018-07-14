<?php

namespace Aham\Jobs\Teacher;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\AhamClass;

use Aham\Managers\SuperMail;

class SendAdminCancelledClassMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $class;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AhamClass $class)
    {
        $this->class = $class;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $class = $this->class;

        if($class->teacher)
        {
                
            $user = $class->teacher->user;

            SuperMail::mail('emails_new.teacher.system_cancelled_class',['user' => $user,'class'=> $class],'Dear Tutor, '.$class->topic->name.' Class is Cancelled',
                    [$user->email => $user->name]);
        }

    }
}
