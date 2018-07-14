<?php

namespace Aham\Listeners\Teacher;

use Aham\Events\Teacher\Activated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeacherIsActivate implements ShouldQueue
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
     * @param  Activated  $event
     * @return void
     */
    public function handle(Activated $event)
    {
        $teacher = $event->teacher;

        dispatch(new \Aham\Jobs\SendTeacherActivatedMail($teacher));

    }
}
