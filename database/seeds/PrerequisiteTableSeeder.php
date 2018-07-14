<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\TopicPrerequisite;

use Aham\Helpers\PrerequisiteHelper;

class PrerequisiteTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('topic_prerequisites')->truncate();

        $topics = Topic::topic()->get();

        // Generate pre-requisites

        // A topic shouldn't have circular dependency

        // If A has B as prerequisite, B can't have A as prerequisite

        foreach($topics as $topic)
        {
            for ($i=0; $i < 3 ; $i++) { 

                $prerequisite = Topic::orderBy(DB::raw('RAND()'))->first();

                $eligible = PrerequisiteHelper::isEligible($topic, $prerequisite);
                
                if($eligible)
                {
                    TopicPrerequisite::create([
                        'topic_id' => $prerequisite->id,
                        'requirer_id' => $topic->id
                    ]);
                }


            }

        }

    }
}
