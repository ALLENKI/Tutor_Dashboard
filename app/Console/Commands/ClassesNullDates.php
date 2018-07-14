<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Aham\Models\SQL\AhamClass;

class ClassesNullDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:classes_null_dates';

    /**
     * fix classestimings with null start_timings.
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
        $nullDates = AhamClass::whereNull('start_date')
                                ->whereNotIn('status',['initiated','cancelled'])
                                ->get();
       
        foreach($nullDates as $nulldate)
        {
            
            if($nulldate->timings->count()) {      
                var_dump('ClassId: '.$nulldate->id);

                var_dump($nulldate->timings->first()['date']->format('Y-m-d').' '.
                $nulldate->timings->first()['start_time']);

                $nulldate->start_date = $nulldate->timings->first()['date']->format('Y-m-d').' '.
                                        $nulldate->timings->first()['start_time'];

                $nulldate->save();
            }

        }
        
    }
}
