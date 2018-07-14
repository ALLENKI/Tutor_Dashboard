<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Helpers\HipchatHelper;

use Aham\Models\SQL\AhamClass;


class MarkAsCompleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:mark_completed';

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
        $classes = AhamClass::where('status','get_feedback')->get();

        foreach($classes as $ahamClass)
        {
            foreach($ahamClass->enrollments as $enrollment)
            {
                $enrollment->feedback = 'needs_practice';
                $enrollment->save();
            }

            $ahamClass->status = 'got_feedback';
            $ahamClass->save();
        }

        $classes = AhamClass::where('status','got_feedback')->get();

        foreach($classes as $ahamClass)
        {
            $ahamClass->status = 'completed';
            $ahamClass->save();
        }
    }
}
