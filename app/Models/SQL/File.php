<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $guarded = [];


    public function user()
    {
        return $this->hasOne('Aham\Models\SQL\User');
    }

    public function of()
    {
    	return $this->morphTo();
    }

}