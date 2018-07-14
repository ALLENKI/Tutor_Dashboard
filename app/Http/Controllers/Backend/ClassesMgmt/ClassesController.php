<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\City;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\Student;

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

class ClassesController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $tableRoute = route('admin::classes_mgmt::classes.table');

        return view('backend.classes_mgmt.classes.index',compact('tableRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topics = Topic::has('units')
                        ->topic()->active()
                        ->orderBy('name','asc')
                        ->pluck('name','id');

        $loggedInUser = view()->shared('loggedInUser');

        $accessibleLocations = $loggedInUser->accessibleLocations('classes.manage');

        $locations = Location::whereIn('id',$accessibleLocations)->pluck('name','id');

        return view('backend.classes_mgmt.classes.create',compact('topics','locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $rules = [
            'location_id' => 'required|exists:locations,id',
            'topic_id' => 'required|exists:topics,id',
            'maximum_days' => 'required|numeric',
            'scheduling_rule_id' => 'required|exists:scheduling_rules,id',
            'minimum_enrollment' => 'required|numeric',
            'maximum_enrollment' => 'required|numeric',
            // 'grades' => 'required'
        ];

        // dd(Input::all());

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $class = AhamClass::create(Input::only('location_id','topic_id','maximum_days','scheduling_rule_id','minimum_enrollment','maximum_enrollment'));

        $class->creator_id = \Sentinel::getUser()->id;

        $class->status = 'initiated';

        $class->grades = implode(',', Input::get('grades',['5-7','8-10','Under_Grad','Grad_or_Higher','Working_Professional','other']));

        if(Input::has('free'))
        {
            $class->free = true;
        }

        if(Input::has('no_tutor_comp'))
        {
            $class->no_tutor_comp = true;
        }

        $class->save();

        $topic = Topic::find(Input::get('topic_id'));

        $class->topic_name = $topic->name; 
        $class->topic_description = $topic->description; 
        $class->save();

        foreach($topic->units as $unit)
        {
            ClassUnit::create([

                'name' => $unit->name,
                'description' => $unit->description,
                'order' => $unit->order,
                'topic_id' => $topic->id,
                'class_id' => $class->id,
                'original_unit_id' => $unit->id

            ]);
        }

        return redirect()->route('admin::classes_mgmt::classes.show',$class->id);
    }


    public function createRepeat($id)
    {
        $ahamClass = AhamClass::find($id);

        $topics = Topic::has('units')
                        ->topic()->active()
                        ->orderBy('name','asc')
                        ->pluck('name','id');

        $loggedInUser = view()->shared('loggedInUser');

        $accessibleLocations = $loggedInUser->accessibleLocations('classes.manage');

        $locations = Location::whereIn('id',$accessibleLocations)->pluck('name','id');

        $schedulingRules = SchedulingRule::where('no_of_units',$ahamClass->topic->units->count())->pluck('description','id')->toArray();

        // dd($certifiedTeachers);

        return view('backend.classes_mgmt.classes.repeat_create',compact('topics','locations','ahamClass','schedulingRules'));

    }

    public function storeRepeat($id)
    {
        $rules = [
            'location_id' => 'required|exists:locations,id',
            'topic_id' => 'required|exists:topics,id',
            'maximum_days' => 'required|numeric',
            'scheduling_rule_id' => 'required|exists:scheduling_rules,id',
            'minimum_enrollment' => 'required|numeric',
            'maximum_enrollment' => 'required|numeric',
            // 'grades' => 'required'
        ];

        // dd(Input::all());

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        \DB::beginTransaction();

        $class = AhamClass::create(Input::only('location_id','topic_id','maximum_days','scheduling_rule_id','minimum_enrollment','maximum_enrollment'));

        $class->creator_id = \Sentinel::getUser()->id;

        $class->status = 'initiated';

        $class->grades = implode(',', Input::get('grades',['5-7','8-10','Under_Grad','Grad_or_Higher','Working_Professional','other']));

        if(Input::has('free'))
        {
            $class->free = true;
        }


        if(Input::has('no_tutor_comp'))
        {
            $class->no_tutor_comp = true;
        }

        $class->save();

        $topic = Topic::find(Input::get('topic_id'));

        $class->topic_name = $topic->name; 
        $class->topic_description = $topic->description; 
        $class->save();

        foreach($topic->units as $unit)
        {
            ClassUnit::create([

                'name' => $unit->name,
                'description' => $unit->description,
                'order' => $unit->order,
                'topic_id' => $topic->id,
                'class_id' => $class->id,
                'original_unit_id' => $unit->id

            ]);
        }

        \DB::commit();

        return redirect()->route('admin::classes_mgmt::classes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        Assets::add('js/plugins/forms/jquery-comments/css/jquery-comments.css');
        Assets::add('js/plugins/forms/jquery-comments/js/jquery-comments.js');


        $ahamClass = AhamClass::find($id);

        // dd($ahamClass);

        ClassStatusHelper::status($ahamClass);

        $eligibleTeachers = TeacherHelper::eligibleTeachers($ahamClass);

        $certifiedTeachers = TeacherCertification::with('teacher.classes','teacher.user')
                                ->where('topic_id', $ahamClass->topic->id)
                                ->get();

        // dd($ahamClass->enrollments->pluck('student_id')->toArray());

        $students = Student::whereNotIn('id',$ahamClass->enrollments->pluck('student_id')->toArray())->get();

        $eligibleStudents = [];

        foreach($students as $student)
        {
            $eligibleStudents[$student->id] = $student->user->name.' ('.$student->user->email.')';
        }

        return view('backend.classes_mgmt.classes.show',compact('ahamClass','eligibleTeachers','certifiedTeachers','eligibleStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $class = AhamClass::find($id);

        $scheduling_rules = SchedulingRule::where('no_of_units',$class->topic->units->count())->pluck('description','id');

        return view('backend.classes_mgmt.classes.edit',compact('class','scheduling_rules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $rules = [
            'maximum_days' => 'required|numeric',
            'scheduling_rule_id' => 'required|exists:scheduling_rules,id',
            'minimum_enrollment' => 'required|numeric',
            'maximum_enrollment' => 'required|numeric',
            // 'grades' => 'required'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $class = AhamClass::find($id);

        $class->fill(Input::only('maximum_days','maximum_enrollment','minimum_enrollment','scheduling_rule_id'));

        $class->no_tutor_comp = false;

        if(Input::has('no_tutor_comp'))
        {
            $class->no_tutor_comp = true;
        }


        $class->grades = implode(',', Input::get('grades',['5-7','8-10','Under_Grad','Grad_or_Higher','Working_Professional','other']));

        $class->save();

        Artisan::queue('aham:schedule_classes');

        return redirect()->route('admin::classes_mgmt::classes.show',$class->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AhamClass::destroy($id);

        return redirect()->back();

    }


    public function table()
    {

        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $loggedInUser = view()->shared('loggedInUser');

        $accessibleLocations = $loggedInUser->accessibleLocations('classes.manage');

        $classesModel = AhamClass::with('topic','location')
                                ->where(function ($query) use ($q) {
                                    
                                    $query
                                    ->whereHas('topic', function($query) use ($q)
                                    {
                                        $query
                                        ->where('name', 'LIKE', '%'.$q.'%');
                                    })
                                    ->orWhereHas('location', function($query) use ($q)
                                    {
                                        $query
                                        ->where('name', 'LIKE', '%'.$q.'%');
                                    });

                                })
                                ->whereIn('location_id',$accessibleLocations);


        if(count(Input::get('class_status')))
        {
            $classesModel = $classesModel->whereIn('status',Input::get('class_status'));
        }

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
            $view_link = route('admin::classes_mgmt::classes.show',$class->id);
            $edit_link = route('admin::classes_mgmt::classes.edit',$class->id);
            $delete_link = route('admin::classes_mgmt::classes.delete',$class->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>

                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$view_link'><i class='icon-eye'></i> View</a></li>
                                    <li><a href='$edit_link'><i class='icon-pencil'></i> Edit</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];


            $row['classes']['code'] = "<a href='$view_link'>".$class->code.'</a>';
            $row['classes']['topic_id'] = $class->topic->name;
            
            $row['classes']['location_id'] = $class->location->name;
            $row['classes']['creator_id'] = $class->creator->email;
            $row['classes']['group_id'] = $class->group_id;
            $row['classes']['created_at'] = $class->created_at->format('jS M Y');

            if($class->start_date)
            {
                $row['classes']['start_date'] = $class->start_date->format('jS M Y h:i A');
            }
            else
            {
                $row['classes']['start_date'] = 'NA';
            }

            if($class->enrollment_cutoff)
            {
                $row['classes']['enrollment_cutoff'] = $class->enrollment_cutoff->format('jS M Y h:i A');
            }
            else
            {
                $row['classes']['enrollment_cutoff'] = 'NA';
            }
            
            if($class->teacher_id)
            {
                $row['classes']['teacher'] = $class->teacher->user->name;
            }
            else
            {
                $row['classes']['teacher'] = 'NA';
            }

            $row['classes']['actions'] = $actions;

            $row['classes']['status'] = '<span class="label label-primary">'.ucfirst($class->status).'</span>';

            if($class->isScheduled())
            {
                $row['classes']['scheduled'] = '<span class="label label-success">Yes</span>';
            }
            else
            {
                $row['classes']['scheduled'] = '<span class="label label-danger">No</span>';
            }

            if($class->free)
            {
                $row['classes']['free'] = '<span class="label label-success">Yes</span>';
            }
            else
            {
                $row['classes']['free'] = '<span class="label label-danger">No</span>';
            }


            $records["data"][] = $row;
        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    public function cancelClassModal($id)
    {
        $class = AhamClass::find($id);
        
        return view('backend.classes_mgmt.classes._cancel_modal',compact('class'));
    }

    public function cancelClass($id)
    {
        $ahamClass = AhamClass::find($id);

        $rules = [
            'confirm' => 'required|in:DELETE',
            'remarks' => 'required',
        ];

        $messages = [
            'confirm.in' => 'Please type DELETE to confirm'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $ahamClass->status = 'cancelled';
        $ahamClass->cancelled_at = Carbon::now();
        $ahamClass->cancellation_reason = Input::get('remarks');
        $ahamClass->save();

        foreach($ahamClass->timings as $timing)
        {
            $timing->status = 'cancelled';
            $timing->save();
        }

        ClassStatusManager::giveBackCredits($ahamClass);

        event(new \Aham\Events\AdminCancelledClass($ahamClass));

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully cancelled']]
        ), 200);

    }


}
