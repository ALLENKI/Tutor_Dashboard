<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class Notification extends RevSoftModel
{
    protected $table = 'notifications';

    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('Aham\Models\SQL\User','user_id');
    }
}
