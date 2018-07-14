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
use Aham\Models\SQL\TeacherEarning;



use Aham\Helpers\AssessmentHelper;

use Aham\Helpers\TeacherGraphHelper;


use Input;
use Sentinel;
use Validator;
use Assets;
use DB;
use Carbon;

class TeacherEarningsController extends BaseController 
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
    public function index($id)
    {
        $teacher =  Teacher::with('allEarnings','completedClasses')->find($id);

        // dd($teacher->allEarnings);

        return view('backend.users.teachers.earnings',compact('teacher'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPayout($id)
    {

        $rules = [
            'amount' => 'required|numeric',
            'paid_on' => 'required|date',
            'cheque_no' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $data = Input::only('amount','cheque_no','mode','remarks','paid_on','actual_amount','tax');

        $data['paid_on'] = Carbon::createFromTimestamp(strtotime($data['paid_on']));
        $data['teacher_id'] = $id;

        $teacher =  Teacher::find($id);

        DB::beginTransaction();

        $teacher->payouts += Input::get('amount');
        $teacher->save();

        TeacherEarning::create($data);

        DB::commit();

        flash()->success('Successfully Created!');

        return redirect()->back();
    }   

}
