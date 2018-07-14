<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\Goal;

use Aham\Models\SQL\StudentAssessment;

use Aham\Helpers\AssessmentHelper;

use Input;
use Sentinel;
use Validator;
use DB;


class StudentGoalsController extends BaseController 
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
    public function getGoals($id)
    {
        $student =  Student::with('assessments.topic')->find($id);

        $user = $student->user;

        $student_goals = $student->goals->pluck('id')->toArray();

        $goals = Goal::whereNotIn('id',$student_goals)->pluck('name','id')->toArray();

        return view('backend.users.students.goals',compact('student','goals','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postGoal($id)
    {   
        $student = Student::with('assessments.topic')->find($id);

        $student->goals()->attach(Input::get('goal_id'));

        $student->save();

        flash()->success('Successfully added goal');
        return redirect()->back();
    }

    public function remove($id,$goal)
    {   
        $student = Student::with('assessments.topic')->find($id);

        $student->goals()->detach($goal);

        $student->save();

        flash()->success('Successfully removed goal');
        return redirect()->back();
    }

}
