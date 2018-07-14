<?php

namespace Aham\CourseCatalog;

class SubCategoryHelper extends CourseCatalogHelper
{
    public $neoHelper;

    public function __construct()
    {
        parent::__construct();
    }

    public function get($subCategoryProperties)
    {
        return  $this->parentGet('SubCategory', $subCategoryProperties);
    }

    public function create($subjectProperties, $subCategoryProperties)
    {
        $this->parentCreate('SubCategory', $subCategoryProperties);
        $this->addHasRelationToSubject($subjectProperties, $subCategoryProperties);

        $topic = $this->get($subCategoryProperties);

        // $this->createRequired($topic, $requireProperties);
    }

    public function createRequired($topic, $requireProperties)
    {
        if (array_key_exists($requireProperties, 'subCategory')) {
            if (!is_null($topic) && !empty($requireProperties)) {
                foreach ($requireProperties as $requireProperty) {
                    if ($this->checkShortestPath('SubCategory', 'SubCategory', ['id' => $topic['id']], ['id' => $requireProperty])) {
                        $this->addSubCategoryAsPrerequisite($topic, ['id' => $requireProperty]);
                    }
                }
            }
        }
    }

    public function update($subCategory, $previousSubject, $updateValues)
    {
        $matchArray = ['id' => $subCategory->id];

        $this->parentUpdate('SubCategory', $matchArray, $updateValues);

        $this->parentDeleteRelationship('Subject','SubCategory',['id' => $previousSubject], ['id' => $subCategory->id], 'HAS');

        $this->addHasRelationToSubject(['id' => $subCategory->parent_id], ['id' => $subCategory->id]);
    }

    public function delete($propertyArray)
    {
        $this->parentDelete('SubCategory', $propertyArray);
    }

    public function addHasRelationToTopic($subCategoryProperties, $topicProperties)
    {
        $this->parentRelationship('SubCategory', 'Topic', $subCategoryProperties, $topicProperties, 'HAS');
    }

    public function addHasRelationToSubject($subjectProperties, $subCategoryProperties)
    {
        $this->parentRelationship('Subject', 'SubCategory', $subjectProperties, $subCategoryProperties, 'HAS');
    }

    public function addSubCategoryAsPrerequisite($subCategoryProperties, $subCategory1Properties)
    {
        $this->parentRelationship('SubCategory', 'SubCategory', $subCategoryProperties, $subCategory1Properties, 'REQUIRES');
    }
}
