<?php

namespace Aham\Listeners\Student;

use Aham\Events\Student\RefundedCredits as RefundedCreditsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Models\SQL\StudentCredits;

class RefundedCredits implements ShouldQueue
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
     * @param  RefundedCredits  $event
     * @return void
     */
    public function handle(RefundedCreditsEvent $event)
    {
        $creditsModel = $event->credits;

        \Log::info('RefundedCredits - Listener');

        dispatch(new \Aham\Jobs\Student\RefundedCreditsMail($creditsModel));
    }
}
