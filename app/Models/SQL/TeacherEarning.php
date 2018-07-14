<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class TeacherEarning extends RevSoftModel
{
    protected $table = 'teacher_earnings';

    protected $guarded = [];

    protected $dates = ['paid_on'];

    public function topic()
    {
    	return $this->belongsTo('Aham\Models\SQL\Teacher','teacher_id');
    }
}
