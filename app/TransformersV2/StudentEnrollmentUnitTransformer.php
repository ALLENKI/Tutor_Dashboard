<?php

namespace Aham\TransformersV2;

use Aham\Models\SQL\StudentEnrollmentUnit;
use League\Fractal;

class StudentEnrollmentUnitTransformer extends Fractal\TransformerAbstract
{
    public function transform(StudentEnrollmentUnit $enrollmentUnit)
    {
        $data = [];
        $data['id'] = $enrollmentUnit->id;
        $data['unit'] = $enrollmentUnit->classUnit->name;
        $data['start_time'] = $enrollmentUnit->start_time;
        $data['date'] = $enrollmentUnit->date;
        $data['end_time'] = $enrollmentUnit->end_time;
        $data['topic'] = $enrollmentUnit->ahamClass->topic_name;
        $data['topic_avatar'] = cloudinary_url($enrollmentUnit->ahamClass->topic->present()->picture, array("height"=>500, "width"=>800, "crop"=>"thumb", 'secure' => true, "fetch_format" => "auto"));
        // /dist/media/img/blog/blog1.jpg

        $data['location'] = $enrollmentUnit->ahamClass->location->name;

        $data['tutor'] = null;
        $data['tutor_avatar'] = null;
        $data['tutor_email'] = null;
        if ($enrollmentUnit->classUnit->classTiming->teacher) {
            $tutor = $enrollmentUnit->classUnit->classTiming->teacher->user;
            $data['tutor'] = $tutor->name;
            $data['tutor_email'] = $tutor->email;
            $data['tutor_avatar'] = cloudinary_url($tutor->present()->picture, array("height"=>80, "width"=>80, "crop"=>"thumb",'secure' => true));
        }

        $data['enrolled'] = $enrollmentUnit->classUnit->classTiming->ahamClass->enrolled;


        return $data;
    }
}
