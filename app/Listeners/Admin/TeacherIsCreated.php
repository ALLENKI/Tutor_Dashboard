<?php

namespace Aham\Listeners\Admin;

use Aham\Events\Teacher\Created;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Helpers\HipchatHelper;

class TeacherIsCreated implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Created  $event
     * @return void
     */
    public function handle(Created $event)
    {
        $teacher = $event->teacher;

        $hipchat = new HipchatHelper();

        $message = view('notifications.teacher_created',compact('teacher'))->render();

        $hipchat->sendMessage('Aham',
        [
            'message' => $message,
            'id' => 'Aham',
            'from' => env('APP_URL'),
            'notify' => true,
            'color' => 'green'
        ]); 
    }
}
