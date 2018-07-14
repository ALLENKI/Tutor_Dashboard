<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;


class FixClasseNullNameAndMorphToTopic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:fix_null_name_ofId_ofType';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fixes null classes names, fix for null of_id,of_type assumed single_class';

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
        $nullClassNames = AhamClass::whereNull('name')->get();

        echo 'fixing for null names \n';
        foreach($nullClassNames as $nullClassName) {

            $nullClassName->name = $nullClassName->topic_name;
            $nullClassName->save();

            var_dump ('classId: '.$nullClassName->id);
        }

        $nullMorphs =  AhamClass::whereNull('of_id')->get();

        echo 'fixing null Morph';
        foreach($nullMorphs as $nullMorph) {

            $nullMorph->of_id = $nullMorph->topic_id;
            $nullMorph->of_type = Topic::class;

            $nullMorph->save();

            var_dump ('classId: '.$nullClassName->id);
        }

    }

}
