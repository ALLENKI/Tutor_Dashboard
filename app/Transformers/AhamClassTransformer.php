<?php namespace Aham\Transformers;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\StudentEnrollment;
use League\Fractal;
use Dingo\Api\Routing\Helpers;
use Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class AhamClassTransformer extends Fractal\TransformerAbstract
{
    use Helpers;

	protected $availableIncludes = [
        'topic',
        'teacher',
        'classUnits',
        'enrollments'
    ];

    protected $defaultIncludes = [
        'topic',
        'teacher',
        'classUnits',
        'enrollments'
    ];


	public function transform(AhamClass $ahamClass)
	{
		$data = [];

		$data['id'] = $ahamClass->id;
		$data['code'] = $ahamClass->code;
		$data['name'] = $ahamClass->name;
		$data['topic_name'] = $ahamClass->topic_name;
		$data['teacher_id'] = $ahamClass->teacher_id;
		$data['unit_duration'] = $ahamClass->unit_duration;
		$data['payment_finalized'] = $ahamClass->payment_finalized;

		if($ahamClass->type == 'single_group_class') {
				$data['type'] = 'Course';
				$data['course_id'] = $ahamClass->group_class_id;
				$data['course'] = AhamClass::find($ahamClass->group_class_id);
		}

		if(!is_null($ahamClass->source_class_id)) {
			$data['repeat_class_id'] = $ahamClass->source_class_id;
		}

		$data['teacher_assigned'] = 'NO';

		if($ahamClass->teacher)
		{
			$data['teacher_assigned'] = 'YES';
		}

		$data['status'] = $ahamClass->status;
		$data['minimum_enrollment'] = $ahamClass->minimum_enrollment;
		$data['maximum_enrollment'] = $ahamClass->maximum_enrollment;
		$data['enrolled'] = $ahamClass->enrollments->count();
		$data['commission'] = $ahamClass->commission;
		$data['free'] = $ahamClass->free;
		$data['auto_cancel'] = $ahamClass->auto_cancel;
		$data['no_tutor_comp'] = $ahamClass->no_tutor_comp;
		$data['cancellation_reason'] = $ahamClass->cancellation_reason;
		$data['scheduling_rule_id'] = $ahamClass->scheduling_rule_id;
		$data['charge_multiply'] = $ahamClass->charge_multiply;
		$data['invitations'] = $ahamClass->invitations->count();
		$data['tutor_feedback'] = $ahamClass->tutor_feedback ? true : false;

		$data['chat_enable'] = $ahamClass->chat_enable ? true : false;
		
		if($ahamClass->schedulingRule)
		{
			$data['division'] = $ahamClass->schedulingRule->division;
		}
		
		$data['maximum_days'] = $ahamClass->maximum_days;

		$data['can_enroll'] = false;

		if($ahamClass->start_date)
		{
			$data['start_date_dmy'] = $ahamClass->start_date->format('d-m-Y');
			$data['start_date'] = $ahamClass->start_date->format('jS M Y H:i A');
		}
		

		if($ahamClass->timings->count())
		{
			$firstClassTiming = $ahamClass->timings->first();
			$startDate = Carbon::createFromTimestamp(strtotime($firstClassTiming->date->format('Y-m-d').' '.$firstClassTiming->start_time));
			$data['start_date_dmy'] = $startDate->format('d-m-Y');
			$data['start_date'] = $startDate->format('jS M Y h:i A');
			$data['can_enroll'] = $startDate->subHours(6)->isPast() ? false : true;
		}

		$data['location'] = $ahamClass->location->present()->address;
		$data['locationf'] = $ahamClass->location;
		$data['latitude'] = $ahamClass->location->latitude;
		$data['longitude'] = $ahamClass->location->longitude;

		$user = $this->auth->user();

		if(!is_null($user) && $user->teacher)
		{
			$data['invitation'] = ClassInvitation::where([
					'class_id' => $ahamClass->id,
					'teacher_id' => $user->teacher->id
				])->first();
		}


		$timings = [];
		$teachers = [];
		$added_teachers = [];

		// dd($ahamClass->timings);

		foreach($ahamClass->timings as $timing)
		{
			$insert_timing = [];

			$insert_timing['unit'] = $timing->classUnit->name;
			$insert_timing['tutor_payment'] = $timing->tutor_payment ?  $timing->tutor_payment : 0;
			$insert_timing['tutor_payment_calculator'] = $timing->tutor_payment_calculator;
			$insert_timing['unit_id'] = $timing->unit_id;
			$insert_timing['id'] = $timing->id;
			$insert_timing['session'] = $timing->session;
			$insert_timing['start_time'] = $timing->start_time;
			$insert_timing['end_time'] = $timing->end_time;
			$insert_timing['class_unit'] = $timing->classUnit;
			$insert_timing['oclassroom'] = $timing->classroom;
			$insert_timing['date'] = $timing->date->format('jS M Y');
			$insert_timing['udate'] = $timing->date->format('Y-m-d H:i:s');

			$insert_timing['classroom'] = 'NA';
			$insert_timing['teacher'] = 'NA';
			$insert_timing['status'] = $timing->status;
			$insert_timing['done'] = $timing->status == 'done' ? true : false;
			
			if($timing->teacher)
			{
				if(!in_array($timing->teacher->id, $added_teachers))
				{
					$teacher = [];
					$teacher['id'] = $timing->teacher->id;
					$teacher['name'] = $timing->teacher->user->name;
					$teacher['email'] = $timing->teacher->user->email;
					$teachers[] = $teacher;
					$added_teachers[] = $timing->teacher->id;
				}


				$insert_timing['teacher'] = $timing->teacher->user->name;
			}

			if($timing->classroom)
			{
				$insert_timing['classroom'] = $timing->classroom->name;
			}
			$timings[] = $insert_timing;
		}

		$data['timings'] = $timings;
		$data['teachers'] = $teachers;
		$data['no_of_units'] = $ahamClass->classUnits->count();

		$data['topic_order'] = [$ahamClass->topic->parent->parent->id,$ahamClass->topic->parent->id,$ahamClass->topic->id];
		

	    return $data;
	}

	public function includeTopic(AhamClass $ahamClass)
	{

        return $this->item($ahamClass->topic, new TopicTransformer);
	}

	public function includeTeacher(AhamClass $ahamClass)
	{
		if(is_null($ahamClass->teacher))
		{
			return null;
		}

        return $this->item($ahamClass->teacher, new TeacherTransformer);
	}

	public function includeClassUnits(AhamClass $ahamClass)
	{
        return $this->collection($ahamClass->classUnits, new ClassUnitTransformer);
	}

	public function includeEnrollments(AhamClass $ahamClass)
	{
        return $this->collection($ahamClass->enrollments, new EnrollmentTransformer);
	}
}
