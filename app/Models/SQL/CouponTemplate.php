<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class CouponTemplate extends RevSoftModel
{
    protected $table = 'coupon_templates';

    protected $guarded = [];

    protected $dates = ['valid_from','valid_till'];

    public function users()
    {
        return $this->belongsToMany('Aham\Models\SQL\User');
    }
}
