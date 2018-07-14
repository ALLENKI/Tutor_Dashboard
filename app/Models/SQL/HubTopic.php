<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class HubTopic extends Model
{
    protected $table = 'hub_topics';

    protected $guarded = [];

    public function of()
    {
    	return $this->morphTo();
    }
}
