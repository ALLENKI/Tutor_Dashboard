<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class FbParticipant extends Model
{
    protected $table = 'fb_participants';

    protected $guarded = [];

    public function chat()
    {
    	return $this->belongsTo('Aham\Models\SQL\FbChat','fb_chat_id');
    }

    public function user()
    {
    	return $this->belongsTo('Aham\Models\SQL\User','user_id');
    }
}
