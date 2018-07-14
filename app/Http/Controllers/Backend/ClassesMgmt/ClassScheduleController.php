<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Slot;
use Aham\Models\SQL\StudentAssessment;

use Aham\Interactions\ClassSchedule;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;
use DB;

class ClassScheduleController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function scheduleModal($class, $unit)
    {
        $ahamClass = AhamClass::find($class);

        $dateRange = ClassSchedule::getDateRange($ahamClass, $unit);

        if(!is_null($dateRange['startDate']))
        {
            $startDate = 'moment("'.$dateRange['startDate']->format('Y-m-d').'")';
        }
        else
        {
            $startDate = "moment().add(1, 'hour')";
        }

        if(!is_null($dateRange['endDate']))
        {
            $endDate = 'moment("'.$dateRange['endDate']->format('Y-m-d').'")';
        }
        else
        {
            $endDate = NULL;
        }

        // dd($startDate);

        // We can fix start date and end here

        return view('backend.classes_mgmt.classes._schedule_modal',compact('ahamClass','unit','startDate','endDate'));
    }

    public function scheduleModalPost($class, $unit)
    {

        $sessions = [
            'early_morning' => strtotime('05:30:00'),
            'morning' => strtotime('07:30:00'),
            'afternoon' => strtotime('12:00:00'),
            'evening' => strtotime('16:00:00'),
            'late_evening' => strtotime('18:30:00'),
        ];

        $rules = [
            'unit_date' => 'required',
            'unit_slot' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $ahamClass = AhamClass::find($class);

        $firstUnit = $ahamClass->topic->units->first();

        $units = $ahamClass->topic->units->pluck('id')->toArray();

        $key = array_search($unit, $units);

        for($i = $key+1; $i < count($units); $i++)
        {
            $exists = ClassTiming::where([
                            'class_id' => $ahamClass->id,
                            'unit_id' => $units[$i],
                        ])->first();

            if($exists)
            {
                $exists->delete();
            }

        }


        $date = Carbon::createFromTimestamp(strtotime(Input::get('unit_date')));

        $slot = Input::get('unit_slot');
        $slot = trim($slot,'"');
        $slot = trim($slot,"'");


        // dd($sessions);

        $dbSlot = Slot::find($slot);

        $finalSession = '';
        $starttimeStamp = strtotime($dbSlot->start_time);

        foreach($sessions as $session => $timestamp)
        {
            if($starttimeStamp >= $timestamp)
            {
                $finalSession = $session;
            }
        }

        $classUnit = ClassUnit::where([
            'class_id' => $ahamClass->id, 
            'original_unit_id' => $unit])
            ->first();

        $timing = ClassTiming::firstOrCreate([
            'class_id' => $ahamClass->id,
            'unit_id' => $unit,
            'class_unit_id' => $classUnit->id,
            'of_id' => $ahamClass->id,
            'of_type' => get_class($ahamClass)
        ]);

        $timing->slot_id = $slot;

        $start_date = $date->format('d-m-Y').' '.$dbSlot->start_time;

        $start_date = Carbon::createFromTimestamp(strtotime($start_date));

        $timing->start_time = $dbSlot->start_time;
        $timing->end_time = $dbSlot->end_time;
        $timing->session = $finalSession;

        // Determine session and assign

        $timing->date = $date;
        $timing->location_id = $ahamClass->location->id;

        $timing->save();

        if($firstUnit->id == $unit)
        {
            $ahamClass->start_date = $start_date;
            $ahamClass->schedule_cutoff = $start_date;
            $ahamClass->save();
        }

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully scheduled']]

        ), 200);

    }

    public function openForEnrollment($id)
    {
        $ahamClass = AhamClass::find($id);

        $ahamClass->enrollment_cutoff = $ahamClass->start_date->addMinutes(30);
        $ahamClass->status = 'open_for_enrollment';
        $ahamClass->save();

        return redirect()->back();
    }

    public function schedule($id)
    {
        $ahamClass = AhamClass::find($id);

        $ahamClass->status = 'scheduled';
        $ahamClass->save();

        return redirect()->back();
    }

    public function complete($id)
    {
        $ahamClass = AhamClass::with('topic','enrollments.student')->find($id);

        // dd('completed');

        $enrollmentCount = $ahamClass->enrollments->count();
        $enrollmentCount = $enrollmentCount >  4 ? $enrollmentCount : 4;

        if($ahamClass->free)
        {
            $enrollmentCount = 4;
        }

        $totalWorth = $enrollmentCount*$ahamClass->classUnits->count()*1000;

        $amount = ($ahamClass->commission/100)*$totalWorth;

        if($ahamClass->no_tutor_comp)
        {
            $amount = 0;
        }

        $teacher = $ahamClass->teacher;

        \DB::beginTransaction();

        $teacher->earnings = $teacher->earnings + $amount;
        $teacher->save();

        $ahamClass->status = 'completed';
        $ahamClass->completed_at = Carbon::now();
        $ahamClass->teacher_amount = $amount;
        $ahamClass->save();

        \DB::commit();

        foreach($ahamClass->enrollments as $enrollment)
        {
            if( $enrollment->feedback != 'ghost' && $enrollment->feedback != 'absent')
            {
               $assessment = StudentAssessment::firstOrCreate([
                    'student_id' => $enrollment->student->id,
                    'topic_id' => $ahamClass->topic->id
                ]);

                $assessment->mode = 'aham_class';
                $assessment->save();
            }

        }

        event(new \Aham\Events\ClassCompleted($ahamClass));

        return redirect()->back();
    }

    public function session($id)
    {
        $ahamClass = AhamClass::find($id);

        $ahamClass->status = 'in_session';
        $ahamClass->save();

        return redirect()->back();
    }

    public function reInitiate($id)
    {
        $ahamClass = AhamClass::find($id);

        foreach($ahamClass->enrollments as $enrollment)
        {
            $enrollment->delete();
        }

        foreach($ahamClass->invitations as $invitation)
        {
            $invitation->delete();
        }

        foreach($ahamClass->timings as $timing)
        {
            $timing->delete();
        }

        $ahamClass->status = 'initiated';
        $ahamClass->teacher_id = NULL;

        $ahamClass->save();

        return redirect()->back();
    }

    public function enrollmentCutoffModal($class)
    {
        $ahamClass = AhamClass::find($class);

        $endDate = 'moment()';
        $startDate = 'moment()';

        $endDate = 'moment("'.$ahamClass->start_date->format('Y-m-d H:i').'")';

        return view('backend.classes_mgmt.classes._enrollment_cutoff_modal',compact('ahamClass','startDate','endDate'));
    }

    public function enrollmentCutoffModalPost($class)
    {
        $rules = [
            'enrollment_cutoff' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $ahamClass = AhamClass::find($class);

        $enrollment_cutoff = Input::get('enrollment_cutoff');

        $enrollment_cutoff = Carbon::createFromTimestamp(strtotime($enrollment_cutoff));

        $ahamClass->enrollment_cutoff = $enrollment_cutoff;
        $ahamClass->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully scheduled']]

        ), 200);
    }


    public function scheduleCutoffModal($class)
    {
        $ahamClass = AhamClass::find($class);

        $endDate = 'moment()';
        $startDate = 'moment()';

        $endDate = 'moment("'.$ahamClass->start_date->format('Y-m-d H:i').'")';

        return view('backend.classes_mgmt.classes._schedule_cutoff_modal',compact('ahamClass','startDate','endDate'));
    }

    public function scheduleCutoffModalPost($class)
    {
        $rules = [
            'schedule_cutoff' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $ahamClass = AhamClass::find($class);

        $schedule_cutoff = Input::get('schedule_cutoff');

        $schedule_cutoff = Carbon::createFromTimestamp(strtotime($schedule_cutoff));

        $ahamClass->schedule_cutoff = $schedule_cutoff;
        $ahamClass->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully scheduled']]

        ), 200);
    }

    /**** Classroom Assignment *****/

    public function assignClassroomModal($class, $unit)
    {
        $ahamClass = AhamClass::find($class);

        $classrooms = ClassSchedule::getAvailableClassrooms($ahamClass, $unit);

        // dd($classrooms);

        return view('backend.classes_mgmt.classes._assigncl_modal',compact('ahamClass','unit','classrooms'));
    }

    public function assignClassroom($class, $unit)
    {
        $rules = [
            'classroom_id' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $timing = timing($unit, $class);
        $timing->classroom_id = Input::get('classroom_id');
        $timing->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully scheduled']]

        ), 200);
    }

}
