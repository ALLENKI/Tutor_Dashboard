<?php

namespace Aham\Models\SQL;

class State extends RevSoftModel
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

    protected $table = 'states';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($state) {
            foreach ($state->cities as $city) {
                $city->delete();
            }
        });
    }

    public function country()
    {
        return $this->belongsTo('Aham\Models\SQL\Country', 'country_id');
    }

    public function cities()
    {
        return $this->hasMany('Aham\Models\SQL\City', 'state_id');
    }
}
