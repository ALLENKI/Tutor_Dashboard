<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\CloudinaryImage;
use Aham\Models\SQL\TutorCommission;
use Aham\Models\SQL\Location;
use Aham\Helpers\TopicHelper;
use Aham\Helpers\TopicLookupHelper;


use Aham\Helpers\AssessmentHelper;

use Aham\Helpers\TeacherGraphHelper;


use Input;
use Sentinel;
use Validator;
use Assets;
use DB;

class TeachersController extends BaseController 
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
        $tableRoute = route('admin::users::teachers.table');

        return view('backend.users.teachers.index',compact('tableRoute'));
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

        if($user->teacher)
        {
            flash()->error('This user already is a teacher');
        }

        $teacher = new Teacher();

        $teacher->show_on_homepage = false;

        $user->teacher()->save($teacher);

        flash()->success('Teacher successfully created');
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

        $model = Teacher::with('user')
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

        $iTotalRecords = $model->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $teachers = $model
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($teachers as $teacher)
        {
            $view_link = route('admin::users::teachers.show',$teacher->id);
            $user_link = route('admin::users::users.show',$teacher->user->id);
            $certification_link = route('admin::users::teachers.show_certification',$teacher->id);
            $edit_link = route('admin::users::teachers.edit',$teacher->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>

                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$certification_link'><i class='icon-pencil'></i> Teacher Certification</a></li>
                                    <li><a href='$user_link'><i class='icon-pencil'></i> User Profile</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];

            $row['teachers']['code'] = "<a href='$user_link'>".$teacher->code."</a>";
            $row['teachers']['name'] = $teacher->user->name;
            $row['teachers']['email'] = "<a href='$user_link'>".$teacher->user->email."</a>";

            $row['teachers']['active'] = $teacher->active ? 'yes' : 'no';


            $row['teachers']['actions'] = $actions;

            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    /*******************************************/

    public function showCertification($id)
    {
        Assets::add("js/plugins/visualization/d3/d3.min.js");
        Assets::add("js/charts/d3/tree/tree_collapsible_aham.js");

        $teacher =  Teacher::with('certifications.topic')->find($id);

        $user = $teacher->user;

        $assessed_topics = $teacher->certifications->pluck('topic_id')->toArray();

        $topics = Topic::whereNotIn('id',$assessed_topics)
                        ->whereIn('type',['sub-category','topic'])
                        ->pluck('name','id');

        return view('backend.users.teachers.certification',compact('teacher','user','topics'));
    }

    public function addCertification($id)
    {
        $rules = [
            'topic_id' => 'required|exists:topics,id',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        AssessmentHelper::addTeacherCertification($id, Input::get('topic_id'));

        flash()->success('Successfully added certification.');

        return redirect()->back();

    }

    public function removeCertification($id)
    {
        DB::beginTransaction();

        $certification = TeacherCertification::find($id);

        $teacher_id = $certification->teacher_id;

        $allTopics = TopicLookupHelper::getTopicChildren($certification->topic);

        $certification = TeacherCertification::where([
            'topic_id' => $certification->topic_id,
            'teacher_id' => $teacher_id
        ]);

        $certification->delete();

        foreach($allTopics as $topic)
        {
            $certification = TeacherCertification::where([
                'topic_id' => $topic,
                'teacher_id' => $teacher_id
            ]);

            $certification->delete();
        }

        DB::commit();

        flash()->success('Successfully removed assessment.');

        return redirect()->back();
    }


    public function graph($id)
    {
        $teacher =  Teacher::with('certifications.topic')->find($id);

        $graphHelper = new TeacherGraphHelper();

        return $graphHelper->graph($teacher->user->username, 'admin::topic_tree::topics.show','id');
    }

    public function controlStatus($id)
    {
        $teacher =  Teacher::find($id);

        if(Input::has('active'))
        {
            $teacher->active = true;
        }

        if(Input::has('inactive'))
        {
            $teacher->active = false;
        }

        $teacher->save();

        if($teacher->active)
        {
            event(new \Aham\Events\Teacher\Activated($teacher));
        }

        return redirect()->back();

    } 

    /*** Public Profile ***/

    public function getPublicProfile($id)
    {
        $teacher =  Teacher::with('user')->find($id);

        $user = $teacher->user;

        $topics = Topic::ofType('subject')->pluck('name','name')->toArray();

        $public_profile = json_decode($user->public_profile,true);

        // dd($public_profile);

        return view('backend.users.teachers.public_profile',compact('teacher','user','topics','public_profile'));
    }

    public function updatePublicProfile($id)
    {
        $public_profile = Input::only('name','tagline','bio','education','experience','research','linkedin','facebook','twitter');

        $teacher =  Teacher::with('user')->find($id);

        $user = $teacher->user;

        // dd($public_profile);

        $data['public_profile'] = json_encode($public_profile);

        $data['interested_subjects'] = implode(',',Input::get('interested_subjects',[]));

        $user->fill($data);

        $user->save();

        flash()->success('Profile updated successfully.');

        return redirect()->back();
    }

    /**** Tutor Commission ****/

    public function getCommission($id)
    {
        $teacher =  Teacher::with('user')->find($id);

        $user = $teacher->user;

        $locations = Location::pluck('name','id');

        $tutorCommission = TutorCommission::firstOrCreate([
            'teacher_id' => $id,
        ]);

        return view('backend.users.teachers.commission',compact('teacher','locations','user','tutorCommission'));
    }

    public function postCommission($id)
    {
        $tutorCommission = TutorCommission::firstOrCreate([
            'teacher_id' => $id,
        ]);

        $tutorCommission->location_id = Input::get('location_id');
        $tutorCommission->value_type = Input::get('value_type');
        $tutorCommission->value = Input::get('value');
        $tutorCommission->min_enrollment = Input::get('min_enrollment');

        $tutorCommission->save();

        return redirect()->back();
    }
}
