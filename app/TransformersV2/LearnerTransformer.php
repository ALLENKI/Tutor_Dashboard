<?php

namespace Aham\TransformersV2;

use Aham\Models\SQL\Student;
use League\Fractal;

class LearnerTransformer extends Fractal\TransformerAbstract
{
    public function transform(Student $learner)
    {
        $data = [];

        $data['id'] = $learner->id;
        $data['active'] = $learner->active ? 'yes' : 'no';
        $data['name'] = $learner->user->name;
        $data['email'] = $learner->user->email;
        $data['since'] = $learner->created_at->format('d M Y');
        $data['avatar'] = cloudinary_url($learner->user->present()->picture, array("height"=>80, "width"=>80, "crop"=>"thumb",'secure' => true));
        $data['lifetime'] = $learner->lifetimeOffer;
        $data['preferred_locations'] = $learner->hubs;

        return $data;
    }
}
