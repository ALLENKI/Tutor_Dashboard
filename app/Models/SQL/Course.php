<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Course extends Model implements UniquifyInterface
{
    use Sluggable;
    use UniqueNoTrait;
    
    protected $table = 'courses';
    protected $guarded = [];

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function topics()
    {
        return $this->morphedByMany('Aham\Models\SQL\Topic', 'coursable');
    }

    public function courses()
    {
        return $this->morphedByMany('Aham\Models\SQL\Course', 'coursable');
    }

    public function ahamClass()
    {
        return $this->morphMany('Aham\Models\SQL\AhamClass', 'of');
    }

    public function whishList()
    {
        return $this->belongsTo('Aham\Models\SQL\whichList', 'of');
    }

}
