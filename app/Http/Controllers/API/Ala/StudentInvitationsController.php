<?php

namespace Aham\Http\Controllers\API\Ala;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;

use League\Fractal;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;
use Aham\Transformers\AhamClassTransformer;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\StudentInvitation;

use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

class StudentInvitationsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getInvitations(Request $request, $id)
    {
        $ahamClass = AhamClass::find($id);

        $alreadyInvited = $ahamClass->studentInvitations;

        $students = Student::where('active',true)->whereNotIn('id',$alreadyInvited->pluck('student_id')->toArray())->get();

        $allStudents = [];

        foreach($students as $student)
        {
            $allStudents[] = [
                'id' => $student->id,
                'email' => $student->user->email,
                'name' => $student->user->name
            ];
        }

        $invitedStudents = [];

        foreach($alreadyInvited as $invited)
        {
            $invitedStudents[] = [
                'id' => $invited->student_id,
                'email' => $invited->student->user->email,
                'name' => $invited->student->user->name,
                'status' => $invited->status
            ];
        }

        return [    
            'invitedStudents' => $invitedStudents,
            'allStudents' => $allStudents,
            'eligibleStudents' => $allStudents,
        ];

    }

    public function postInvitations(Request $request, $id)
    {
        $ahamClass = AhamClass::find($id);

        $invitations = Input::get('invitations');

        // An invite is sent at Class level, not unit level.

        foreach($invitations as $invitation)
        {
            StudentInvitation::firstOrCreate([
                'class_id' => $ahamClass->id,
                'student_id' => $invitation
            ]);
        }

        $ahamClass->save();

        return response()->json([
                'success' => true
        ],200);
    }

    public function awardInvitation(Request $request, $invitation)
    {
        $invitation = ClassInvitation::find($invitation);

        $ahamClass = $invitation->ahamClass;

        \DB::beginTransaction();

        foreach($ahamClass->invitations as $invitationRow)
        {
            if($invitationRow->id != $invitation->id)
            {
                $invitationRow->status = 'lost';
                $invitationRow->save();
            }
            else
            {
                $invitationRow->status = 'awarded';
                $invitationRow->save();
            }
        }

        $ahamClass->teacher_id = $invitation->teacher_id;
        $ahamClass->commission = $invitation->teacher->commission;
        // $ahamClass->status = 'open_for_enrollment';

        $ahamClass->save();

        foreach($ahamClass->timings as $timing)
        {
            $timing->teacher_id = $ahamClass->teacher_id;
            $timing->commission = $invitation->teacher->commission;
            $timing->save();
        }

        \DB::commit();

        return response()->json([
                'success' => true
        ],200);

    }
}
