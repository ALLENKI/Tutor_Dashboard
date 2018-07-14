<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class CloudMsgId extends Model
{
    protected $table = 'cloud_msg_ids';

    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('Aham\Models\SQL\User','user_id');
    }
}
