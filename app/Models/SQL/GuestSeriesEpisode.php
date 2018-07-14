<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;

class GuestSeriesEpisode extends RevSoftModel implements UniquifyInterface
{
    use UniqueNoTrait;
    protected $table = 'guest_series_episodes';

    protected $guarded = [];

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });

    }

    protected $dates = ['enrollment_cutoff'];

    public function level()
    {
        return $this->belongsTo('Aham\Models\SQL\GuestSeriesLevel', 'level_id');
    }

    public function series()
    {
        return $this->belongsTo('Aham\Models\SQL\GuestSeries', 'series_id');
    }

    public function location()
    {
        return $this->belongsTo('Aham\Models\SQL\Location', 'location_id');
    }

    public function topic()
    {
        return $this->belongsTo('Aham\Models\SQL\Topic', 'topic_id');
    }

    public function repeatOf()
    {
        return $this->belongsTo('Aham\Models\SQL\GuestSeriesEpisode', 'repeat_of');
    }

    public function timings()
    {
        return $this->hasMany('Aham\Models\SQL\EpisodeTiming', 'episode_id');
    }

    public function enrollments()
    {
        return $this->hasMany('Aham\Models\SQL\UserEnrollment', 'episode_id')->where('type', 'episode');
    }
}
