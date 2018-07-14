<?php


namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class StudentOffer extends RevSoftModel
{
    protected $table = 'student_offers';

    protected $guarded = [];

    public function coupon()
    {
    	return $this->belongsTo('Aham\Models\SQL\Coupon','coupon_id');
    }

    public function student()
    {
    	return $this->belongsTo('Aham\Models\SQL\Student','student_id');
    }
}
