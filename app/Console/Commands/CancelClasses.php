<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Helpers\HipchatHelper;


class CancelClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:cancel_classes';

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
        $hipchat = new HipchatHelper();

        // $hipchat->sendMessage('Aham',
        // [
        //     'message' => 'Class cancellation job is about to start',
        //     'id' => 'Aham',
        //     'from' => env('APP_URL'),
        //     'notify' => false,
        //     'color' => 'yellow'
        // ]);


        \Aham\Managers\ClassStatusManager::cancelClasses();
    }
}
