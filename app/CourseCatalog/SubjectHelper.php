<?php

namespace Aham\CourseCatalog;

class SubjectHelper extends CourseCatalogHelper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($properties)
    {
        return $this->parentGet('Subject', $properties);
    }

    /*
        create subject
    */
    public function create($categoryProperties, $subjectProperties)
    {
        $this->parentCreate('Subject', $subjectProperties);
        $this->addHasRelationToCategory($categoryProperties, $subjectProperties);
    }

    /*
        upate the subject properties
    */
    public function update($subject, $previousCategory, $updateValues)
    {
        $matchArray = ['id' => $subject->id];

        $this->parentUpdate('Subject', $matchArray, $updateValues);

        $this->parentDeleteRelationship('Category', 'Subject', ['id' => $previousCategory],['id' => $subject->id], 'HAS');

        $this->addHasRelationToCategory(['id' => $subject->parent_id], ['id' => $subject->id]);
    }

    public function delete($properties)
    {
        $this->parentDelete('Subject', $properties);
    }

    public function addHasRelationToSubCategory($subjectProperties, $subCategoryProperties)
    {
        $this->parentRelationship('Subject', 'SubCategory', $subjectProperties, $subCategoryProperties, 'HAS');
    }

    public function addHasRelationToCategory($categoryProperties, $subjectProperties)
    {
        $this->parentRelationship('Category', 'Subject', $categoryProperties, $subjectProperties, 'HAS');
    }
}
