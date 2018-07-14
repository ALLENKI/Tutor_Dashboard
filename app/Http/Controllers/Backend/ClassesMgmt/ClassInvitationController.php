<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\Slot;

use Aham\Interactions\ClassSchedule;
use Aham\Helpers\TeacherHelper;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;
use DB;

class ClassInvitationController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function inviteTeacher($class)
    {
        $rules = [
            'teacher_id' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        // dd(Input::get('teacher_id'));

        foreach(Input::get('teacher_id') as $teacher_id)
        {
            ClassInvitation::firstOrCreate([
                'class_id' => $class,
                'teacher_id' => $teacher_id
            ]);
        }

        flash()->success('Teachers invited successfully');

        return redirect()->back();
    }


    public function inviteAwardTeacher($class)
    {
        $rules = [
            'teacher_id' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }


        $invitation = ClassInvitation::firstOrCreate([
            'class_id' => $class,
            'teacher_id' => Input::get('teacher_id')
        ]);

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
        $ahamClass->status = 'open_for_enrollment';

        $ahamClass->save();

        foreach($ahamClass->timings as $timing)
        {
            $timing->teacher_id = $ahamClass->teacher_id;
            $timing->save();
        }
        
        \DB::commit();

        flash()->success('Awarded Successfully');

        // flash()->success('Teachers invited successfully');

        return redirect()->back();
    }

    public function inviteAllTeachers($id)
    {
        $ahamClass = AhamClass::find($id);

        $eligibleTeachers = TeacherHelper::eligibleTeachers($ahamClass);

        foreach($eligibleTeachers as $teacher_id => $teacher)
        {
            ClassInvitation::firstOrCreate([
                'class_id' => $ahamClass->id,
                'teacher_id' => $teacher_id
            ]);
        }

        flash()->success('All Teachers invited successfully');

        return redirect()->back();
    }

    public function deleteInvite($id)
    {
        $invitation = ClassInvitation::find($id);

        $invitation->delete();

        flash()->success('Invitation Deleted Successfully');

        return redirect()->back();
    }

    public function award($id)
    {
        $invitation = ClassInvitation::find($id);

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
        $ahamClass->status = 'open_for_enrollment';

        $ahamClass->save();

        foreach($ahamClass->timings as $timing)
        {
            $timing->teacher_id = $ahamClass->teacher_id;
            $timing->save();
        }

        \DB::commit();

        flash()->success('Awarded Successfully');

        return redirect()->back();

    }

}
