<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class CreditsBucket extends Model
{
    protected $table = 'credits_buckets';
    protected $guarded = [];

    public function purchased()
    {
        return $this->hasOne('Aham\Models\SQL\CreditsPurchased','bucket_id');
    }



    public function promotional()
    {
        return $this->belongsTo('Aham\Models\SQL\CreditsPromotional');
    }

    public function used()
    {
        return $this->belongsTo('Aham\Models\SQL\CreditsUsed');
    }

    public function hubOnly()
    {
        return $this->hasOne('Aham\Models\SQL\CreditsHubOnly','bucket_id');
    }

    public function refund()
    {
        return $this->belongsTo('Aham\Models\SQL\CreditsRefund');
    }

    public function user()
    {
        return $this->belongsTo('Aham\Models\SQL\User', 'user_id');
    }

    public function hub()
    {
        return $this->belongsTo('Aham\Models\SQL\Location', 'hub_id');
    }
}
