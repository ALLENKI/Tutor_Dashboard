<?php

namespace Aham\CourseCatalog;

use Aham\CourseCatalog\CourseCatalogHelper;

class CategoryHelper extends CourseCatalogHelper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($properties)
    {
        return $this->parentGet('Category', $properties);
    }

    public function create($properties)
    {
        $this->parentCreate('Category', $properties);
    }

    /*
        update the category properties
    */
    public function update($matchArray, $updateValues)
    {
        $this->parentUpdate('Category', $matchArray, $updateValues);
    }

    /*
        delete category
    */
    public function delete($propertyArray)
    {
        $this->parentDelete('Category', $propertyArray);
    }

    // addSubjectAsChild($categoryProperties, $subjectProperties)

    public function addHasRelationToSubject($categoryProperties, $subjectProperties)
    {
        $this->parentRelationship('Category', 'Subject', $categoryProperties, $subjectProperties, 'HAS');
    }
}
