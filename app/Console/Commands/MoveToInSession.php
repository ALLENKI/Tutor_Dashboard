<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Helpers\HipchatHelper;


class MoveToInSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:move_to_in_session';

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
        //     'message' => 'Job to move classes into session is about to start',
        //     'id' => 'Aham',
        //     'from' => env('APP_URL'),
        //     'notify' => false,
        //     'color' => 'yellow'
        // ]);

        \Aham\Managers\ClassStatusManager::moveClassesToInSession();
    }
}
