<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class FbChat extends Model
{
    protected $table = 'fb_chats';

    protected $guarded = [];

    public function of()
    {
        return $this->morphTo();
    }

    public function location()
    {
    	return $this->belongsTo('Aham\Models\SQL\Location','location_id');
    }

    public function participants()
    {
    	return $this->hasMany('Aham\Models\SQL\FbParticipant','fb_chat_id');
    }
}
