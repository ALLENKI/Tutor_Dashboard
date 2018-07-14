<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Slot;
use Aham\Models\SQL\StudentAssessment;

use Aham\Models\SQL\GuestSeries;
use Aham\Models\SQL\GuestSeriesLevel;
use Aham\Models\SQL\GuestSeriesEpisode;
use Aham\Models\SQL\EpisodeTiming;

use Aham\Interactions\ClassSchedule;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;
use DB;

class EpisodeScheduleController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function scheduleModal($episode)
    {
        $episode = GuestSeriesEpisode::find($episode);

        $startDate = "moment().add(1, 'hour')";

        return view('backend.classes_mgmt.guest_series._schedule_modal',compact('episode','startDate'));
    }

    public function scheduleModalPost($episode)
    {
        // dd(Input::all());

        $rules = [
            'unit_date' => 'required',
            'unit_slots' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $episode = GuestSeriesEpisode::find($episode);

        $date = Carbon::createFromTimestamp(strtotime(Input::get('unit_date')));

        $slots = Input::get('unit_slots');

        \DB::beginTransaction();

        $episode->timings()->delete();

        foreach($slots as $slot)
        {
            $slot = trim($slot,'"');
            $slot = trim($slot,"'");

            $dbSlot = Slot::find($slot);

            $timing = EpisodeTiming::create([
                'series_id' => $episode->series->id,
                'episode_id' => $episode->id,
            ]);

            $timing->slot_id = $slot;

            $start_date = $date->format('d-m-Y').' '.$dbSlot->start_time;

            $start_date = Carbon::createFromTimestamp(strtotime($start_date));

            $timing->start_time = $dbSlot->start_time;
            $timing->end_time = $dbSlot->end_time;

            $timing->date = $date;
            $timing->location_id = $episode->location->id;

            $timing->save();

        }

        \DB::commit();

        // dd($episode->timings);

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
