<?php

namespace Aham\Models\SQL;

class City extends RevSoftModel
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

    protected $table = 'cities';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($city) {
            foreach ($city->locations as $location) {
                $location->delete();
            }

            foreach ($city->localities as $location) {
                $location->delete();
            }
        });
    }

    public function state()
    {
        return $this->belongsTo('Aham\Models\SQL\State', 'state_id');
    }

    public function localities()
    {
        return $this->hasMany('Aham\Models\SQL\Locality', 'city_id');
    }

    public function locations()
    {
        return $this->hasMany('Aham\Models\SQL\Location', 'city_id');
    }
}
