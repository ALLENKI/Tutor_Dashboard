<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class TeacherAvailability extends RevSoftModel
{
    protected $table = 'teacher_availability';

    protected $guarded = [];

    protected $dates = ['from_date', 'to_date'];

    public function teacher()
    {
    	return $this->belongsTo('Aham\Models\SQL\Teacher','teacher_id');
    }
}
