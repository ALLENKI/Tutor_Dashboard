<?php

namespace Aham\Helpers;

use Carbon;
use DB;
use Sentinel;

use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\StudentAssessment;
use Aham\Models\SQL\Student;

class GoalHelper {

	public static function getNodesAndEdges($goal)
	{
		$goal_topics = $goal->topics->pluck('id')->toArray();

        $added_ids = [];
        $added_edges = [];
        $nodes = [];
        $edges = [];


        $node = [];

        $node['id'] = 0;
        $node['label'] = $goal->name;
        $node['title'] = $goal->name;
        // $node['mass'] = 10;
        $node['color'] = 'green';
        $node['level'] = 0;

        $nodes[] = $node;

        foreach($goal->topics as $topic)
        {
        	$rows = DB::select('CALL TOPIC_FLOW(?)',[$topic->id]);

        	foreach($rows as $row)
            {
            	// Check if this topic is already in topics and remove it
                if(($key = array_search($row->DID, $goal_topics)) !== false) {
                    unset($goal_topics[$key]);
                }

                // Check it's not added already
                if(!in_array($row->ID, $added_ids))
                {

                    $added_ids[] = $row->ID;

                    $node = [];

                    $node['id'] = $row->ID;
                    $node['label'] = $row->NAME;
                    $node['title'] = $row->NAME;
                    // $node['mass'] = 30;
                    $node['level'] = $row->LEVEL;

                    if(is_null($row->LEVEL))
                    {
                        $node['level'] = 6;
                    }
                    else
                    {
                        $node['level'] = 6 - $row->LEVEL;
                    }

                    $node['color'] = ['background' => '#97c2fc','highlight' => [
                    'background' => '#861aaf']];

                    $nodes[] = $node;
                }


                // Add edge
                if(!is_null($row->DID))
                {   
                    $edge = [];
                    $edge['from'] = $row->ID;
                    $edge['to'] = $row->DID;

                    $edge['arrows'] = 'from';

                    $edge_code = $row->ID."-".$row->DID;

                    if(!in_array($edge_code, $added_edges))
                    {
                        $added_edges[] = $edge_code;
                        $edges[] = $edge;
                    }
                    
                }
            }

        }

        //

        foreach($goal_topics as $goal_topic)
        {
            $edge = [];
            $edge['from'] = 0;
            $edge['to'] = $goal_topic;
            $edge['arrows'] = 'from';

            $edge_code = "0-".$goal_topic;
            $added_edges[] = $edge_code;

            $edges[] = $edge;
        }



        return ['nodes' => $nodes, 'edges' => $edges];


	}

}