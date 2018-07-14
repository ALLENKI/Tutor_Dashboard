<?php

namespace Aham\Models\SQL;

use Karl456\Presenter\Traits\PresentableTrait;

class StudentEnrollment extends RevSoftModel
{
    use PresentableTrait;

    protected $table = 'student_enrollments';

    protected $guarded = [];

    protected $presenter = 'Aham\Presenters\StudentEnrollmentPresenter';

    public static function boot()
    {
        parent::boot();

        static::created(function ($enrollment) {
            event(new \Aham\Events\Student\EnrolledToClass($enrollment));
        });
    }

    public function ahamClass()
    {
        return $this->belongsTo('Aham\Models\SQL\AhamClass', 'class_id');
    }

    public function student()
    {
        return $this->belongsTo('Aham\Models\SQL\Student', 'student_id');
    }

    public function unitEnrollments()
    {
        return $this->hasMany(StudentEnrollmentUnit::class,'student_enrollment_id');
    }
}
