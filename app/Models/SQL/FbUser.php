<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class FbUser extends Model
{
    protected $table = 'fb_users';

    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('Aham\Models\SQL\User','user_id');
    }

}
