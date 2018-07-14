<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;

class Classroom extends RevSoftModel implements UniquifyInterface
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

    protected $table = 'classrooms';

    protected $guarded = [];

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });

    }

    public function location()
    {
        return $this->belongsTo('Aham\Models\SQL\Location', 'location_id');
    }

    public function classroomSlots()
    {
        return $this->hasMany('Aham\Models\SQL\ClassroomSlot', 'classroom_id');
    }
}
