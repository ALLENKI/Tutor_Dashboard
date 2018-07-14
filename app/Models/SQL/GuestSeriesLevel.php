<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;

class GuestSeriesLevel extends RevSoftModel implements UniquifyInterface
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

    protected $table = 'guest_series_levels';

    protected $guarded = [];

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

    protected $dates = ['enrollment_cutoff'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });

    }

    public function series()
    {
        return $this->belongsTo('Aham\Models\SQL\GuestSeries', 'series_id');
    }

    public function episodes()
    {
        return $this->hasMany('Aham\Models\SQL\GuestSeriesEpisode', 'level_id');
    }

    public function enrollments()
    {
        return $this->hasMany('Aham\Models\SQL\UserEnrollment', 'episode_id')->where('type', 'level');
    }
}
