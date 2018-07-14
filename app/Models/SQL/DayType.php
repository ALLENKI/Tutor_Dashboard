<?php

namespace Aham\Models\SQL;

class DayType extends RevSoftModel
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

    protected $table = 'day_types';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($daytype) {
            foreach ($daytype->slots as $slot) {
                $slot->delete();
            }

            foreach ($daytype->locationCalendars as $calendar) {
                $calendar->delete();
            }
        });
    }

    public function scopeNotWeekend($query)
    {
        return $query->where('slug', '<>', 'weekend');
    }

    public function slots()
    {
        return $this->hasMany('Aham\Models\SQL\Slot', 'day_type_id')->orderBy('start_time', 'asc');
    }

    public function locationCalendars()
    {
        return $this->hasMany('Aham\Models\SQL\LocationCalendar', 'day_type_id')
                    ->orderBy('from_date', 'asc');
    }
}
