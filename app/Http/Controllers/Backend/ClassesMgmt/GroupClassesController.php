<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\City;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\Goal;
use Aham\Models\SQL\GroupClass;

use Aham\Interactions\ClassSchedule;
use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

use Aham\Managers\ClassStatusManager;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;
use Artisan;

class GroupClassesController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $tableRoute = route('admin::classes_mgmt::group_classes.table');

        return view('backend.classes_mgmt.group_classes.index',compact('tableRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $goals = Goal::orderBy('name','asc')
                        ->pluck('name','id');

        $loggedInUser = view()->shared('loggedInUser');

        $accessibleLocations = $loggedInUser->accessibleLocations('classes.manage');

        $locations = Location::whereIn('id',$accessibleLocations)->pluck('name','id');

        $topics = null;
        $topicRules = null;
        $goal_topics = null;

        if(Input::has('goal'))
        {
            $goal = Goal::find(Input::get('goal'));
            $topics = $goal->topics;
            $topicRules = [];

            $goal_topics = $goal->topics()
                                ->with('units')
                                ->orderBy('graph_level','desc')
                                ->get();

            $schedulingRules = [];

            foreach(SchedulingRule::get() as $rule)
            {   
                $division = 'return '.str_replace('-', '*', $rule->division).';';

                if(eval($division) == '1')
                {
                    $schedulingRules[$rule->no_of_units]['selected'] = $rule->id;
                }

                // $schedulingRules[$rule->no_of_units]['selected'] = 0;

            }

            // dd($schedulingRules);

            foreach($topics as $topic)
            {
                $units = $topic->units;

                $number_of_units = $units->count();

                $schedulingRules[$number_of_units]['options'] = SchedulingRule::where('no_of_units', $number_of_units)->pluck('description','id')->toArray();
            }

        }

        // dd($goal_topics->pluck('id'));
        

        return view('backend.classes_mgmt.group_classes.create',compact('goals','locations','topics','topicRules','goal_topics','schedulingRules'));
    }

    public function store()
    {

        
        // dd(Input::all());

        $groups = Input::get('group');
        $creates = Input::get('create',[]);

        // dd($creates);

        if(count($creates))
        {
            $groupClass = GroupClass::create(['name' => Input::get('name'),'goal_id' => Input::get('goal')]);

            foreach($creates as $create)
            {
                $group = $groups[$create];

                $data = [];

                $data['topic_id'] = $create;
                $data['location_id'] = $group['location_id'];
                $data['maximum_days'] = $group['maximum_days'];
                $data['scheduling_rule_id'] = $group['scheduling_rule_id'];
                $data['minimum_enrollment'] = $group['minimum_enrollment'];
                $data['maximum_enrollment'] = $group['maximum_enrollment'];

                if(isset($group['grades']))
                {
                    $data['grades'] = implode(',',$group['grades']);
                }
                else
                {
                    $data['grades'] = implode(',',['5-7','8-10','Under_Grad','Grad_or_Higher','Working_Professional','other']);
                }

                if(isset($group['free']))
                {
                    $data['free'] = true;
                }

                if(isset($group['no_tutor_comp']))
                {
                    $data['no_tutor_comp'] = true;
                }


                $data['group_id'] = $groupClass->id;

                $class = AhamClass::create($data);

                $class->creator_id = \Sentinel::getUser()->id;

                $class->status = 'initiated';

                $class->save();

            }

            flash()->success('Created classes');
            return redirect()->route('admin::classes_mgmt::group_classes.show',$groupClass->id);

        }
        else
        {

            flash()->error('Created no classes');
            return redirect()->back();

        }

    }

    public function show($id)
    {
        $groupClass = GroupClass::find($id);

        $classes = $groupClass->classes;

        foreach($classes as $ahamClass)
        {
            ClassStatusHelper::status($ahamClass);
        }

        return view('backend.classes_mgmt.group_classes.show',compact('groupClass','classes'));
    }

    public function table()
    {

        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];


        $classesModel = GroupClass::where(function ($query) use ($q) {
                                    
                                    $query->where('name', 'LIKE', '%'.$q.'%');

                                });;

        $iTotalRecords = $classesModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $classes = $classesModel
                        ->skip($iDisplayStart)
                        ->take($iDisplayLength)
                        ->orderBy($column,$sort)
                        ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($classes as $class)
        {
            $view_link = route('admin::classes_mgmt::group_classes.show',$class->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>

                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$view_link'><i class='icon-eye'></i> View</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];


            $row['classes']['id'] = "<a href='$view_link'>".$class->id.'</a>';
            $row['classes']['name'] = $class->name;
            $row['classes']['goal_id'] = $class->goal->name;

            $row['classes']['actions'] = $actions;

            $records["data"][] = $row;
        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }


}
