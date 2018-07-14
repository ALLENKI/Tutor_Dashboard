<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;

class ClassUnit extends RevSoftModel
{
    use UniqueNoTrait;

    protected $table = 'class_units';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($unit) {
        });
    }

    /**
     * [scopeOrdered description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function topic()
    {
        return $this->belongsTo('Aham\Models\SQL\Topic');
    }

    public function unit()
    {
        return $this->belongsTo('Aham\Models\SQL\Unit', 'original_unit_id');
    }

    public function classTiming()
    {
        return $this->hasOne('Aham\Models\SQL\ClassTiming', 'class_unit_id');
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollmentUnitsNew::class,'class_unit_id')
                    ->orderBy('created_at','asc');
    }

    public function previousUnit()
    {
        $list_of_units = $this->topic->units->pluck('id')->toArray();

        $key = array_search($this->id, $list_of_units);

        if (($key - 1) < 0) {
            return null;
        }

        return $list_of_units[$key - 1];
    }
}
