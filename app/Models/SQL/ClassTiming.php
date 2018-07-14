<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class ClassTiming extends RevSoftModel
{

    protected $table = 'class_timings';

    protected $guarded = [];

    protected $dates = ['date'];

    public function ahamClass()
    {
    	return $this->belongsTo('Aham\Models\SQL\AhamClass','class_id');
    }

    // enrolled learners belongs to this classTimings
    public function enrolledLearners()
    {
        return $this->hasManyThrough(
            'Aham\Models\SQL\Student',
            'Aham\Models\SQL\StudentEnrollmentUnit',
            'class_unit_id', // Foreign key on StudentEnrollmentUnit table...
            'id', // Foreign key on Student table...
            'class_unit_id', // Local key on ClassTimings table...
            'student_id' // Local key on StudentEnrollmentUnit table...
        )->where('status','<>','cancelled');
    }

    public function ofclass()
    {
        return $this->belongsTo('Aham\Models\SQL\AhamClass','class_id');
    }

    public function slot()
    {
    	return $this->belongsTo('Aham\Models\SQL\Slot','slot_id');
    }

    public function classroom()
    {
        return $this->belongsTo('Aham\Models\SQL\Classroom','classroom_id');
    }

    public function teacher()
    {
        return $this->belongsTo('Aham\Models\SQL\Teacher','teacher_id');
    }

    public function unit()
    {
        return $this->belongsTo('Aham\Models\SQL\Unit','unit_id');
    }

    public function classUnit()
    {
        return $this->belongsTo('Aham\Models\SQL\ClassUnit','class_unit_id');
    }

    public function location()
    {
        return $this->belongsTo('Aham\Models\SQL\Location','location_id');
    }


    public function of()
    {
        return $this->morphTo();
    }

}
