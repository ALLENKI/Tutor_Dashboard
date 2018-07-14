<?php

namespace Aham\TransformersV2;

use Aham\Models\SQL\Teacher;
use League\Fractal;

class TutorTransformer extends Fractal\TransformerAbstract
{
    public function transform(Teacher $tutor)
    {
        $data = [];

        $data['id'] = $tutor->id;
        $data['active'] = $tutor->active ? 'yes' : 'no';
        $data['name'] = $tutor->user->name;
        $data['email'] = $tutor->user->email;
        $data['since'] = $tutor->created_at->format('d M Y');
        $data['avatar'] = cloudinary_url($tutor->user->present()->picture, array("height"=>80, "width"=>80, "crop"=>"thumb",'secure' => true));
        $data['joined_on'] = $tutor->created_at->format('jS M Y');

        return $data;
    }
}
