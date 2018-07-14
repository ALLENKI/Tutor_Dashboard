<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Managers\TeacherClassesManager;

use Aham\Models\SQL\ClassInvitation;

use Aham\Helpers\TeacherHelper;

use Validator;
use Input;

class InvitationsController extends TeacherDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function newInvitations()
    {
    	$teacherClassesManager = new TeacherClassesManager($this->teacher);

        $newInvitations = $teacherClassesManager->getPendingInvitations();

    	return view('dashboard.teacher.invitations.new',compact('newInvitations'));
    }

    public function pending()
    {
        $teacherClassesManager = new TeacherClassesManager($this->teacher);

        $pendingInvitations = $teacherClassesManager->getAcceptedInvitations();

        // We show to user as pending invitation, because he is waiting for admin's action on these invitation

        return view('dashboard.teacher.invitations.pending',compact('pendingInvitations'));
    }


    public function awarded()
    {
        $teacherClassesManager = new TeacherClassesManager($this->teacher);

        $awardedInvitations = $teacherClassesManager->getAwardedInvitations();

        // We show to user as pending invitation, because he is waiting for admin's action on these invitation

        return view('dashboard.teacher.invitations.awarded',compact('awardedInvitations'));
    }

    public function all()
    {
        $teacherClassesManager = new TeacherClassesManager($this->teacher);

        $allInvitations = $teacherClassesManager->getAllInvitations();

        return view('dashboard.teacher.invitations.all',compact('allInvitations'));
    }

    public function acceptModal($id)
    {
        $invitation = ClassInvitation::find($id);

        // Find Availability
        $available = TeacherHelper::isAvailable($invitation->ahamClass, $invitation);

        if(!$available)
        {
            return view('dashboard.teacher.invitations.not_available',compact('invitation'));
        }

        return view('dashboard.teacher.invitations.accept',compact('invitation'));
        
    }

    public function accept($id)
    {
        $invitation = ClassInvitation::find($id);

        $invitation->fill([
            'status' => 'accepted'
        ]);

        $invitation->save();

        return \Response::json(array(
            'success' => true,
            'redirect' => route('teacher::invitations.pending'),
            'errors' => [['Successfully accepted invitation']]

        ), 200);
    }


    public function declineModal($id)
    {
        $invitation = ClassInvitation::find($id);

        return view('dashboard.teacher.invitations.decline',compact('invitation'));  
    }


    public function decline($id)
    {
        $rules = [
            'decline_reason' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $invitation = ClassInvitation::find($id);

        $invitation->fill([
            'status' => 'declined',
            'decline_reason' => Input::get('decline_reason'),
            'decline_remarks' => Input::get('decline_remarks'),
        ]);

        $invitation->save();

        flash()->success('You have declined the invitation.');

        return \Response::json(array(
            'success' => true,
            'redirect' => route('teacher::invitations.all'),
            'errors' => [['Class successfully declined']]

        ), 200);
    }

}