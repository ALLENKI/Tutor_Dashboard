<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class CreditsRefund extends Model
{
    protected $table = 'credits_refunds';

    protected $guarded = [];
    public $dates = ['refunded_on'];


    public function of()
    {
        return $this->morphTo();
    }
    
    public function bucket()
    {
        return $this->hasMany('Aham\Models\SQL\CreditsBucket');
    }
}
