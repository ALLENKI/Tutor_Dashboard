<?php

namespace Aham\Models\SQL;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;

class Student extends RevSoftModel implements UniquifyInterface
{
    use UniqueNoTrait;

    protected $table = 'students';

    protected $guarded = [];

    protected $unique_no_source = 'name';

    protected $unique_no_destination = 'code';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });

        static::deleting(function ($student) {
            foreach ($student->assessments as $assessment) {
                $assessment->delete();
            }
        });
    }

    public function getUniqueNoSource()
    {
        return 'S T' . ' ' . $this->user->name;
    }

    public function user()
    {
        return $this->belongsTo('Aham\Models\SQL\User', 'user_id');
    }

    public function assessments()
    {
        return $this->hasMany('Aham\Models\SQL\StudentAssessment', 'student_id');
    }

    public function goals()
    {
        return $this->belongsToMany('Aham\Models\SQL\Goal');
    }


    public function enrollmentUnits()
    {
        return $this->hasMany('Aham\Models\SQL\StudentEnrollmentUnit', 'student_id')
                    ->where('status','<>', 'cancelled');
    }

    public function enrollments()
    {
        return $this->hasMany('Aham\Models\SQL\StudentEnrollment', 'student_id')
                    ->where('cancelled', false);
    }

    public function classes()
    {
        return $this->hasMany('Aham\Models\SQL\StudentEnrollment', 'student_id')
                    ->where('cancelled', false);
    }

    public function invitations()
    {
        return $this->hasMany('Aham\Models\SQL\StudentInvitation', 'student_id')
                    ->where('status', '<>', 'not_interested');
    }

    public function classTimings()
    {
        return $this->hasMany('Aham\Models\SQL\ClassTiming', 'student_id')
                                ->where('cancelled', false);
    }

    public function ahamCredits()
    {
        return $this->hasMany('Aham\Models\SQL\StudentCredits', 'student_id');
    }

    public function lifetimeOffer()
    {
        return $this->hasOne('Aham\Models\SQL\StudentOffer', 'student_id')->with('coupon');
    }

    public function notes()
    {
        return $this->morphMany('Aham\Models\SQL\Note', 'of')->orderBy('created_at', 'asc');
    }

    public function hubs()
    {
        return $this->belongsToMany(Location::class,'location_student','student_id','location_id');
    }
}
