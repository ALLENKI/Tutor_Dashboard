<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class CreditsPromotional extends Model
{
    protected $table = 'credits_promotional';
    protected $guarded = [];
    public $dates = ['added_on'];

    public function bucket()
    {
        return $this->hasOne('Aham\Models\SQL\CreditsBucket');
    }

    public function of()
    {
        return $this->morphTo();
    }

    public function purchasedItem()
    {
        return $this->belongsTo(CreditsPurchased::class, 'purchased_id');
    }
}
