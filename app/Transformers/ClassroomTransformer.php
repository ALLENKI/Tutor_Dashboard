<?php namespace Aham\Transformers;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Classroom;
use Aham\Models\SQL\ClassUnit;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class ClassroomTransformer extends Fractal\TransformerAbstract
{


	public function transform(Classroom $classroom)
	{
	    return $classroom->toArray();
	}

}