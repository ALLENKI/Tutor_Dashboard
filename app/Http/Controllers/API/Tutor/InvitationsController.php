<?php

namespace Aham\Http\Controllers\API\Tutor;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;

use Aham\Helpers\TeacherHelper;


use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;

use Aham\Managers\TeacherClassesManager;

use Aham\Transformers\InvitationTransformer;

use Aham\Models\SQL\ClassInvitation;


class InvitationsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {    
    	$filter = Input::get('filter','all');

        $user = $this->auth->user();

        $teacher = $user->teacher;

        $teacherClassesManager = new TeacherClassesManager($teacher);

        $model = $teacher->invitations();

        switch ($filter) {

            case 'new':
                $invitations = $teacherClassesManager->getPendingInvitations();
                break;

            case 'pending':
                $invitations = $teacherClassesManager->getAcceptedInvitations();
                break;

            case 'awarded':
                $invitations = $teacherClassesManager->getAwardedInvitations();
                break;

            default:
                $invitations = $teacherClassesManager->getAllInvitations();
                break;
        }

		// $invitations = $model->paginate(Input::get('per_page',10));

        return $this->response->paginator($invitations, new InvitationTransformer);
    }

    public function show($id)
    {    

        $user = $this->auth->user();

        $teacher = $user->teacher;

        $model = $teacher->invitations()->find($id);

        return $this->response->item($model, new InvitationTransformer);
    }


    public function accept($id)
    {
       $invitation = ClassInvitation::find($id);

        // Find Availability
       $available = TeacherHelper::isAvailable($invitation->ahamClass, $invitation);

       // dd($available);

       if(!$available)
       {
            return $this->response->withArray([
                'result'=>'error',
                'messages' => 'You are not available in these timings'
            ])->setStatusCode(200);
       }

        $invitation->fill([
            'status' => 'accepted'
        ]);

        $invitation->save();

        return $this->response->item($invitation->fresh(), new InvitationTransformer);

    }

    public function reject($id)
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

        return $this->response->item($invitation->fresh(), new InvitationTransformer);
    }


}
