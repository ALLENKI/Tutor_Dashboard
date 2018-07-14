<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Models\SQL\GuestSeriesEpisode;

use Illuminate\Foundation\Bus\DispatchesJobs;



class SendRoboticsPromotionalEmail extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:send_robotics_promotional_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $episode = GuestSeriesEpisode::find(1);

        foreach($episode->enrollments as $enrollment)
        {
            $this->dispatch(new \Aham\Jobs\SendWorkshopConfirmMail($enrollment));
            // var_dump($enrollment);
        }

        // $this->dispatch(new \Aham\Jobs\SendWorkshopConfirmMail($enrollment));

        $episode = GuestSeriesEpisode::find(2);

        foreach($episode->enrollments as $enrollment)
        {
            $this->dispatch(new \Aham\Jobs\SendWorkshopConfirmMail($enrollment));
            // var_dump($enrollment);
        }

    }


}
