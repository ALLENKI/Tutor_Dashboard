<?php

namespace Aham\Models\SQL;

class Page extends RevSoftModel
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

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($org) {
        });
    }
}
