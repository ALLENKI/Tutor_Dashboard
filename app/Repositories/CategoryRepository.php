<?php

namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\CourseCatalog\CategoryHelper;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository extends Repository
{
    public $category;

    public function __construct()
    {
        parent::__construct(new \Illuminate\Container\Container(), new  \Illuminate\Support\Collection());
        $this->neoCategory = new CategoryHelper();
    }

    public function model()
    {
        return 'Aham\Models\SQL\Topic';
    }

    public function getCategory($id)
    {
        return  $this->neoCategory->get(['id' => $id]);
    }

    public function createNewCategoryWithNeo4J($array)
    {
        if ($array['hub_id'] == '') {
            $array['hub_id'] = null; 
        } else {
            $array['hub_id'] = Location::where('slug',$array['hub_id'])->select('id')->get()->first()->id;
        }

        $array['type'] = 'category';
        $category = Topic::create($array);

        // Create a node for this category
        $this->neoCategory->create([
            'id' => $category->id,
            'name' => $category->name
        ]);

        if (count($this->getCategory($category->id)) > 0) {
            return $category;
        }

        return false;
    }

    public function updateCategoryWithNeo4j($id, $updates)
    {
        $category = Topic::find($id);
        $category->slug = null;
        $category->fill($updates);
        $category->save();

        // Some nodes are not created in neo4j

        try {
            $updatedValues = $this->getCategory($category->id);

            // Update the node properties
            $this->neoCategory->update(['id' => $id], $updates);
        } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
            $this->neoCategory->create([
                'id' => $category->id,
                'name' => $category->name
            ]);
        }

        return $category;
    }

    public function deleteCategoryWithNeo4j($id)
    {
        $category = Topic::find($id);

        if ($category->children->count() == 0) {
            $category->delete();
            // Delete the node and relationships
            $this->neoCategory->delete(['id' => $id]);
        }

        try {
            if (count($this->getCategory($category->id)) > 0) {
                return false;
            }
        } catch (Exception $e) {
            \Log::info('Neo4j exception: ', $e->getMessage());
            return true;
        }
    }

    public function syncCategoryWithNeo4j($category) {
        $this->neoCategory->create([
            'id' => $category->id,
            'name' => $category->name
        ]);
    }
}
