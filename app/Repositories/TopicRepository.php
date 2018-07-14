<?php

namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\SubCategory;
use Aham\Models\SQL\Subject;
use Aham\Models\SQL\Category;
use Aham\Models\SQL\TopicPrerequisite;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Course;
use Aham\CourseCatalog\TopicHelper;
use Aham\CourseCatalog\SubCategoryHelper;
use Aham\CourseCatalog\SubjectHelper;
use Aham\CourseCatalog\CategoryHelper;
use Aham\Repositories\CourseRepository;
use Aham\Repositories\CategoryRepository;
use Aham\Repositories\SubCategoryRepository;
use Aham\Repositories\SubjectRepository;

class TopicRepository extends Repository
{
    public $topic;

    public function __construct()
    {
        parent::__construct(new \Illuminate\Container\Container(), new  \Illuminate\Support\Collection());
        $this->neoTopic = new TopicHelper();
        $this->neoSubCategory = new SubCategoryHelper();
        $this->neoSubject = new SubjectHelper();
        $this->neoCategory = new CategoryHelper();
    }

    public function model()
    {
        return 'Aham\Models\SQL\Topic';
    }

    public function getTopic($id)
    {
        return $this->neoTopic->get(['id' => $id]);
    }

    public function createNewTopicWithNeo4J($data)
    {
        $data['type'] = 'Topic';
        if (!isset($data['hub'])) {
            $data['hub_id'] = null; 
        } else {
            $data['hub_id'] = Location::where('slug',$data['hub'])->select('id')->get()->first()->id;
        }

        try {
            $this->neoSubCategory->get(['id' => $data['parent_id']]);            
        } catch(\Aham\Exceptions\Neo4jNodeNotFound $e) {
            $subCategory = Topic::find($data['parent_id']);
            $syncSubCategory = new SubCategoryRepository();
            $syncSubCategory->syncSubCategoryWithNeo4j($subCategory);
        } finally {
            $topic = Topic::create(array_only($data, ['name', 'description', 'parent_id', 'type','status','approve','hub_id','visibility']));
            $this->neoTopic->create(
                [
                    'id' => $topic->parent_id
                ],
                [
                    'id' => $topic->id,
                    'name' => $topic->name
                ]
            );
        }
        
        if (isset($data['units'])) {
            foreach ($data['units'] as $index => $unit) {
                $newUnit = Unit::create(array_only($unit, ['name', 'description']));
                $newUnit->order = $index + 1;
                $topic->units()->save($newUnit);
            }
        }
        
        if (count($this->getTopic($topic->id)) > 0) {
            return $topic;
        }
    }

    public function updateTopicWithNeo4j($id, $updates)
    {
        $topic = Topic::find($id);
        $previousSubCategory = $topic->parent_id;
        $topic->description = $updates['description'];
        $topic->parent_id = $updates['parent_id'];
        $topic->status = $updates['status'];
        $topic->slug = null;
        $topic->name = $updates['name'];
        $topic->approve = $updates['approve'];
        $topic->visibility = $updates['visibility'];
        $topic->save();
        $topic->units()->delete();

        if (isset($updates['units'])) {
            foreach ($updates['units'] as $index => $unit) {
                $newUnit = Unit::create(array_only($unit, ['name', 'description']));
                $newUnit->order = $index + 1;
                $topic->units()->save($newUnit);
            }
        }

        $this->neoTopic->update($topic, $previousSubCategory, $updates);

        $updatedValues = $this->getTopic($topic->id);

        if ($updatedValues['name'] == $updates['name']) {
            return $topic;
        }

        return false;
    }

    public function deleteTopicWithNeo4j($id)
    {
        $topic = Topic::find($id);

        if ($topic->children->count() == 0) {
            $topic->delete();
            $this->neoTopic->delete(['id' => $id]);
        }

        try {
            if (count($this->getTopic($id)) > 0) {
                return false;
            }
        } catch (Exception $e) {
            \Log::info('No4j exception: ', $e->getMessage());
            return true;
        }
    }

    public function addPrerequisite($topicId,$requirerId)
    {
    }

    public function addingToCourse($courseId, $topicId)
    {
       $topic =  Topic::find($topicId);

       $course = Course::find($courseId);

       $courseRepo = new CourseRepository();

       $payload['course'] = [
                'name' => $course->name,
                'type' => 'collection_of_topics',
       ];

       $payload['topics'] = [
           'id' => $topic->id,
       ];

       $courseRepo->updateCourseWithNeo4j($course->id,$payload);
    }

}
