<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class TeacherCertification extends RevSoftModel
{
    protected $table = 'teacher_certification';

    protected $guarded = [];


    public static function boot()
    {
        parent::boot();

        static::created(function ($certification) {

            

        });

    }

    public function topic()
    {
    	return $this->belongsTo('Aham\Models\SQL\Topic','topic_id');
    }

    public function teacher()
    {
    	return $this->belongsTo('Aham\Models\SQL\Teacher','teacher_id');
    }
}
