<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class TutorCommission extends Model
{
    protected $table = 'tutor_commissions';

    protected $guarded = [];

	public function teacher()
    {
        return $this->belongsTo('Aham\Models\SQL\Teacher','teacher_id');
    }

    public function location()
    {
    	return $this->belongsTo('Aham\Models\SQL\Location','location_id');
    }
}
