<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class GroupClass extends Model
{
    protected $table = 'group_classes';

    protected $guarded = [];

    public function goal()
    {
    	return $this->belongsTo('Aham\Models\SQL\Goal','goal_id');
    }

    public function classes()
    {
    	return $this->hasMany('Aham\Models\SQL\AhamClass','group_id');
    }
}
