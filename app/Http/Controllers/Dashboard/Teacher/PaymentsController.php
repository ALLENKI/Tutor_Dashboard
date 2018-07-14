<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Managers\TeacherClassesManager;


use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\TeacherEarning;
use Aham\Helpers\TeacherHelper;
use Aham\Helpers\AssessmentHelper;

use Aham\Helpers\TeacherGraphHelper;

use Input;
use Sentinel;
use Validator;
use Assets;
use DB;
use Carbon;

class PaymentsController extends TeacherDashboardBaseController 
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $teacher = $this->teacher;

        // dd($teacher->allEarnings);
        $teacherClassesManager = new TeacherClassesManager($this->teacher);

        $projectedAmount = $teacherClassesManager->getProjectedAmount();

        return view('dashboard.teacher.payments.index',compact('teacher','projectedAmount'));
    }
 

}
