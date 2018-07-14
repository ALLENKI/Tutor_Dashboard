<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Validator;
use Input;
use Illuminate\Http\Request;
use Assets;

use Aham\Managers\StudentClassesManager;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\StudentInvitation;
use Aham\Models\SQL\StudentEnrollment;
use Carbon\Carbon;

class ClassesController extends StudentDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();

        Assets::add('js/plugins/star-rating/star-rating.min.css');
        Assets::add('js/plugins/star-rating/star-rating.min.js');
    }

    public function enrolled()
    {
        $studentClassesManager = new StudentClassesManager($this->student);

        $enrolledClasses = $studentClassesManager->getEnrolledClasses();

        return view('dashboard.student.classes.enrolled',compact('enrolledClasses'));
    }

    public function onGoing()
    {
        $studentClassesManager = new StudentClassesManager($this->student);

        $ongoingTimings = $studentClassesManager->getOngoingTimings();

        return view('dashboard.student.classes.on-going',compact('ongoingTimings'));
    }

    public function upcoming()
    {
        $studentClassesManager = new StudentClassesManager($this->student);

        $upcomingTimings = $studentClassesManager->getUpcomingTimings();

        return view('dashboard.student.classes.upcoming',compact('upcomingTimings'));
    }

    public function completed()
    {
        $studentClassesManager = new StudentClassesManager($this->student);

        $completedTimings = $studentClassesManager->getCompletedTimings();

        return view('dashboard.student.classes.completed',compact('completedTimings'));
    }


    public function browse(Request $request)
    {
        // get all the classes student enrolled to.
        $enrollments = $this->student->enrollments->pluck('class_id')->toArray();

        // build a queryBilder where student is not enrolled to.
        $classes = AhamClass::with('timings')
                            ->whereIn('status',['open_for_enrollment','scheduled'])
                            ->whereNotIn('id',$enrollments)
                            ->whereHas('timings',function($q){
                                    $q->whereDate('date','>=',Carbon::now()->format('Y-m-d'));
                            });

        if(Input::has('q') && Input::get('q') != '') {
            $classes->whereHas('topic',function($query){
                $query->where('name','like','%'.Input::get('q').'%');
            });
        }

        if(Input::has('sortBy')) {
            $classes->orderBy('created_at',Input::get('sortBy'));
        }

        $classes = $classes->paginate(10);

        view()->share('classes', $classes);

        return view('dashboard.student.classes.browse',compact('classes','enrollments'));
    }

    public function recommended()
    {
        $invitations = $this->student->invitations->pluck('class_id')->toArray();

        $enrollments = $this->student->enrollments->pluck('class_id')->toArray();

        // dd($enrollments);

        $classes = AhamClass::whereIn('status',['open_for_enrollment','scheduled'])
                            ->whereIn('id',$invitations)
                            ->whereNotIn('id',$enrollments)
                            ->whereDate('start_date','>=',Carbon::today())
                            ->paginate(10);
                            

        return view('dashboard.student.classes.recommended',compact('classes','invitations','enrollments'));
    }

    public function markAsNotInterested($id)
    {
        $student = $this->student;

        $invitation = StudentInvitation::where([
            'class_id' => $id,
            'student_id' => $student->id,
        ])->first();

        if(!is_null($invitation))
        {
            $invitation->status = 'not_interested';
            $invitation->save();
        }

        return redirect()->back();
    }

}
