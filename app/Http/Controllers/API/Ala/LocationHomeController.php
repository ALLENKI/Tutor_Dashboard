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

use Aham\Transformers\AhamClassUnitTransformer;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;

class LocationHomeController extends BaseController
{
    use Helpers;

    public function __construct()
    {
        parent::__construct();
    }

    public function studentsByDate()
    {
        
    }

    public function getTotalUnits($classTimings)
    {
        $totalUnits = 0;

        foreach($classTimings as $classTiming)
        {
            // if($classTiming->ahamClass->status != 'cancelled')
            // {
             if(!$classTiming->ahamClass->free){
                $totalUnits += $classTiming->ahamClass->enrollments->count()*$classTiming->ahamClass->charge_multiply;
            }
            // }
        }

        return $totalUnits;
    }

    public function getAnalytics($slug)
    {
        $date = Input::get('date');
        $date = Carbon::createFromTimestamp(strtotime($date));

        $location = Location::where('slug',$slug)->first();

        $analytics = [];

        $classTimings = ClassTiming::with('ahamClass.enrollments')
                                        ->whereHas('ahamClass', function($query)
                                        {
                                            $query
                                            ->where('status', '<>', 'cancelled');
                                        })
                                        ->where('location_id',$location->id)
                                        ->whereDate('date', '=', $date->toDateString())
                                        ->get();

        $analytic = [];
        $analytic['id'] = "units_today";
        $analytic['name'] = 'Units Today';
        $analytic['value'] = $this->getTotalUnits($classTimings);
        $analytics[] = $analytic;

        $classTimings = ClassTiming::with('ahamClass.enrollments')
                                        ->whereHas('ahamClass', function($query)
                                        {
                                            $query
                                            ->where('status', '<>', 'cancelled');
                                        })
                                        ->where('location_id',$location->id)
                                        ->whereDate('date', '=', $date->toDateString())
                                        ->get();

        // return $classTimings->pluck('class_id','id')->toArray();

        $analytic = [];
        $analytic['id'] = "classes_today";
        $analytic['name'] = 'Classes Today';
        $analytic['value'] = $classTimings->count();
        $analytics[] = $analytic;

        // dd(Carbon::parse('first day of this month')->toDateString());

        $currentDate = Input::get('date');
        $currentDate = Carbon::createFromTimestamp(strtotime($currentDate));


        $classTimings = ClassTiming::with('ahamClass.enrollments')
                                    ->whereHas('ahamClass', function($query)
                                    {
                                        $query
                                            ->where('status', '<>', 'cancelled');
                                    })
                                    ->where('location_id',$location->id)
                                    ->whereBetween('date',
                                    [
                                        $date->startOfMonth()->toDateString(),
                                        $currentDate->toDateString()
                                    ])
                                    ->get();


        $analytic = [];
        $analytic['name'] = 'Units This Month';
        $analytic['id'] = "units_this_month";
        $analytic['value'] = $this->getTotalUnits($classTimings);

        $analytics[] = $analytic;

        
        $date = Input::get('date');
        $date = Carbon::createFromTimestamp(strtotime($date));

        $classTimings = ClassTiming::with('ahamClass.enrollments')
                            ->where('location_id', '=', $location->id)
                            ->whereHas('ahamClass', function($query)
                            {
                                $query
                                ->where('status', '<>', 'cancelled');
                            })
                            // ->whereBetween('date',
                            // [
                            //     1800-01-01,
                            //     $date
                            // ])
                            ->whereDate('date','<=',$date)
                            ->get();

        $analytic = [];
        $analytic['id'] = "units_till_date";
        $analytic['name'] = 'Units Till Date';
        $analytic['value'] = $this->getTotalUnits($classTimings);


        $analytics[] = $analytic;

        return $analytics;
    }

    public function getClassInTimings($slug)
    {
        $location = Location::where('slug',$slug)->first();

        $date = Input::get('date');
        $fromTime = Input::get('from_time');
        $toTime = Input::get('to_time');

        $classTimings = ClassTiming::whereHas('ahamClass', function($query)
                            {
                                $query
                                ->where('status', '<>', 'cancelled');
                            })
                            ->where('location_id',$location->id)
                            ->whereDate('date', '=', $date)
                            ->whereBetween('start_time',[$fromTime,$toTime])
                            ->orderBy('start_time','asc')
                            ->get();

        return $this->response()->collection($classTimings,new AhamClassUnitTransformer);
    }

}
