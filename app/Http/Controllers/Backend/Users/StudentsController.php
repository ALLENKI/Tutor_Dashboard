<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Topic;

use Aham\Models\SQL\StudentAssessment;

use Aham\Helpers\AssessmentHelper;

use Input;
use Sentinel;
use Validator;
use DB;
use Assets;

use Aham\Helpers\GraphHelper;


class StudentsController extends BaseController 
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
        $tableRoute = route('admin::users::students.table');

        return view('backend.users.students.index',compact('tableRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'user_id' => 'required|exists:users,id'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $user = User::find(Input::get('user_id'));

        if($user->student)
        {
            flash()->error('This user already is a student');
        }

        $student = new Student();

        $user->student()->save($student);

        return redirect()->back();
    }   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        // dd($q);

        $model = Student::with('user')
                        ->where(function ($query) use ($q) {
                                    
                            $query
                            ->where('code', 'LIKE', '%'.$q.'%')
                            ->orWhereHas('user', function($query) use ($q)
                            {
                                $query->where(function ($query) use ($q)
                                {
                                    $query->where('name', 'LIKE', '%'.$q.'%')
                                        ->orWhere('email', 'LIKE', '%'.$q.'%');
                                });
                                
                            });

                        });
        // dd($model);

        $iTotalRecords = $model->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));

        $students = $model
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        // dd($students);

        foreach($students as $student)
        {
            $view_link = route('admin::users::students.show',$student->id);
            $user_link = route('admin::users::users.show',$student->user->id);
            $assessment_link = route('admin::users::students.show_assessment',$student->id);
            $edit_link = route('admin::users::students.edit',$student->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>

                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$assessment_link'><i class='icon-pencil'></i> Student Assessment</a></li>
                                    <li><a href='$user_link'><i class='icon-pencil'></i> User Profile</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];

            $row['students']['code'] = "<a href='$assessment_link'>".$student->code."</a>";
            $row['students']['id'] = $student->id;
            $row['students']['active'] = $student->active ? 'yes' : 'no';
            $row['students']['credits'] = $student->credits;
            $row['students']['name'] = $student->user->name;
            $row['students']['email'] = "<a href='$user_link'>".$student->user->email."</a>";
            $row['students']['actions'] = $actions;

            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    /*******************************************/

    public function showAssessment($id)
    {

        Assets::add("js/plugins/visualization/d3/d3.min.js");
        Assets::add("js/charts/d3/tree/tree_collapsible_aham.js");


        $student =  Student::with('assessments.topic')->find($id);

        $user = $student->user;

        $assessed_topics = $student->assessments->pluck('topic_id')->toArray();

        $topics = Topic::whereNotIn('id',$assessed_topics)
                        ->whereIn('type',['sub-category','topic'])
                        ->pluck('name','id');

        return view('backend.users.students.assessment',compact('student','user','topics'));
    }

    public function addAssessment($id)
    {
        $rules = [
            'topic_id' => 'required|exists:topics,id',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        AssessmentHelper::addStudentAssessment($id, Input::get('topic_id'));

        flash()->success('Successfully added assessment.');

        return redirect()->back();

    }

    public function removeAssessment($id)
    {
        DB::beginTransaction();

        $assessment = StudentAssessment::find($id);

        $student_id = $assessment->student_id;

        $fullTree = AssessmentHelper::getFullTree($assessment->topic_id);

        foreach($fullTree as $topic)
        {
            $assessment = StudentAssessment::where([
                'topic_id' => $topic,
                'student_id' => $student_id
            ]);

            $assessment->delete();
        }

        DB::commit();

        flash()->success('Successfully removed assessment.');

        return redirect()->back();
    }

    public function graph($id)
    {
        $student =  Student::with('assessments.topic')->find($id);

        $graphHelper = new GraphHelper();

        return $graphHelper->graph($student->user->username, 'admin::topic_tree::topics.show','id');
    }

    public function controlStatus($id)
    {
        $student =  Student::with('assessments.topic')->find($id);

        if(Input::has('active'))
        {
            $student->active = true;
        }

        if(Input::has('inactive'))
        {
            $student->active = false;
        }

        $student->save();

        if($student->active)
        {
            event(new \Aham\Events\Student\Activated($student));
        }

        return redirect()->back();

    } 
}
