<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;
use Karl456\Presenter\Traits\PresentableTrait;

class GuestSeries extends RevSoftModel implements UniquifyInterface
{
    use UniqueNoTrait;
    use PresentableTrait;

    protected $presenter = 'Aham\Presenters\GuestSeriesPresenter';

    protected $table = 'guest_series';

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

        static::deleting(function ($series) {
        });
    }

    public function levels()
    {
        return $this->hasMany('Aham\Models\SQL\GuestSeriesLevel', 'series_id');
    }

    public function picture()
    {
        return $this->morphOne('Aham\Models\SQL\CloudinaryImage', 'of')->where('type', 'picture');
    }

    public function episodes()
    {
        return $this->hasMany('Aham\Models\SQL\GuestSeriesEpisode', 'series_id');
    }

    public function location()
    {
        return $this->belongsTo('Aham\Models\SQL\Location', 'location_id');
    }

    public function creator()
    {
        return $this->belongsTo('Aham\Models\SQL\User', 'creator_id');
    }

    public function getUniqueNoSource()
    {
        return 'G S' . ' ' . $this->name;
    }
}
