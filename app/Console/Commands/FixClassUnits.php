<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Helpers\HipchatHelper;

use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\AhamClass;

class FixClassUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:fix_units';

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
        $classes = AhamClass::all();

        foreach($classes as $ahamClass)
        {
            $topic = $ahamClass->topic;

            $ahamClass->topic_name = $ahamClass->topic->name; 
            $ahamClass->topic_description = $topic->description; 
            $ahamClass->save();

            foreach($topic->units()->withTrashed()->get() as $unit)
            {
                $classUnit = ClassUnit::firstOrCreate([
                    'class_id' => $ahamClass->id,
                    'original_unit_id' => $unit->id
                ]);

                $classUnit->fill([
                    'name' => $unit->name,
                    'description' => $unit->description,
                    'order' => $unit->order,
                    'topic_id' => $topic->id
                ]);

                $classUnit->save();
            }

        }

    }
}
