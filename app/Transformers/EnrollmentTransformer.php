<?php namespace Aham\Transformers;

use Aham\Models\SQL\Student;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\Topic;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class EnrollmentTransformer extends Fractal\TransformerAbstract
{

	public function transform(StudentEnrollment $studentEnrollment)
	{
		$student = $studentEnrollment->student;

		$user = $student->user;

		$token = JWTAuth::fromUser($user);

		$data['email'] = $user->email;
		$data['name'] = $user->name;
		$data['mobile'] = $user->mobile;
		$data['token'] = $token;
		$data['code'] = $student->code;
		$data['curriculum'] = $student->curriculum;
		$data['school'] = $student->school;
		$data['student_id'] = $student->id;
		$data['grade'] = $student->user->grade;
		$data['joined_on'] = $student->created_at->format('jS M Y');
		$data['avatar'] = cloudinary_url($user->present()->picture, array('secure' => true));
		$data['avatar_set'] = false;
		$data['ghost'] = $studentEnrollment->ghost ? true : false;
		$data['avatar_cloudinary'] = null;
		$data['enrollment_id'] = $studentEnrollment->id;
		$data['rating_given'] = $studentEnrollment->rating_given ? true : false;

		if($user->picture)
		{		
			$picture['public_id'] = $user->picture->public_id;
			$picture['format'] = $user->picture->format;
			$data['avatar_cloudinary'] = $picture;
			$data['avatar_set'] = true;
		}


	    return $data;
	}

}