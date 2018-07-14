<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;

class Comment extends RevSoftModel
{
    protected $table = 'comments';

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
