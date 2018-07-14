<?php namespace Aham\Transformers;

use Aham\Models\SQL\TeacherAvailability;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class TeacherAvailabilityTransformer extends Fractal\TransformerAbstract
{

	public function transform(TeacherAvailability $availability)
	{
		$data = [];


        $sessions = [
            'early_morning' => 'Early Morning (5:30 - 8:00)',
            'morning' => 'Morning (8:00 - 12:00)',
            'afternoon' => 'Afternoon (12:00 - 16:00)',
            'evening' => 'Evening (16:00 - 18:00)',
            'late_evening' => 'Late Evening (18:00 - 21:00)',
        ];

		// $data = $availability->toArray();

        $data['id'] = $availability->id;
		$data['day_of_the_week'] = ucwords($availability->day_of_the_week);
		$data['from_date'] = $availability->from_date->format('jS M Y');
		$data['to_date'] = $availability->to_date->format('jS M Y');

		foreach($sessions as $key => $session)
		{
			if($availability->$key)
			{
				$data[$key] = 'Yes';
			}
			else
			{
				$data[$key] = 'No';
			}
		}

		$data['sessions'] = $sessions;

	    return $data;
	}

}