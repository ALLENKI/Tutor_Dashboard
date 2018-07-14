<?php namespace Aham\Transformers;

use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\SchedulingRule;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class ClassUnitTransformer extends Fractal\TransformerAbstract
{
	public function transform(ClassUnit $unit)
	{
		$data = [];

		$data['id'] = $unit->id;
		$data['name'] = $unit->name;
		$data['description'] = $unit->description;

	    return $data;
	}
}