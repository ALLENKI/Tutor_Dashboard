<?php

namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\CourseCatalog\SubCategoryHelper;
use Aham\CourseCatalog\SubjectHelper;

class SubCategoryRepository extends Repository
{
    public $neoSubCategory;
    private $topic;

    public function __construct()
    {
        parent::__construct(new \Illuminate\Container\Container(), new  \Illuminate\Support\Collection());
        $this->neoSubCategory = new SubCategoryHelper();
        $this->neoSubject = new SubjectHelper();
        $this->topic = resolve('Aham\Repositories\TopicRepository');
    }

    public function model()
    {
        return 'Aham\Models\SQL\Topic';
    }

    public function getSubCategory($id)
    {
        return  $this->neoSubCategory->get(['id' => $id]);
    }

    public function createSubCategoryWithNeo4J($data)
    {
        // Create Sub Category in Database
        $data['type'] = 'sub-category';
        
        if (!isset($data['hub'])) {
            $data['hub_id'] = null; 
        } else {
            $data['hub_id'] = Location::where('slug',$data['hub'])->select('id')->get()->first()->id;
        }

        try {
            $this->neoSubject->get(['id' => $data['parent_id']]);
        } catch(\Aham\Exceptions\Neo4jNodeNotFound $e) {
            $subject = Topic::find($data['parent_id']);
            $syncSubject = new SubjectRepository();
            $syncSubject->syncSubjectWithNeo4j($subject);
        } finally {
            $subCategory = Topic::updateOrCreate(array_only($data, ['name', 'hub_id', 'description', 'parent_id', 'type']));
            $this->neoSubCategory->create(
                [
                    'id' => $subCategory->parent_id
                ],
                [
                    'id' => $subCategory->id,
                    'name' => $subCategory->name
                ]
            );
        }

        if (isset($data['topics'])) {
            foreach ($data['topics'] as $topic) {
                $topic_data = array_only($topic, ['name', 'description', 'units',]);
                $topic_data['parent_id'] = $subCategory->id;
                $this->topic->createNewTopicWithNeo4J($topic_data);
            }
        }

        if (count($this->getSubCategory($subCategory->id)) > 0) {
            return $subCategory;
        }
    }

    public function createNewSubCategoryWithNeo4J($array)
    {
        $array['type'] = 'SubCategory';
        $prerequisites = $array['prerequisites'];
        unset($array['prerequisites']);

        foreach ($prerequisites as $prerequasite) {
            if ($prerequasite['type'] = 'topic') {
                $subCategory_id = $prerequasite['id'];
                $subCategory = Topic::create($array);
                $this->neoTopic->create(
                    ['id' => $subCategory->parent_id],
                    ['id' => $subCategory->id,
                    'name' => $subCategory->name],
                     ['topic' => $subCategory_id]
                );
            }
        }

        if (count($this->getSubCategory($subCategory->id)) > 0) {
            return true;
        }

        return false;
    }

    public function updateSubCategoryWithNeo4j($id, $updates)
    {
        $subCategory = Topic::find($id);

        if (!isset($updates['hub'])) {
            $updates['hub_id'] = null; 
        } else {
            $updates['hub_id'] = Location::where('slug',$updates['hub'])->select('id')->get()->first()->id;
        }

        $previousSubject = $subCategory->parent_id;
        $subCategory->slug = null;
        $subCategory->name = $updates['name'];
        $subCategory->parent_id = $updates['parent_id'];
        $subCategory->description = $updates['description'];
        $subCategory->hub_id = $updates['hub_id'];

        $subCategory->save();

        if (isset($updates['topics'])) {
            foreach ($updates['topics'] as $topic) {
                // var_dump($topic);
                $topic_data = array_only($topic, ['name', 'description', 'units',]);
                $topic_data['parent_id'] = $subCategory->id;
                // var_dump($topic_data);die;
                $this->topic->createNewTopicWithNeo4J($topic_data);
            }
        }

        array_splice($updates,3);
        
        $this->neoSubCategory->update($subCategory, $previousSubject, $updates);
        $updatedValues = $this->getSubCategory($subCategory->id);

        if ($updatedValues['name'] == $updates['name']) {
            return true;
        }

        return false;
    }

    public function deleteSubCategoryWithNeo4j($id)
    {
        $subCategory = Topic::find($id);

        if ($subCategory != null) {
            if ($subCategory->children->count() == 0) {
                $subCategory->delete();
                $this->neoSubCategory->delete(['id' => $id]);
            }
        }

        try {
            $array = $this->getSubCategory($subCategory->id);
            if (count($array) > 0) {
                return false;
            }
        } catch (Exception $e) {
            \Log::info('No4j exception: ', $e->getMessage());
            return true;
        }
    }

    public function syncSubCategoryWithNeo4j($subCategory) 
    {
        try {
            $this->neoSubject->get(['id' => $subCategory->parent_id]);
        } catch(\Aham\Exceptions\Neo4jNodeNotFound $e) {
            $subject = Topic::find($subCategory->parent_id);
            $syncSubject = new SubjectRepository();
            $syncSubject->syncSubjectWithNeo4j($subject);
        } finally {
            $this->neoSubCategory->create(
                [
                    'id' => $subCategory->parent_id
                ],
                [
                    'id' => $subCategory->id,
                    'name' => $subCategory->name
                ]
            );
        }
    }
}
