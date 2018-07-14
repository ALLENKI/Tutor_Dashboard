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
use Aham\Models\SQL\ClassInvitation;

use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

class InvitationsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEligiblePerUnit(Request $request, $id) {

        $unitTiming = ClassTiming::with('unit')->find($id);

        $eligibleTeachers = TeacherHelper::eligibleTeachersPerUnit($unitTiming);

        $eligibleTeachers = Teacher::where('active',true)->whereIn('id',array_keys($eligibleTeachers))->get();

        $allEligibleTeachers = [];

        foreach($eligibleTeachers as $eligibleTeacher)
        {
            $allEligibleTeachers[] = [
                'id' => $eligibleTeacher->id,
                'email' => $eligibleTeacher->user->email,
                'name' => $eligibleTeacher->user->name
            ];
        }

        return $allEligibleTeachers;
    }

    public function getInvitations(Request $request, $id)
    {
        $ahamClass = AhamClass::find($id);

        $eligibleTeachers = TeacherHelper::eligibleTeachers($ahamClass);

         //dd($eligibleTeachers);

        $eligibleTeachers = Teacher::where('active',true)->whereIn('id',array_keys($eligibleTeachers))->get();

        $allEligibleTeachers = [];

        foreach($eligibleTeachers as $eligibleTeacher)
        {
            $allEligibleTeachers[] = [
                'id' => $eligibleTeacher->id,
                'email' => $eligibleTeacher->user->email,
                'name' => $eligibleTeacher->user->name
            ];
        }

        $certifiedTeachers = TeacherCertification::with('teacher.classes','teacher.user')
                                ->where('topic_id', $ahamClass->topic->id)
                                ->get();

        $allCertifiedTeachers = [];

        foreach($certifiedTeachers as $certifiedTeacher)
        {
            $allCertifiedTeachers[] = [
                'available' => TeacherHelper::isAvailable($ahamClass, $certifiedTeacher),
                'ignoreCalendar' => $certifiedTeacher->teacher->ignore_calendar,
                'email' => $certifiedTeacher->teacher->user->email,
                'name' => $certifiedTeacher->teacher->user->name
            ];  
        }

        $invitations = $ahamClass->invitations;

        $invitedTeachers = [];

        foreach($invitations as $invitation)
        {
            $invitedTeachers[] = [
                'status' => $invitation->status,
                'id' => $invitation->id,
                'teacher_id' => $invitation->teacher->id,
                'teacher_name' => $invitation->teacher->user->name,
                'teacher_email' => $invitation->teacher->user->email,
                'teacher_mobile' => $invitation->teacher->user->mobile,
            ];
        }

        return [    
            'invitedTeachers' => $invitedTeachers,
            'certifiedTeachers' => $allCertifiedTeachers,
            'eligibleTeachers' => $allEligibleTeachers,
        ];

    }

    

    public function postInvitations(Request $request, $id)
    {
        $ahamClass = AhamClass::find($id);

        $invitations = Input::get('invitations');

        // An invite is sent at Class level, not unit level.

        foreach($invitations as $invitation)
        {
            ClassInvitation::firstOrCreate([
                'class_id' => $ahamClass->id,
                'teacher_id' => $invitation['id']
            ]);
        }

        // $ahamClass->status = 'invited';
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
