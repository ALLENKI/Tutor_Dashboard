<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class TutorPayments extends Model
{
    protected $table = 'tutor_payments';

    protected $guarded = [];

	public function teacher()
    {
        return $this->belongsTo('Aham\Models\SQL\Teacher','teacher_id');
    }

}