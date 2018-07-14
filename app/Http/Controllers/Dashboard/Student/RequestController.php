<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Aham\Jobs\SendStudentRequestMail;

use Validator;
use Input;

use Aham\Models\SQL\AhamClass;

use Aham\Models\SQL\Topic;

use Aham\Managers\EnrollmentManager;

class RequestController extends StudentDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($id)
    {
        $topic = Topic::where('id', $id)->first();
        
        return view('dashboard.student.request.modal', compact('topic'));
    }

    public function post($id)
    {
        $topic = Topic::find($id);

        $student = $this->student;

        $preferred_time = Input::get('preferred_time');

        $preferred_day = Input::get('preferred_day');

        $preferred_period = Input::get('preferred_period');

        $your_message = Input::get('your_message');


        flash()->success('Message sent successfully.');

        $this->dispatch( new SendStudentRequestMail($student, $topic, $preferred_time, $preferred_day, $preferred_period, $your_message) );
    }

}