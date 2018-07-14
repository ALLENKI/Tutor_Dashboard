<?php

namespace Aham\Listeners\Student;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribedToGoal implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  SubscribedToGoal  $event
     * @return void
     */
    public function handle(SubscribedToGoalEvent $event)
    {
    }
}
