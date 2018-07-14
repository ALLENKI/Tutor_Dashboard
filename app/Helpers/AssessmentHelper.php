<?php

namespace Aham\Helpers;

use Carbon;
use DB;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\StudentAssessment;
use Aham\Models\SQL\TeacherCertification;

use Aham\Helpers\TreeExplorer;
use Aham\Helpers\TopicLookupHelper;

class AssessmentHelper {

    // A function which takes an array and iterates over it
    public static function getFullTree($topic_id)
    {
        $treeExplorer = new TreeExplorer();

        $fullTree = $treeExplorer->getFullTree([$topic_id]);

        return $treeExplorer->explored;
    }

    public static function getPrerequisitesAndChildren($topic_id)
    {
        $topic = Topic::find($topic_id);

        $items = [];

        // Topic has children - Add them

        foreach($topic->children as $child)
        {
            $items[] = intval($child->id);
        }
        
        // Topic has prerequisites - Add them

        foreach($topic->prerequisites as $prerequisite)
        {
            $items[] = intval($prerequisite->id);
        }

        return $items;
    }

	public static function addStudentAssessment($id, $topic_id)
	{
        DB::beginTransaction();

        $fullTree = static::getFullTree($topic_id);

        foreach($fullTree as $topic)
        {
            StudentAssessment::firstOrCreate([
                'topic_id' => $topic,
                'student_id' => $id
            ]);
        }

        DB::commit();

        return true;
	}

	public static function addTeacherCertification($id, $topic_id)
	{
        DB::beginTransaction();

        $topic = Topic::find($topic_id);

        $certification = TeacherCertification::firstOrCreate([
            'topic_id' => $topic->id,
            'teacher_id' => $id
        ]);

        // event(new \Aham\Events\Teacher\Certified($certification));

        $allTopics = TopicLookupHelper::getTopicChildren($topic);

        foreach($allTopics as $topic)
        {
            TeacherCertification::firstOrCreate([
                'topic_id' => $topic,
                'teacher_id' => $id
            ]);
        }

        DB::commit();

        return true;
	}

}
