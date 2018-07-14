<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class StudentInvitation extends Model
{
    protected $table = 'student_invitations';

    protected $guarded = [];

    public function ahamClass()
    {
    	return $this->belongsTo('Aham\Models\SQL\AhamClass','class_id');
    }

    public function student()
    {
    	return $this->belongsTo('Aham\Models\SQL\Student','student_id');
    }
}
