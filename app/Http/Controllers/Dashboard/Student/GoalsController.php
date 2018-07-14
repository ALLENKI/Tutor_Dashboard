<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Validator;
use Input;
use Assets;

use Aham\Helpers\GoalHelper;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TopicsLookup;
use Aham\Models\SQL\Goal;

class GoalsController extends StudentDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $goal = NULL;

        $goals = Goal::active()->pluck('name','slug')->toArray();

        if(Input::get('goal','') != '')
        {
	        Assets::add('js/plugins/visualization/vis/vis.min.css');     
        	Assets::add('js/plugins/visualization/vis/vis.min.js');     

        	$goal = Goal::where('slug',Input::get('goal'))->first();

            // dd($goal);

	        $student_goals = $this->student->goals->pluck('id')->toArray();

        	$nodesAndEdges = GoalHelper::getNodesAndEdges($goal);

        	$nodes = $nodesAndEdges['nodes'];
        	$edges = $nodesAndEdges['edges'];

        	return view('dashboard.student.goals.index',compact('goal','goals','nodes','edges','student_goals'));
        }

        return view('dashboard.student.goals.index',compact('goals','goal'));
    }

    public function show($slug)
    {
        Assets::add('js/plugins/visualization/vis/vis.min.css');     
        Assets::add('js/plugins/visualization/vis/vis.min.js');  

        $goal = Goal::where('slug',$slug)->first();

        $student_goals = $this->student->goals->pluck('id')->toArray();

        $all_topics = Topic::topic()->pluck('slug','id')->toArray();

        $student = $this->student;

        $goal_url = env('NODE_JS').'/rest/appdata/studentassessmentgoal?goalId='.$goal->id.'&studentId='.$student->id;

        $url = $goal_url;
        $response = \Httpful\Request::get($url)
            ->send();

        $nodes =  $response->body->data->nodes;
        $edges =  $response->body->data->edges;

        $goal_topics = [];

        $analytics = [];
        
        // Go through nodes, group by colors

        foreach ($nodes as $node) 
        {
            $goal_topics[] = $node->id;
        }

        $goal_topics = Topic::with('units')
                            ->whereIn('id',$goal_topics)
                            ->orderBy('graph_level','desc')
                            ->get();

        $total = [];

        foreach ($nodes as $node) 
        {
            if(isset($node->color->background))
            {
                $units = $goal_topics->find($node->id)->units->count();

                $goal_topics->find($node->id)->color = $node->color->background;

                $total[$node->id] = $units;

                if(isset($analytics[$node->color->background]))
                {
                    $analytics[$node->color->background] += $units;
                }
                else
                {
                    $analytics[$node->color->background] = $units;
                }
            }
        }

        // dd($goal_topics);

        return view('dashboard.student.goals.show',compact('goal','student_goals','all_topics','goal_topics','analytics'));

    }

    public function add($id)
    {
        $student = $this->student;

        $student->goals()->attach($id);

        $student->save();

        flash()->success('Successfully added goal');

        return redirect()->back();
    }

    public function remove($id)
    {
        $student = $this->student;

        $student->goals()->detach($id);

        $student->save();

        flash()->success('Successfully removed goal');
        
        return redirect()->route('student::goals.index');
    }
}