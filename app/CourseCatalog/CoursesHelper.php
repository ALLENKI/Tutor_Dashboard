<?php

namespace Aham\CourseCatalog;

use Aham\CourseCatalog\CourseCatalogHelper;

class CoursesHelper extends CourseCatalogHelper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($properties)
    {
        return $this->parentGet('Course', $properties);
    }

    public function create($properties)
    {
        $this->parentCreate('Course', $properties);
    }

    /*
        update the category properties
    */
    public function update($matchArray, $updateValues)
    {
        $this->parentUpdate('Course', $matchArray, $updateValues);
    }

    /*
        delete category
    */
    public function delete($propertyArray)
    {
        $this->parentDelete('Course', $propertyArray);
    }

    public function deleteHasForTopic($sourceTopicProperties,$destinationTopicProperties)
    {
        $this->parentDeleteRelationship('Course', 'Topic', $sourceTopicProperties, $destinationTopicProperties, 'HAS');
    }

    public function deleteHasForCourse($sourceTopicProperties,$destinationTopicProperties)
    {
        $this->parentDeleteRelationship('Course', 'Course', $sourceTopicProperties, $destinationTopicProperties, 'HAS');
    }

    // addSubjectAsChild($categoryProperties, $subjectProperties)

    public function addHasRelationToTopic($courseProperties, $topicProperties)
    {
        $this->parentRelationship('Course', 'Topic', $courseProperties, $topicProperties, 'HAS');
    }

    public function addHasRelationToCourse($courseProperties, $topicProperties)
    {
        $this->parentRelationship('Course', 'Course', $courseProperties, $topicProperties, 'HAS');
    }

    public function addRequireRelationToTopic($courseProperties, $topicProperties)
    {
        $this->parentRelationship('Course', 'Topic', $courseProperties, $topicProperties, 'REQUIRES');
    }

    public function addRequireRelationToCourse($courseProperties, $course1Properties)
    {
        $this->parentRelationship('Course', 'Course', $courseProperties, $course1Properties, 'REQUIRES');
    }

    /**
     * TODO:: add has relationship course->topics
     */
}
