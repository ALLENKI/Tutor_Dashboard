<?php

namespace Aham\Models\SQL;

class Goal extends RevSoftModel
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

    protected $table = 'goals';

    protected $guarded = [];

    public function topics()
    {
        return $this->belongsToMany('Aham\Models\SQL\Topic')->whereIn('status', ['active', 'in_progress']);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
