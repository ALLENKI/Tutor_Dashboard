<?php

namespace Aham\Helpers;


use Aham\Models\SQL\TopicPrerequisite;
use Aham\Models\SQL\Topic;

class PrerequisiteHelper {

    public static function isEligible($requirer, $topic)
    {

        // Rule 1 - $topic shouldn't be in parents of the requirer

        // Get parents of $requirer

        $parents = static::getParents($requirer);

        // dd($parents);

        // $topic can't in parents of $requirer

        if(in_array($topic->id, $parents))
        {
            return "// Rule 1 - topic should not be in parents of the requirer";
            return false;
        }

        // Rule 2 : Topic shouldn't already be unlocked directly
        // or indirectly by existing requirer's prerequisites

        // Get requirer fullTree and check if topic is in full tree.

        $requirerTree = new TreeExplorer();

        $requirerTree->getFullTree([$requirer->id]);

        if(in_array($topic->id, $requirerTree->explored))
        {
            return "Rule 2 : Topic shouldnt already be unlocked directly or indirectly by existing requirer's prerequisites";
            return false;
        }

        // Rule 3 : Requirer shouldn't already be unlocked directly
        // or indirectly by existing topic's prerequisites

        // Can't have circular dependency

        // Get  full Tree of topic and check if requirer is in full Tree
        
        $topicTree = new TreeExplorer();

        $topicTree->getFullTree([$topic->id]);

        if(in_array($requirer->id, $topicTree->explored))
        {
            return "// Rule 3 : Requirer shouldn't already be unlocked directly
        // or indirectly by existing topic's prerequisites";
            return false;
        }

        // Rule 4: topic and requirer both (at a time) shouldn't be satisfying any existing requirer directly

        $allRequirers = TopicPrerequisite::distinct()
                                // ->whereNotIn('requirer_id',[$topic->id, $requirer->id])
                                ->pluck('requirer_id')
                                ->toArray();

        $allRequirers = Topic::whereIn('id',$allRequirers)->get();

        foreach($allRequirers as $allRequirer)
        {
            $intersection = array_intersect($allRequirer->prerequisites->pluck('id')->toArray(),[$topic->id, $requirer->id]);

            if(count($intersection) == 2)
            {
                // dd($intersection);
                return "// Rule 4: topic and requirer both (at a time) shouldn't be satisfying any existing requirer directly: Check: ".$allRequirer->name." (".$allRequirer->id.")";
                return false;
            }
        }

        // Don't need repetitive checks

        // $contains = $topic->prerequisites()
        //                     ->where('topic_id',$requirer->id)
        //                     ->count();

        // if($contains)
        // {
        //     return false;
        // }

        // // Already added

        // $contains = $requirer->prerequisites()
        //                     ->where('topic_id',$topic->id)
        //                     ->count();

        // if($contains)
        // {
        //     return false;
        // }

        return true;
    }

    public static function getParents($requirer)
    {
        $parents = [];

        while($requirer->parent)
        {
            $parents[] = $requirer->parent->id;
            $requirer = $requirer->parent;
        }

        return $parents;

    }

    public static function getTopicsOnlyTree($topic)
    {
        // Get prerequisites of this 
    }

}
