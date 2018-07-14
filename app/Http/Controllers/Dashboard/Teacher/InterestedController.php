<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Managers\TeacherClassesManager;

use Aham\Jobs\SendTeacherInterestedMail;

use Validator;
use Input;
use Assets;

use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TopicsLookup;


class InterestedController extends TeacherDashboardBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($id)
    {   
        $topic = Topic::where('id', $id)->first();
        
        return view('dashboard.teacher.interested.modal', compact('topic'));
        
    }


    public function post($id)
    {   
        $topic = Topic::find($id);

        $teacher = $this->teacher;

        flash()->success('Message sent successfully.');

        $this->dispatch( new SendTeacherInterestedMail($teacher, $topic) );
    }



}