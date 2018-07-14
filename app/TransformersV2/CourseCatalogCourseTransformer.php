<?php

namespace Aham\TransformersV2;

use Aham\Models\SQL\Course;
use League\Fractal;

class CourseCatalogCourseTransformer extends Fractal\TransformerAbstract
{
    public function transform(Course $course)
    {
        $data = [];

        $data['id'] = $course->id;
        $data['name'] = $course->name;
        $data['type'] =  $course->type;
        $data['status'] = $course->status;
        $data['description'] = $course->description;
        $data['created_at'] = $course->created_at;

        return $data;
    }
}
