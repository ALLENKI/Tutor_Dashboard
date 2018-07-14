<?php

namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\CourseCatalog\SubjectHelper;
use Aham\CourseCatalog\CategoryHelper;

class SubjectRepository extends Repository
{
    public $subject;

    public function __construct()
    {
        parent::__construct(new \Illuminate\Container\Container(), new  \Illuminate\Support\Collection());
        $this->neoSubject = new SubjectHelper();
        $this->neoCategory = new CategoryHelper();
    }

    public function model()
    {
        return 'Aham\Models\SQL\Topic';
    }

    public function getSubject($id)
    {
        return $this->neoSubject->get(['id' => $id]);
    }

    public function createNewSubjectWithNeo4J($array)
    {
        $array['type'] = 'subject';

        if (!isset($array['hub_id'])) {
            $array['hub_id'] = '';
        } else {
            $array['hub_id'] = Location::where('slug',$array['hub_id'])->select('id')->get()->first()->id;
        }

        try {
            $this->neoCategory->get(['id' => $array['parent_id']]);
        } catch(\Aham\Exceptions\Neo4jNodeNotFound $e) {
            $category = Topic::find($array['parent_id']);
            $syncCategory = new CategoryRepository();
            $syncCategory->syncCategoryWithNeo4j($category);
        } finally {
            $subject = Topic::create($array);
            $this->neoSubject->create(
                [
                    'id' => $subject->parent_id
                ],
                [
                    'id' => $subject->id,
                    'name' => $subject->name
                ]
            );
        }

        if (count($this->getSubject($subject->id)) > 0) {
            return $subject;
        }
    }

    public function updateSubjectWithNeo4j($id, $updates)
    {
        $subject = Topic::with('children.children')->find($id);

        $previousCategory = $subject->parent_id;
        $subject->slug = null;
        $subject->name = $updates['name'];
        $subject->description = $updates['description'];
        $subject->parent_id = $updates['parent_id'];
        $subject->visibility = $updates['visibility'];
        $subject->save();

        $subject->children()->update(['visibility' => $updates['visibility']]);

        foreach ($subject->children as $item) {
            
         $item->children()->update(['visibility' => $updates['visibility']]);

        }


        // Update the subject node, and also update the relationship
        $this->neoSubject->update($subject, $previousCategory, $updates);

        $updatedValues = $this->getSubject($subject->id);

        if ($updatedValues['name'] == $updates['name']) {
            return $subject;
        }
        return false;
    }

    public function deleteSubjectWithNeo4j($id)
    {
        $subject = Topic::find($id);

        if ($subject->children->count() == 0) {
            $subject->delete();
            $this->neoSubject->delete(['id' => $id]);
        }

        try {
            if (count($this->getSubject($id)) > 0) {
                return false;
            }
        } catch (Exception $e) {
            \Log::info('No4j exception: ', $e->getMessage());
            return true;
        }
    }

    public function syncSubjectWithNeo4j($subject) 
    {
        try {
            $this->neoCategory->get(['id' => $subject->parent_id]);
        } catch(\Aham\Exceptions\Neo4jNodeNotFound $e) {
            $category = Topic::find($subject->parent_id);
            $syncCategory = new CategoryRepository();
            $syncCategory->syncCategoryWithNeo4j($category);
        } finally {
            $this->neoSubject->create(
                [
                    'id' => $subject->parent_id
                ],
                [
                    'id' => $subject->id,
                    'name' => $subject->name
                ]
            );
        }
    }
}
