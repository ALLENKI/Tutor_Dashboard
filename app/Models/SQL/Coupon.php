<?php

namespace Aham\Models\SQL;

use Carbon;
use Karl456\Presenter\Traits\PresentableTrait;

class Coupon extends RevSoftModel
{
    use PresentableTrait;

    protected $presenter = 'Aham\Presenters\CouponPresenter';

    protected $table = 'coupons';

    protected $guarded = [];

    protected $dates = ['valid_from', 'valid_till'];

    public function user()
    {
        return $this->belongsTo('Aham\Models\SQL\User', 'user_id');
    }

    public function usage()
    {
        return $this->hasMany('Aham\Models\SQL\StudentCredits', 'coupon_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeValid($query)
    {
        return $query->where('valid_from', '<=', Carbon::now())
                    ->where(function ($q) {
                        $q->where('valid_till', '>=', Carbon::now())
                            ->orWhere('type', 'lifetime');
                    });
    }

    public function scopeApplicable($query, $user)
    {
        // $user = \Sentinel::getUser();

        return $query->where(function ($q) use ($user) {
            $q->where('user_id', 0)
                            ->orWhere('user_id', $user->id);
        });
    }
}
