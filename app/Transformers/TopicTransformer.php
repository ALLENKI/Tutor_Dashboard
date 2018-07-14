<?php namespace Aham\Transformers;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\SchedulingRule;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class TopicTransformer extends Fractal\TransformerAbstract
{
	public function transform(Topic $topic)
	{
		$data = [];

		$data['id'] = $topic->id;
		$data['name'] = $topic->name;
		$data['code'] = $topic->code;
		$data['type'] = $topic->type;
		$data['status'] = $topic->status;
		$data['description'] = $topic->description;
		$data['subject'] = $topic->lookup->subject->name;
		$data['subCategory'] = $topic->lookup->subCategory->name;
		$data['avatar'] = cloudinary_url($topic->present()->picture, array("height"=>550, "width"=>550, "crop"=>"thumb", 'secure' => true));

		$units = [];

		foreach($topic->units as $unit)
		{
			$single_unit = [];

			$single_unit['id'] = $unit->id;
			$single_unit['name'] = $unit->name;
			$single_unit['description'] = $unit->description;

			$units[] = $single_unit;
		}

    	$number_of_units = $topic->units->count();

    	$topicRules = SchedulingRule::where('no_of_units', $number_of_units)->get();

		$data['units'] = $units;
		$data['rules'] = $topicRules;

	    return $data;
	}
}