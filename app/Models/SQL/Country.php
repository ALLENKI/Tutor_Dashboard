<?php

namespace Aham\Models\SQL;

class Country extends RevSoftModel
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

    protected $table = 'countries';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($country) {
            foreach ($country->states as $state) {
                $state->delete();
            }
        });
    }

    public function states()
    {
        return $this->hasMany('Aham\Models\SQL\State', 'country_id');
    }
}
