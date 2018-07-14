<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class CreditsUsed extends Model
{
    protected $table = 'credits_used';

    protected $guarded = [];
    public $dates = ['used_on'];

    public function bucket()
    {
        return $this->belongsTo('Aham\Models\SQL\CreditsBucket');
    }

    public function of()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('Aham\Models\SQL\User','user_id');
    }
}
