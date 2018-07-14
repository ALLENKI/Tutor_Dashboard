<?php

namespace Aham\Http\Controllers\V2\LearnerDB;

use Aham\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Input;
use Aham\TransformersV2\StudentEnrollmentUnitTransformer;
use Aham\Models\SQL\Student;

class CalendarController extends BaseController
{
    public $student;
    public $calendar;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        $this->student = $this->auth->user()->student;

        if (Input::has('student_id')) {
            $this->student = Student::find(Input::get('student_id'));
        }

        $this->calendar = new \Aham\LearnerEnrollment\Calendar($this->student);
    }

    public function enrolledUnits()
    {
        $this->init();

        $filter = Input::get('filter', 'completed');

        switch ($filter) {
            case 'today':
                $enrolledUnits = $this->calendar->getTodayUnits();
                // $enrolledUnits = $this->calendar->getUnitsOnADate('2018-01-07');
                break;

            case 'upcoming':
                $enrolledUnits = $this->calendar->getUpcomingUnits();
                break;

            case 'completed':
                $enrolledUnits = $this->calendar->getCompletedUnits();
                break;
        }

        $paginator = $enrolledUnits;
        $enrolledUnits = $paginator->getCollection();


        $resource = new \League\Fractal\Resource\Collection(
            $enrolledUnits,
            new StudentEnrollmentUnitTransformer
        );

        $resource->setPaginator(
            new \League\Fractal\Pagination\IlluminatePaginatorAdapter($paginator)
        );

        $manager = new \League\Fractal\Manager();
        $manager->setSerializer(new \League\Fractal\Serializer\ArraySerializer());
        $enrolledUnits = $manager->createData($resource)->toArray();

        return $enrolledUnits;
    }
}
