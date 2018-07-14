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

class GuestSeriesLevelController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createModal($series)
    {
        $guestSeries = GuestSeries::find($series);

        return view('backend.classes_mgmt.guest_series.create_level',compact('guestSeries'));

    }


    public function storeModal($series)
    {
        $guestSeries = GuestSeries::find($series);

        $level = new GuestSeriesLevel(['name' => Input::get('name')]);

        $guestSeries->levels()->save($level);

        return \Response::json(array(
            'success' => true,
            'errors' => [['Level added successfully']]
        ), 200);

    }

    public function editModal($level)
    {
        $guestSeriesLevel = GuestSeriesLevel::find($level);

        return view('backend.classes_mgmt.guest_series.edit_level',compact('guestSeriesLevel'));

    }


    public function updateModal($level)
    {
        $guestSeriesLevel = GuestSeriesLevel::find($level);

        $guestSeriesLevel->fill([
            'name' => Input::get('name'),
            'description' => Input::get('level_description'),
        ]);

        if(Input::has('enrollment_cutoff'))
        {
            $enrollment_cutoff = Input::get('enrollment_cutoff');

            $enrollment_cutoff = Carbon::createFromTimestamp(strtotime($enrollment_cutoff));

            $guestSeriesLevel->enrollment_cutoff = $enrollment_cutoff;
        }

        $guestSeriesLevel->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Level added successfully']]

        ), 200);

    }

    public function deleteLevel($level)
    {
        $guestSeriesLevel = GuestSeriesLevel::find($level);

        $guestSeriesLevel->delete();

        return redirect()->back();
    }
}
