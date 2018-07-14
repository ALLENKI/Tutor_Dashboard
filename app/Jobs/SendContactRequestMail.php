<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Managers\SuperMail;

class SendContactRequestMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;
    public $email;
    public $select_option;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name, $email, $select_option, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->select_option = $select_option;
        $this->message = $message;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $name = $this->name;
        $email = $this->email;
        $select_option = $this->select_option;
        $comment = $this->message;

        // dd($this);

        SuperMail::mail('emails.contact_us',['name' => $name, 'email' => $email, 'select_option' => $select_option, 'comment' => $comment], $name.' has left a message on ahamlearning.com ',
                ['contactus@ahamlearning.com' => 'Aham ContactUs']);   //

    }
}
