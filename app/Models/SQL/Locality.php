<?php

namespace Aham\Models\SQL;

class Locality extends RevSoftModel
{
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

    protected $table = 'localities';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($city) {
            foreach ($city->locations as $location) {
                $location->delete();
            }
        });
    }

    public function city()
    {
        return $this->belongsTo('Aham\Models\SQL\City', 'city_id');
    }

    public function locations()
    {
        return $this->hasMany('Aham\Models\SQL\Location', 'locality_id');
    }
}
