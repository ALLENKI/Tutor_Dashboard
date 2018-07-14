<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class TopicsLookup extends Model
{
    protected $table = 'topics_lookup';

    protected $guarded = [];

    public function topic()
    {
    	return $this->belongsTo('Aham\Models\SQL\Topic','topic_id');
    }

    public function subCategory()
    {
    	return $this->belongsTo('Aham\Models\SQL\Topic','sub_category_id');
    }

    public function subject()
    {
    	return $this->belongsTo('Aham\Models\SQL\Topic','subject_id');
    }

    public function category()
    {
    	return $this->belongsTo('Aham\Models\SQL\Topic','category_id');
    }

}
