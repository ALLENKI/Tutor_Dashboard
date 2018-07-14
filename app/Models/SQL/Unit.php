<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;

class Unit extends RevSoftModel implements UniquifyInterface
{
    use UniqueNoTrait;

    use \Cviebrock\EloquentSluggable\Sluggable;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'separator' => ''
            ]
        ];
    }

    protected $table = 'units';

    protected $guarded = [];

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });

        static::deleting(function ($unit) {
        });
    }

    /**
     * [scopeOrdered description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id','asc');
    }

    public function topic()
    {
        return $this->belongsTo('Aham\Models\SQL\Topic');
    }

    public function previousUnit()
    {
        $list_of_units = $this->topic->units->pluck('id')->toArray();

        $key = array_search($this->id, $list_of_units);

        if (($key - 1) < 0) {
            return null;
        }

        return $list_of_units[$key - 1];
    }
}
