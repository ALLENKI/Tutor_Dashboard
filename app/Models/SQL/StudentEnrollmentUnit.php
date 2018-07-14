<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class StudentEnrollmentUnit extends Model
{
    protected $table = 'student_enrollment_units_new';
    // protected $table = 'student_enrollment_units';

    protected $guarded = [];

    protected $dates = ['date'];

    public function classTiming()
    {
        return $this->hasOne('Aham\Models\SQL\ClassTiming','class_unit_id','class_unit_id');
    }

    // public function teachers()
    // {
    //     return $this->hasManyThrough(
    //         'Aham\Models\SQL\Teacher',
    //         'Aham\Models\SQL\ClassTiming',
    //         'teacher_id', // Foreign key on users table...
    //         'id', // Foreign key on posts table...
    //         'class_unit_id', // Local key on countries table...
    //         'class_unit_id' // Local key on users table...
    //     );
    // }
  
    public function learner()
    {
        return $this->belongsTo('Aham\Models\SQL\Student', 'student_id');
    }

    public function classUnit()
    {
        return $this->belongsTo('Aham\Models\SQL\ClassUnit', 'class_unit_id');
    }

    public function ahamClass()
    {
        return $this->belongsTo('Aham\Models\SQL\AhamClass', 'class_id');
    }

    public function enrollment()
    {
        return $this->hasOne(StudentEnrollment::class,'class_id','class_id')
                    ->where('student_id',$this->student_id);
    }
}
