<?php namespace Aham\Transformers;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Classroom;
use Aham\Models\SQL\Note;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class NoteTransformer extends Fractal\TransformerAbstract
{

	public function transform(Note $note)
	{
		$data = [];
		$data['id'] = $note->id;
		$data['note'] = $note->note;
		$data['user'] = $note->user->name;
		$data['created_at'] = $note->created_at->format('d-m-Y H:i A');

	    return $data;
	}

}