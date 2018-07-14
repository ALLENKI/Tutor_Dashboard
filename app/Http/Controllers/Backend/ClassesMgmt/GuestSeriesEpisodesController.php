<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\City;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\GuestSeries;
use Aham\Models\SQL\GuestSeriesLevel;
use Aham\Models\SQL\GuestSeriesEpisode;
use Aham\Models\SQL\UserEnrollment;

use Aham\Interactions\ClassSchedule;
use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

use Aham\Managers\ClassStatusManager;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;
use Artisan;

class GuestSeriesEpisodesController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createModal($level)
    {
        $guestSeriesLevel = GuestSeriesLevel::find($level);

        $topics = Topic::topic()
                        ->active()
                        ->orderBy('name','asc')
                        ->pluck('name','id');

        return view('backend.classes_mgmt.guest_series.create_episode',compact('guestSeriesLevel','topics'));

    }


    public function storeModal($level)
    {
        // dd(Input::all());

        $guestSeriesLevel = GuestSeriesLevel::find($level);

        $guestSeries = $guestSeriesLevel->series;

        $data = [
            'name' => Input::get('name'),
            'topic_id' => Input::get('topic_id'),
            'time_summary' => Input::get('time_summary'),
            'date_summary' => Input::get('date_summary'),
            'enrollment_limit' => Input::get('enrollment_limit'),
            'series_id' => $guestSeries->id,
            'level_id' => $guestSeriesLevel->id,
            'location_id' => $guestSeries->location_id
        ];

        // dd($data);

        $episode = new GuestSeriesEpisode($data);

        $episode->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Episode added successfully']]

        ), 200);

    }


    public function createRerunModal($episode)
    {
        $originalEpisode = GuestSeriesEpisode::find($episode);

        $topics = Topic::topic()
                        ->active()
                        ->orderBy('name','asc')
                        ->pluck('name','id');

        return view('backend.classes_mgmt.guest_series.create_rerun',compact('originalEpisode','topics'));

    }

    public function storeRerunModal($episode)
    {
        // dd(Input::all());

        $originalEpisode = GuestSeriesEpisode::find($episode);

        $guestSeriesLevel = $originalEpisode->level;

        $guestSeries = $guestSeriesLevel->series;

        $data = [
            'name' => Input::get('name'),
            'topic_id' => Input::get('topic_id'),
            'series_id' => $guestSeries->id,
            'level_id' => $guestSeriesLevel->id,
            'location_id' => $guestSeries->location_id,
            'repeat_of' => $originalEpisode->id,
            'time_summary' => Input::get('time_summary'),
            'date_summary' => Input::get('date_summary'),
        ];

        // dd($data);

        $episode = new GuestSeriesEpisode($data);

        $episode->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Episode added successfully']]

        ), 200);

    }

    public function editModal($episode)
    {
        $originalEpisode = GuestSeriesEpisode::find($episode);

        $topics = Topic::topic()
                        ->active()
                        ->orderBy('name','asc')
                        ->pluck('name','id');


        return view('backend.classes_mgmt.guest_series.edit_episode',compact('originalEpisode','topics'));
    }


    public function updateModal($episode)
    {
        $originalEpisode = GuestSeriesEpisode::find($episode);

        $guestSeriesLevel = $originalEpisode->level;

        $guestSeries = $guestSeriesLevel->series;

        $data = [
            'name' => Input::get('name'),
            'topic_id' => Input::get('topic_id'),
            'series_id' => $guestSeries->id,
            'level_id' => $guestSeriesLevel->id,
            'location_id' => $guestSeries->location_id,
            'repeat_of' => $originalEpisode->id,
            'time_summary' => Input::get('time_summary'),
            'date_summary' => Input::get('date_summary'),
            'enrollment_limit' => Input::get('enrollment_limit'),
        ];

        if(Input::has('enrollment_cutoff'))
        {
            $enrollment_cutoff = Input::get('enrollment_cutoff');

            $enrollment_cutoff = Carbon::createFromTimestamp(strtotime($enrollment_cutoff));

            $data['enrollment_cutoff'] = $enrollment_cutoff;
        }

        // dd($data);

        $originalEpisode->fill($data);

        $originalEpisode->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Episode added successfully']]

        ), 200);

    }


    public function cancelEpisodeModal($episode)
    {
        $guestSeriesEpisode = GuestSeriesEpisode::find($episode);

        $series = $guestSeriesEpisode->series;

        $level = $guestSeriesEpisode->level;

        $canBeCancelled = true;

        if($series->enrollment_restriction == 'restrict_by_episode')
        {
            $enrolled = UserEnrollment::whereIn('episode_id',[$episode])->count();
        }

        if($series->enrollment_restriction == 'restrict_by_level')
        {
            $enrolled = UserEnrollment::whereIn('episode_id',[$level->id])->count();
        }

        if($enrolled > 0)
        {
            $canBeCancelled = false;
        }
        
        return view('backend.classes_mgmt.guest_series._cancel_episode_modal',compact('guestSeriesEpisode','canBeCancelled'));
    }

    public function cancelEpisode($episode)
    {
        $guestSeriesEpisode = GuestSeriesEpisode::find($episode);

        $rules = [
            'confirm' => 'required|in:DELETE'
        ];

        $messages = [
            'confirm.in' => 'Please type DELETE to confirm'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $guestSeriesEpisode->delete();

        // ClassStatusManager::giveBackCredits($ahamClass);

        // event(new \Aham\Events\AdminCancelledClass($ahamClass));

        return \Response::json(array(
            'success' => true,
            'errors' => [['Episode successfully deleted']]
        ), 200);

    }



    public function enrolled($episode)
    {
        $episode = GuestSeriesEpisode::find($episode);

        return view('backend.classes_mgmt.guest_series.enrolled',compact('episode'));
    }
}
