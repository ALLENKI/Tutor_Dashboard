<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Aham\Models\SQL\AhamClass;

class TestQueues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:test_queues';

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
        event(new \Aham\Events\LogMyName(AhamClass::find(7)));
    }
}
