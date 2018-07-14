<?php namespace Aham\Transformers;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\StudentEnrollmentUnit;
use League\Fractal;
use Dingo\Api\Routing\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;
use Carbon;

class AhamEnrollmentUnitTransformer extends Fractal\TransformerAbstract
{
    use Helpers;

	protected $availableIncludes = [
        'teacher',
        'ahamClass',
        'classUnit'
    ];

    protected $defaultIncludes = [
        'teacher',
        'ahamClass',
        'classUnit'
    ];

	public function transform(StudentEnrollmentUnit $enrollmentUnit)
	{
		$data = [];

		$classTiming = $enrollmentUnit->classTiming;

		$data['enrollment_unit_status'] = $enrollmentUnit->status;
		$data['enrollment_unit_id'] = $enrollmentUnit->id;

		$data['unit_name'] = $classTiming->classUnit->name;

        if($classTiming->teacher){
            $data['unit_teacher'] = $classTiming->teacher->user->name;
        }

		$data['class_id'] = $classTiming->class_id;
		$data['start_time'] = $classTiming->start_time;
		$data['date'] = $classTiming->date;
		$data['end_time'] = $classTiming->end_time;
		$data['topic_name'] = $classTiming->ahamClass->topic->name;
		$data['class_code'] = $classTiming->ahamClass->code;
		// $data['topic'] = $classTiming->ahamClass->topic;
		$data['status'] = $classTiming->ahamClass->status;
		$data['minimum_enrollment'] = $classTiming->ahamClass->minimum_enrollment;
		$data['maximum_enrollment'] = $classTiming->ahamClass->maximum_enrollment;
		$data['enrolled'] = $classTiming->ahamClass->enrolled;
		$data['classroom'] = 'NA';
		if($classTiming->classroom)
		{
			$data['classroom'] = $classTiming->classroom->name;
		}

		$data['rating_given'] = $enrollmentUnit->enrollment->rating_given ? true : false;

		$data['can_cancel'] = false;
		$unitDate = Carbon::createFromTimestamp(strtotime($classTiming->date->format('Y-m-d').' '.$classTiming->start_time));
		$data['can_cancel'] = $unitDate->subHours(16)->isPast() ? false : true;

	    return $data;
	}

	public function includeTeacher(StudentEnrollmentUnit $enrollmentUnit)
	{
		$classTiming = $enrollmentUnit->classTiming;

		if(is_null($classTiming->teacher))
		{
			return null;
		}

        return $this->item($classTiming->teacher, new TeacherTransformer);
	}


	public function includeAhamClass(StudentEnrollmentUnit $classTiming)
	{
        return $this->item($classTiming->ahamClass, new AhamClassTransformer);
	}

	public function includeClassUnit(StudentEnrollmentUnit $classTiming)
	{
        return $this->item($classTiming->classUnit, new ClassUnitTransformer);
	}
}
