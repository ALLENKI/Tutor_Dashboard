<?php

namespace Aham\Listeners\Student;

use Aham\Events\Student\AddedCredits as AddedCreditsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Models\SQL\StudentCredits;

class AddedCredits implements ShouldQueue
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
     * @param  AddedCredits  $event
     * @return void
     */
    public function handle(AddedCreditsEvent $event)
    {
        $creditsModel = $event->credits;

        dispatch(new \Aham\Jobs\Student\AddedCreditsMail($creditsModel));
    }
}
