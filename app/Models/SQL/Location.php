<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;
use Karl456\Presenter\Traits\PresentableTrait;

class Location extends RevSoftModel implements UniquifyInterface
{
    use UniqueNoTrait;
    use PresentableTrait;

    protected $presenter = 'Aham\Presenters\LocationPresenter';

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

    protected $table = 'locations';

    protected $guarded = [];

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });

        static::deleting(function ($location) {
            foreach ($location->locationCalendars as $calendar) {
                $calendar->delete();
            }

            foreach ($location->classrooms as $classroom) {
                $classroom->delete();
            }
        });
    }

    public function classes()
    {
        return $this->hasMany('Aham\Models\SQL\AhamClass', 'location_id');
    }

    public function city()
    {
        return $this->belongsTo('Aham\Models\SQL\City', 'city_id');
    }

    public function locality()
    {
        return $this->belongsTo('Aham\Models\SQL\Locality', 'locality_id');
    }

    public function locationCalendars()
    {
        return $this->hasMany('Aham\Models\SQL\LocationCalendar', 'location_id')
                    ->orderBy('from_date', 'asc');
    }

    public function notes()
    {
        return $this->morphMany('Aham\Models\SQL\Note', 'of')->orderBy('created_at', 'asc');
    }

    public function classrooms()
    {
        return $this->hasMany('Aham\Models\SQL\Classroom', 'location_id');
    }

    public function topics()
    {
        return $this->morphedByMany('Aham\Models\SQL\Topic', 'hub_topics');
    }

    public function courses()
    {
        return $this->morphedByMany('Aham\Models\SQL\Course', 'hub_topics');
    }
}
