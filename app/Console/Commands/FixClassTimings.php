<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Helpers\HipchatHelper;

use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;

class FixClassTimings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:fix_timings';

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
        $timings = ClassTiming::all();

        foreach($timings as $timing)
        {
            $timing->of_id = $timing->class_id;
            $timing->of_type = get_class($timing->ahamClass);
            $timing->save();

            $classUnit = ClassUnit::where([
                'original_unit_id' => $timing->unit_id,
                'class_id' => $timing->class_id
            ])->first();

            if(!is_null($classUnit))
            {
                $timing->class_unit_id = $classUnit->id;
                
            }

            if($timing->ahamClass->teacher)
            {
                $timing->teacher_id = $timing->ahamClass->teacher->id;
            }

            $timing->save();
        }

    }
}
