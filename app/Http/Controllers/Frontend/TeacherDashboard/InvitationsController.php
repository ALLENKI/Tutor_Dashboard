<?php

namespace Aham\Http\Controllers\Frontend\TeacherDashboard;

use Aham\Models\SQL\ClassInvitation;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\User;

class InvitationsController extends TeacherDashboardBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    	$pendingInvitations = 
                $this->teacher->invitations()->pending()->get();

        $acceptedInvitations = 
                $this->teacher->invitations()->accepted()->get();

    	return view('frontend.tutor_dashboard.invitations',compact('pendingInvitations','acceptedInvitations'));
    }

    public function show($id)
    {
    	$invitation = ClassInvitation::find($id);

    	return view('frontend.teacher_dashboard.invitations.show',compact('invitation'));
    }

    public function accept($slug, $id)
    {
        $foundUser = User::where('username',$slug)->firstOrFail();

        if(!$foundUser->teacher)
        {
            abort(404);
        }

        $teacher = $foundUser->teacher;

        $invitation = ClassInvitation::findOrFail($id);

        if($teacher->id != $invitation->teacher->id)
        {
            abort(404);
        }

        $invitation->fill([
            'status' => 'accepted'
        ]);

        $invitation->save();

        return redirect()->route('tutor::invitations.show',$teacher->user->username);
    }

    public function reject($id)
    {
        $invitation = ClassInvitation::find($id);

        $invitation->fill([
            'status' => 'rejected'
        ]);

        $invitation->save();

        return redirect()->route('teacher_dashboard::invitations.show',$invitation->id);
    }


    public function propose($id)
    {
        $rules = [
            'date_range' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $invitation = ClassInvitation::find($id);

        $dates = explode(' - ', Input::get('date_range'));

        $from_date = Carbon::createFromTimestamp(strtotime($dates[0]));
        $to_date = Carbon::createFromTimestamp(strtotime($dates[1]));

        $invitation->fill([
            'from_date' => $from_date,
            'to_date' => $to_date,
            'status' => 'alternate_timing'
        ]);

        $invitation->save();

        return redirect()->route('teacher_dashboard::invitations.show',$invitation->id);
    }

}