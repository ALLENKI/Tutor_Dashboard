<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Managers\TeacherClassesManager;

use Aham\Models\SQL\ClassInvitation;

use Aham\Helpers\TeacherHelper;

use Carbon\Carbon;

use Validator;
use Input;

class ClassesController extends TeacherDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function onGoing()
    {
    	$teacherClassesManager = new TeacherClassesManager($this->teacher);


        $ongoingClassesTimings = $teacherClassesManager->getOnGoingClassesTimings();

    	return view('dashboard.teacher.classes.on-going',compact('ongoingClassesTimings'));
    }

    public function upcoming()
    {
        $teacherClassesManager = new TeacherClassesManager($this->teacher);

        $upcomingClassesTimings = $teacherClassesManager->getUpcomingClassesTimings();

        return view('dashboard.teacher.classes.upcoming',compact('upcomingClassesTimings'));
    }

// removed on font end
    public function scheduled()
    {
        $teacherClassesManager = new TeacherClassesManager($this->teacher);

        $scheduledClasses = $teacherClassesManager->getScheduledClasses();

        return view('dashboard.teacher.classes.scheduled',compact('scheduledClasses'));
    }

    public function completed()
    {
        $teacherClassesManager = new TeacherClassesManager($this->teacher);

        $completedClassesTimings = $teacherClassesManager->getCompletedClassesTimings();

        return view('dashboard.teacher.classes.completed',compact('completedClassesTimings'));
    }

    public function calendar(){
        return 'calendar';
    }

}
