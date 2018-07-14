<?php

namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Course;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\CourseCatalog\TopicHelper;

class ClassRepository extends Repository
{
    public function model()
    {
        return 'Aham\Models\SQL\AhamClass';
    }

    public function allRepeatClasses($id)
    {
        return AhamClass::where('source_class_id',$id)->get();
    }

    public function updateClass($classId, $data, $user)
    {
        $ahamClass = $this->find($classId);

        $originalTopic = $ahamClass->topic;
        
        $ahamClass->fill(array_only($data, ['name','location_id', 'topic_id', 'minimum_enrollment', 'maximum_enrollment', 'charge_multiply','type','unit_duration']));

        $ahamClass->creator_id = $user->id;

        if (isset($data['free_class']) && $data['free_class']) {
            $ahamClass->free = true;
        }

        $ahamClass->no_tutor_comp = false;

        if (isset($data['pay_tutor']) && !$data['pay_tutor']) {
            $ahamClass->no_tutor_comp = true;
        }

        if (isset($data['auto_cancel']) && !$data['auto_cancel']) {
            $ahamClass->auto_cancel = false;
        }

        if (isset($data['auto_cancel']) && $data['auto_cancel']) {
            $ahamClass->auto_cancel = true;
        }

        $ahamClass->save();
        $ahamClass = $this->find($classId);


        if($originalTopic->id != $ahamClass->topic->id)
        {

            $topic = Topic::find($data['topic_id']);

            $this->transferTopic($ahamClass, $originalTopic, $topic);

        }


        return $ahamClass;
    }

    public function classCourseDetail($id)
    {
        $classes = AhamClass::where('group_class_id',$id)->get();
        
        return $classes;
    }

    public function showClassWithCourse($id)
    {
      return AhamClass::find($id);
    }
    
    public function transferTopic($ahamClass, $originalTopic, $topic)
    {

        $ahamClass->topic_id = $topic->id;
        $ahamClass->topic_name = $topic->name; 
        $ahamClass->name=$topic->name;
        $ahamClass->topic_description = $topic->description; 
        $ahamClass->save();

        $topicUnits = $topic->units;

        // dd($topicUnits->shift());

        if($originalTopic->units->count() == $topic->units->count())
        {

            foreach($ahamClass->classUnits as $classUnit)
            {
                $topicUnit = $topicUnits->shift();

                // var_dump($topicUnit->id);

                $classUnitData = [];
                $classUnitData['name'] = $topicUnit->name;
                $classUnitData['description'] = $topicUnit->description;
                $classUnitData['order'] = $topicUnit->order;
                $classUnitData['topic_id'] = $topic->id;
                $classUnitData['original_unit_id'] = $topicUnit->id;
                $classUnit->fill($classUnitData);
                $classUnit->save();

                $timing = ClassTiming::where([
                    'class_id' => $ahamClass->id,
                    'class_unit_id' => $classUnit->id,
                    'of_id' => $ahamClass->id,
                    'of_type' => get_class($ahamClass)
                ])->first();

                $timing->unit_id = $topicUnit->id;
                $timing->save();

            }

        }
        else
        {

            $ahamClass->classTimings()->delete();
            $ahamClass->classUnits()->delete();

            foreach($topic->units as $unit)
            {
                ClassUnit::create([
                    'name' => $unit->name,
                    'description' => $unit->description,
                    'order' => $unit->order,
                    'topic_id' => $topic->id,
                    'class_id' => $ahamClass->id,
                    'original_unit_id' => $unit->id
                ]);
            }
        }

    }

    public function createClass($data, $user)
    {

        $ahamClass = $this->create(array_only($data, [
            'name',
            'location_id', 
            'topic_id', 
            'minimum_enrollment', 
            'maximum_enrollment', 
            'charge_multiply',
            'type',
            'unit_duration',
            'of_id',
            'of_type',
            'group_class_id'
        ]));

        $ahamClass->creator_id = $user->id;

        $ahamClass->status = 'initiated';

        if (isset($data['free_class']) && $data['free_class']) {
            $ahamClass->free = true;
        }

        if (isset($data['pay_tutor']) && !$data['pay_tutor']) {
            $ahamClass->no_tutor_comp = true;
        }

        if (isset($data['auto_cancel']) && !$data['auto_cancel']) {
            $ahamClass->auto_cancel = false;
        }

        if (isset($data['auto_cancel']) && $data['auto_cancel']) {
            $ahamClass->auto_cancel = true;
        }

        $ahamClass->save();

        $topic = Topic::find($data['topic_id']);

        $ahamClass->topic_name = $topic->name; 
        $ahamClass->topic_description = $topic->description; 
        $ahamClass->save();

        foreach($topic->units as $unit)
        {
            ClassUnit::create([

                'name' => $unit->name,
                'description' => $unit->description,
                'order' => $unit->order,
                'topic_id' => $topic->id,
                'class_id' => $ahamClass->id,
                'original_unit_id' => $unit->id

            ]);
        }

        return $ahamClass;
    }

    public function createClassWithTopic($data, $user)
    {
       $topic_id = $data['topic_id'];

       $data['of_id'] = $topic_id;
       $data['of_type'] = Topic::class;

       $ahamClass =  $this->createClass($data, $user);

        return $ahamClass;
    }

    public function createClassWithCourse($data,$user)
    {
        $data['of_id'] = $data['course_id'];
        $data['of_type'] = Course::class;
    
        $ahamClass = $this->create(array_only($data, [
            'name',
            'location_id',
            'minimum_enrollment', 
            'maximum_enrollment', 
            'charge_multiply',
            'type',
            'of_id',
            'of_type',
            'unit_duration1',
        ]));


        $ahamClass->creator_id = $user->id;

        $ahamClass->status = 'initiated';

        if (isset($data['free_class']) && $data['free_class']) {
            $ahamClass->free = true;
        }

        if (isset($data['pay_tutor']) && !$data['pay_tutor']) {
            $ahamClass->no_tutor_comp = true;
        }

        if (isset($data['auto_cancel']) && !$data['auto_cancel']) {
            $ahamClass->auto_cancel = false;
        }

        if (isset($data['auto_cancel']) && $data['auto_cancel']) {
            $ahamClass->auto_cancel = true;
        }

        $ahamClass->save();

        $course = Course::find($data['course_id']);

        $ahamClass->topic_name = $course->name; 
        $ahamClass->save();

        $topicClass['location_id'] = $data['location_id'];
        $topicClass['minimum_enrollment'] = $data['minimum_enrollment'];
        $topicClass['maximum_enrollment'] = $data['maximum_enrollment'];
        $topicClass['charge_multiply'] = $data['charge_multiply'];
        $topicClass['type'] = 'single_group_class';
        $topicClass['unit_duration'] = $data['unit_duration'];
        $topicClass['group_class_id'] = $ahamClass->id;

        foreach($course->topics as $topic) 
        {
            $topicClass['topic_id'] = $topic->id;
            $topicClass['of_id'] = $topic->id;
            $topicClass['of_type'] = Topic::class;
            // create topics
            $class = $this->createClass($topicClass,$user);
        }
    
        return $ahamClass;
    }

}
