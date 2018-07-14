<?php

namespace Aham\Http\Controllers\V2\HubDB;

use Aham\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\HubTopic;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\StudentEnrollmentUnit;
use Aham\Models\SQL\StudentInvitation;

use Aham\Transformers\AhamClassTransformer;
use Aham\Transformers\AhamClassUnitTransformer;
use Aham\Transformers\ClassTimingTransformer;

use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;

use Input;
use Carbon;

use League\Fractal\Manager;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {

        $user = Sentinel::getUser();
        $token = JWTAuth::fromUser($user);

        return view('hub-db.index', compact('user', 'token'));
    }

    public function locations()
    {
        $user = $this->auth->user();

        $accessibleLocations = $user->accessibleLocations('classes.manage');

        $locations = Location::with(
            [
                'city' => function ($query) {
                    return $query->addSelect(['name', 'id']);
                }
            ]
        )
        ->whereIn('id', $accessibleLocations)
        ->select(['id', 'name', 'slug', 'street_address', 'landmark', 'city_id','created_at'])
        ->get();

        return ['locations' => $locations];
    }

    public function getLocationDetail($slug)
    {
        $user = $this->auth->user();

        $accessibleLocations = $user->accessibleLocations('classes.manage');

        $location = Location::with(
            [
                'city' => function ($query) {
                    return $query->addSelect(['name', 'id']);
                }
            ]
        )
        ->where('slug', $slug)
        ->whereIn('id', $accessibleLocations)
        ->select(['id', 'name', 'slug', 'street_address', 'landmark', 'city_id'])
        ->first();

        return ['location' => $location];
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

    public function getAnalytics($slug)
    {
        $date = Input::get('date');
        $date = Carbon::createFromTimestamp(strtotime($date));

        $location = Location::where('slug',$slug)->first();

        $analytics = [];

        $studentEnrollment = StudentEnrollmentUnit::where('location_id', '=', $location->id)
                                                ->where('status','<>','cancelled')
                                                ->whereDate('date', '=', $date->toDateString())
                                                ->get();

        $analytic = [];
        $analytic['id'] = "units_today";
        $analytic['name'] = 'Units Today';
        $analytic['value'] = $this->getTotalUnitsCount($studentEnrollment);
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

        $studentEnrollment = StudentEnrollmentUnit::where('location_id', '=', $location->id)
                                                ->where('status','<>','cancelled')
                                                ->whereBetween('date',
                                                [
                                                    $date->startOfMonth()->toDateString(),
                                                    $currentDate
                                                ])
                                                ->get();

        $analytic = [];
        $analytic['name'] = 'Units This Month';
        $analytic['id'] = "units_this_month";
        $analytic['value'] = $this->getTotalUnitsCount($studentEnrollment);

        $analytics[] = $analytic;

        $date = Input::get('date');
        $date = Carbon::createFromTimestamp(strtotime($date));

        $enrolledUnits = StudentEnrollmentUnit::where('location_id', '=', $location->id)
                                            ->where('status','<>','cancelled')
                                            ->whereDate('date','<=',$date)
                                            ->get();

        $analytic = [];
        $analytic['id'] = "units_till_date";
        $analytic['name'] = 'Units Till Date';

        $analytic['value'] = $this->getTotalUnitsCount($enrolledUnits);

        $analytics[] = $analytic;

        $ids = AhamClass::where('location_id',$location->id)->pluck('id')->toArray();

        $learnerInvitations = StudentInvitation::whereIn('class_id',$ids)
                                                ->count();

        $analytic = [];
    
        $analytic['learnerInvitations'][] = ['name' => 'Invites Sent', 'value' => $learnerInvitations];

        // $analytics[] = $analytic;

        $learnerInvitations = StudentInvitation::whereIn('class_id',$ids)
                                                ->where('status','enrolled')
                                                ->count();

        $analytic['learnerInvitations'][] = ['name' => 'Invites Accepted','value' => $learnerInvitations];
        $analytics[] = $analytic;

        return $analytics;
    }
  
   public function getTotalUnitsCount($enrolledUnits)
   {
       $unitTillDate = 0;

       foreach ($enrolledUnits as $enrolledUnit) {

            $unitTillDate += $enrolledUnit->credits_used;
       }

       return number_format((float)$unitTillDate, 2, '.', '');
   }

   public function getHubTopics($hub)
   {
        $hub = Location::where('slug',$hub)->first();

        $topics = HubTopic::where([
                    'of_type' => Topic::class,
                    'hub_id' => $hub->id
                ])->pluck('of_id')->toArray();

        $topics = Topic::with('children.children.children','units')
                        ->whereIn('id',$topics)
                        ->get();        

        $nodes = getNodes($topics);

        return Topic::with('units')->whereIn('id',$nodes->pluck('id')->toArray())->where('status','active')->where('approve',true)->get();
        // return ['tree' => $nodes, 'list' => $topics];
   }
    public function HubColors()
    {
        $color = Location::select('name','color')->where('active',1)->get();
        return $color;

    }
  
}
