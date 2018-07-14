<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class StudentCredits extends RevSoftModel
{
    protected $table = 'student_credits';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::created(function ($credit) {
            $suffix = str_pad($credit->id, 5, '0', STR_PAD_LEFT);
            $suffix = $credit->created_at->format('mdY').$suffix;
            $invoice_no = 'ALH'.$suffix;
            $credit->invoice_no = $invoice_no;
            $credit->save();
        });
    }

    public function student()
    {
    	return $this->belongsTo('Aham\Models\SQL\Student','student_id');
    }

    public function coupon()
    {
    	return $this->belongsTo('Aham\Models\SQL\Coupon','coupon_id');
    }

    public function parent()
    {
    	return $this->belongsTo('Aham\Models\SQL\StudentCredit','parent_id');
    }

    public function anyCoupon()
    {
        return $this->hasOne('Aham\Models\SQL\StudentCredits','parent_id');
    }

}
