<?php

namespace Aham\TransformersV2;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Unit;
use League\Fractal;

class CourseCatalogTopicTransformer extends Fractal\TransformerAbstract
{
    public function transform(Topic $topic)
    {
        $data = [];

        $data['id'] = $topic->id;
        $data['name'] = $topic->name;
        $data['type'] = $topic->type;
        $data['status'] = $topic->status;
        $data['description'] = $topic->description;
        $data['created_at'] = $topic->created_at;
        $data['approve'] = $topic->approve;

        if($topic->type!="topic")
        {
            $data['count'] = $topic->children->count();
        }
        else
        {
            $data['count']=$topic->units->count();
        }
        return $data;
    }
}
