<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Managers\TeacherClassesManager;

use Aham\Helpers\TeacherGraphHelper;

use Assets;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\File;

class HomeController extends TeacherDashboardBaseController
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

    	$teacherClassesManager = new TeacherClassesManager($this->teacher);

        $upcomingClasses = $teacherClassesManager->getUpcomingClasses();
        $newInvitations = $teacherClassesManager->getPendingInvitations();
        $ongoingClasses = $teacherClassesManager->getInSessionClasses();
        $feedbackClasses = $teacherClassesManager->getFeedbackClasses();
        
        // Calculate projected amount - open_for_enrollment, scheduled, in_session, get_feedback, got_feedback
// 
        $projectedAmount = $teacherClassesManager->getProjectedAmount();

    	return view('dashboard.teacher.home.index',compact('upcomingClasses','newInvitations','ongoingClasses','feedbackClasses','projectedAmount'));
    }


    public function classesForCalendar()
    {
        $classes = AhamClass::whereNotIn('status',['cancelled'])
                                    ->where('teacher_id',$this->teacher->id)
                                    ->orderBy('start_date','asc')
                                    ->pluck('id')
                                    ->toArray();

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        $cancelledClasses = AhamClass::where('status','cancelled')->pluck('id')->toArray();

        $timings = ClassTiming::with('ahamClass','classUnit','classroom')
                    ->where('teacher_id',$this->teacher->id)
                    ->whereBetween('date',[$from_date,$to_date])
                    ->whereNotIn('status',['cancelled'])
                    ->whereNotIn('class_id',$cancelledClasses)
                    ->orderBy('date','asc')
                    ->orderBy('start_time','asc')
                    ->get();

        $events = [];

        foreach($timings as $timing)
        {
            $event = [];

            $event['title'] = $timing->classUnit->name.' '.$timing->ahamClass->topic_name;
            $event['class_id'] = $timing->ahamClass->id;
            $event['slug'] = route('teacher::class.show',$timing->ahamClass->code);

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


    public function certification()
    {
        Assets::add("js/plugins/visualization/d3/d3.min.js");
        Assets::add("js/charts/d3/tree/tree_collapsible_aham.js");

        return view('dashboard.teacher.home.certification');
    }
    
    public function graph()
    {
        $graphHelper = new TeacherGraphHelper();

        return $graphHelper->graph($this->teacher->user->username, 'teacher::courses.show');
    }

    public function snippet()
    {
        return view('dashboard.teacher.home.snippet');
    }
}
