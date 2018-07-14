<?php

namespace Aham\CourseCatalog;

class CourseCatalogHelper
{
    public $neoHelper;

    public function __construct()
    {
        $this->neoHelper = new NeoHelper();
    }

    /*
        Note:-
        all category,subject,subCategory,topic need to be on same level in a graph
        1. Function to create a Category -> category name 'science'
        2. Function to create a Subject -> subject name, "physics"
        3. Function to create a Sub Category -> sub category name, subject "LINEAR MOTION (NON CALCULUS)"
        4. Function to create a Topic -> topic name, sub category "One Dimensional Motion (Level 1 & Level 2)"
        5. units

        TODO:- dependency checking,avoiding circular dependencies while creating

        Eg:- subject:- mathematics,subcategory:- problem solving,units:- unit_name.
        Eg:- subject:- Subject: Math - Exam Preparation & Review,Sub Category: 10th Grade - ICSE,units:- units_name
    */

    public function parentGet($nodeLabel, $properties)
    {
        return $this->neoHelper->getNode($nodeLabel, $properties);
    }

    public function parentCreate($nodeLabel, $properties)
    {
        try {
            $result = $this->parentGet($nodeLabel, $properties);
            return $result;
        } catch (\Aham\Exceptions\Neo4jNodeNotFound $e) {
            return $this->neoHelper->createNode($nodeLabel, $properties);
        }
    }

    public function parentUpdate($nodeLabel, $matchArray, $updateValues)
    {
        // parse propertyKey,value
        $propertyKey = $this->neoHelper->getKey($matchArray);
        $propertyValue = $this->neoHelper->getValue($matchArray);
        $this->neoHelper->update($nodeLabel, $propertyKey, $propertyValue, $updateValues);
    }

    public function checkShortestPath($topicNode, $requireNode, $topicProperties, $requireProperties)
    {
        // parse propertyKey and value from the array
        // $sourceNode,$destinationNode,$sourceProperties,$destinationProperties
        if (!$this->neoHelper->isShortestPathAB($topicNode, $requireNode, $topicProperties, $requireProperties)
           && !$this->neoHelper->isShortestPathBA($requireNode, $topicNode, $requireProperties, $topicProperties)) {
            return true;
        }
    }

    public function parentDelete($nodeLabel, $propertyArray)
    {
        //dd($nodeLabel,$propertyArray);
        $propertyKey = $this->neoHelper->getKey($propertyArray);
        $propertyValue = $this->neoHelper->getValue($propertyArray);
        //$this->neoHelper->propertyValueCheck($propertyValue);
        $this->neoHelper->delete($nodeLabel, $propertyKey, $propertyValue);
    }

    public function parentRelationship($sourceLabel, $destinationLabel, $categoryProperties, $subjectProperties, $relationShipType)
    {
        // find the category ,$destinationkey,$destinationValue,$relationShipType
        $category = $this->neoHelper->getNode($sourceLabel, $categoryProperties);
        $subject = $this->neoHelper->getNode($destinationLabel, $subjectProperties);

        $sourceKey = $this->neoHelper->getKey($category);
        $sourceValue = $this->neoHelper->getValue($category);

        $destinationKey = $this->neoHelper->getKey($subject);
        $destinationValue = $this->neoHelper->getValue($subject);

        $this->neoHelper->createRelation(
            $sourceLabel,
            $destinationLabel,
            $sourceKey,
            $sourceValue,
            $destinationKey,
            $destinationValue,
            $relationShipType
        );
    }

    public function parentDeleteRelationship(
        $sourceLable,
        $destinationLabel,
        $sourceProperty,
        $destinationProperty,
        $existingRelationship
    ) {
        $sourceProperty = $this->neoHelper->removeQuotes($sourceProperty);
        
        $destinationProperty = $this->neoHelper->removeQuotes($destinationProperty);
        $this->neoHelper->deleteRelation(
            $sourceLable,
            $sourceProperty,
            $existingRelationship,
            $destinationLabel,
            $destinationProperty
        );
    }
}
