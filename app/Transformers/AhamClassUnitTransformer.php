<?php namespace Aham\Transformers;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\StudentEnrollmentUnit;
use League\Fractal;
use Dingo\Api\Routing\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class AhamClassUnitTransformer extends Fractal\TransformerAbstract
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

	public function transform(ClassTiming $classTiming)
	{
		$data = [];

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

		$data['classroom'] = 'NA';
		if($classTiming->classroom)
		{
			$data['classroom'] = $classTiming->classroom->name;
		}

		$data['enrolled'] = $classTiming->enrolledLearners->count();

		if($classTiming->teacher)
		{
			$data['teacher'] = $classTiming->teacher->user->name;
		}

	    return $data;
	}

	public function includeTeacher(ClassTiming $classTiming)
	{
		if(is_null($classTiming->teacher))
		{
			return null;
		}

        return $this->item($classTiming->teacher, new TeacherTransformer);
	}


	public function includeAhamClass(ClassTiming $classTiming)
	{
        return $this->item($classTiming->ahamClass, new AhamClassTransformer);
	}

	public function includeClassUnit(ClassTiming $classTiming)
	{
        return $this->item($classTiming->classUnit, new ClassUnitTransformer);
	}
}
