<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $guarded = [];

    public function of()
    {
    	return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('Aham\Models\SQL\User','user_id');
    }
}
