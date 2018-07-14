<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class StudentAssessment extends RevSoftModel
{
    protected $table = 'student_assessments';

    protected $guarded = [];

    public function topic()
    {
    	return $this->belongsTo('Aham\Models\SQL\Topic','topic_id');
    }
}
