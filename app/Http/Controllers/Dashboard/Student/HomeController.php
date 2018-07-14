<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Aham\Managers\StudentClassesManager;

use Assets;
use Carbon;
use Input;

use Aham\Helpers\GraphHelper;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\ClassTiming;

class HomeController extends StudentDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        Assets::add("js/core/libraries/jquery.min.js");
        Assets::add("js/plugins/momentjs/moment.min.js");
        Assets::add("js/plugins/fullcalendar/js/fullcalendar.min.js");
        Assets::add("js/plugins/fullcalendar/css/fullcalendar.min.css");


        // $ahamClass = AhamClass::find(7);

        // event(new \Aham\Events\Teacher\GetFeedback($ahamClass));

    	$studentClassesManager = new StudentClassesManager($this->student);

    	//Classes In Session

    	$ongoingTimings  = $studentClassesManager->getOngoingTimings();
        $upcomingTimings = $studentClassesManager->getUpcomingTimings();
    	$completedTimings= $studentClassesManager->getCompletedTimings();

        $freeCredits = StudentCredits::where('student_id',$this->student->id)
                                    ->whereNotNull('coupon_id')
                                    ->sum('credits');

        if(is_null($freeCredits))
        {
            $freeCredits = 0;
        }

    	//Classes Upcoming

    	return view('dashboard.student.home.index',compact('upcomingTimings','ongoingTimings','completedTimings','freeCredits'));
    }

    public function assessment()
    {
        Assets::add("js/plugins/visualization/d3/d3.min.js");
        Assets::add("js/charts/d3/tree/tree_collapsible_aham.js");

        return view('dashboard.student.home.assessment');

    }

    public function classesForCalendar()
    {
        $classes = $enrollments = $this->student->enrollments->pluck('class_id')->toArray();

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        $timings = ClassTiming::with('ahamClass','classUnit','classroom')
                                ->whereIn('class_id',$classes)
                                ->whereBetween('date',[$from_date,$to_date])
                                ->whereNotIn('status',['cancelled'])
                                ->orderBy('date','asc')
                                ->orderBy('start_time','asc')
                                ->get();
        $events = [];

        foreach($timings as $timing)
        {
            $event = [];

            $event['title'] = $timing->classUnit->name.' '.$timing->ahamClass->topic_name;
            $event['class_id'] = $timing->ahamClass->id;
            $event['slug'] = route('student::class.show',$timing->ahamClass->code);

            $dt = Carbon::parse($timing->date->format('Y-m-d').' '.$timing->start_time);

            $event['start'] = $dt->toIso8601String();

            $dt = Carbon::parse($timing->date->format('Y-m-d').' '.$timing->end_time);

            $event['end'] = $dt->toIso8601String();

            if($timing->status == 'done')
            {
                $event['color'] = '#39c529';
            }

            $events[] = $event;
        }

        return $events;
    }

    public function graph()
    {
        $graphHelper = new GraphHelper();

        return $graphHelper->graph($this->student->user->username, 'student::courses.show');
    }

    public function snippet()
    {
        return view('dashboard.student.home.snippet');
    }

    public function demo()
    {
        return view('dashboard.student.home.demo-video');
    }
}
