<?php

namespace Aham\CourseCatalog;

class TopicHelper extends CourseCatalogHelper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($properties)
    {
        return  $this->parentGet('Topic', $properties);
    }

    public function create($subCategoryProperties, $topicProperties, $requireProperties = [])
    {
        $this->parentCreate('Topic', $topicProperties);
        $this->addHasRelationToSubCategory($subCategoryProperties, $topicProperties);

        $topic = $this->get($topicProperties);

        if (count($requireProperties)) {
            $this->createRequired($topic, $requireProperties);
        }
    }

    public function createRequiredToTopic($topic, $requireProperties)
    {
        if (!is_null($topic) && !empty($requireProperties)) {
            foreach ($requireProperties as $requireProperty) {
                
                if ($this->checkShortestPath('Topic', 'Topic', ['id' => $topic['id']], ['id' => $requireProperty])) {
                    $this->addTopicAsPrerequisite($topic, ['id' => $requireProperty]);
                } else {
                    return 'not eligible';
                }
                
            }
        }
        
    }

    public function createRequiredToSubCategory($topic,$requireProperties)
    {

        if (!is_null($topic) && !empty($requireProperties)) {
            foreach ($requireProperties as $requireProperty) {


                if ($this->checkShortestPath('Topic', 'SubCategory', ['id' => $topic['id']], ['id' => $requireProperty])) {
                    $this->addSubCategoryAsPrerequisite($topic, ['id' => $requireProperty]);
                } else {
                    return 'not eligible';
                }


            }

        }

    }

    public function deleteRequiredForTopic($sourceTopicProperties,$destinationTopicProperties)
    {
        $this->parentDeleteRelationship('Topic', 'Topic', $sourceTopicProperties, $destinationTopicProperties, 'REQUIRES');
    }

    public function deleteRequiredForSubCategory($topicProperties,$subCategoryProperties)
    {
        $this->parentDeleteRelationship('Topic', 'SubCategory', $sourceTopicProperties, $destinationTopicProperties, 'REQUIRES');
    }

    public function update($topic, $previousSubCategory, $updateValues)
    {
        $matchArray = ['id' => $topic->id];

        $this->parentUpdate('Topic', $matchArray, array_only($updateValues, ['name']));

        $this->parentDeleteRelationship('SubCategory', 'Topic',['id' => $previousSubCategory], ['id' => $topic->id],'HAS');

        $this->addHasRelationToSubCategory(['id' => $topic->parent_id], ['id' => $topic->id]);
    }

    public function delete($propertyArray)
    {
        $this->parentDelete('Topic', $propertyArray);
    }

    // 1. Topic can have a relationship with another topic
    // 2. Topic can have a relationship with another sub-category
    // addTopicAsPrerequisite - REQUIRES
    // addSubCategoryAsPrerequisite - REQUIRES

    public function addTopicAsPrerequisite($sourceTopicProperties, $destinationTopicProperties)
    {
        $this->parentRelationship('Topic', 'Topic', $sourceTopicProperties, $destinationTopicProperties, 'REQUIRES');
    }

    public function addSubCategoryAsPrerequisite($topicProperties, $subCategoryProperties)
    {
        $this->parentRelationship('Topic', 'SubCategory', $topicProperties, $subCategoryProperties, 'REQUIRES');
    }

    public function addHasRelationToSubCategory($subCategoryProperties, $topicProperties)
    {
        $this->parentRelationship('SubCategory', 'Topic', $subCategoryProperties, $topicProperties, 'HAS');
    }
}
