<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;

class AhamClass extends RevSoftModel implements UniquifyInterface
{
    use UniqueNoTrait;

    protected $table = 'classes';

    protected $guarded = [];

    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';

    protected $dates = ['start_date','enrollment_cutoff','schedule_cutoff','completed_at','cancelled_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uniquify();
        });


        static::deleting(function ($class) {
            
            foreach($class->timings as $timing)
            {
                $timing->delete();
            }

            foreach($class->invitations as $timing)
            {
                $timing->delete();
            }

            foreach($class->enrollments as $timing)
            {
                $timing->delete();
            }

        });
    }

    public function scopeScheduled($query)
    {
        return $query->where('status','scheduled');
    }


    public function scopeOpenForEnrollment($query)
    {
        return $query->where('status','open_for_enrollment');
    }

    public function teacher()
    {
        return $this->belongsTo('Aham\Models\SQL\Teacher','teacher_id');
    }

    public function topic()
    {
    	return $this->belongsTo('Aham\Models\SQL\Topic','topic_id');
    }

    public function location()
    {
    	return $this->belongsTo('Aham\Models\SQL\Location','location_id');
    }

    public function creator()
    {
    	return $this->belongsTo('Aham\Models\SQL\User','creator_id');
    }
    
    public function creditsUsed()
    {
        return $this->morphMany('Aham\Models\SQL\CreditsUsed','of');
    }

    public function creditsRefunded()
    {
        return $this->morphMany('Aham\Models\SQL\CreditsRefund','of');
    }

    public function course()
    {
    	return $this->belongsTo('Aham\Models\SQL\Course','of_id');
    }

    public function of()
    {
        return $this->morphTo();
    }   

    public function getUniqueNoSource()
    {
        return 'C L'.' '.$this->of->name;
        // return 'C L'.' ';
    }

    public function timings()
    {
        return $this->hasMany('Aham\Models\SQL\ClassTiming','class_id');
    }

    public function classUnits()
    {
        return $this
        ->hasMany('Aham\Models\SQL\ClassUnit','class_id')
        ->where(function($query)
        {
            $query->whereNotNull('topic_id')
                  ->where('topic_id','<>',0);
        })
        ->orderBy('original_unit_id','asc');
    }

    public function classTimings()
    {
        return $this->morphMany('Aham\Models\SQL\ClassTiming','of')
                    ->orderBy('class_unit_id','asc');
    }

    public function schedulingRule()
    {
        return $this->belongsTo('Aham\Models\SQL\SchedulingRule','scheduling_rule_id');
    }

    public function invitations()
    {
        return $this->hasMany('Aham\Models\SQL\ClassInvitation','class_id');
    }


    public function studentInvitations()
    {
        return $this->hasMany('Aham\Models\SQL\StudentInvitation','class_id');
    }

    public function comments()
    {
        return $this->morphMany('Aham\Models\SQL\Comment','of');
    }

    public function notes()
    {
        return $this->morphMany('Aham\Models\SQL\Note','of')->orderBy('created_at','asc');
    }

    public function enrollments()
    {
        return $this->hasMany('Aham\Models\SQL\StudentEnrollment','class_id')
                    ->where('cancelled',false);
    }

    public function enrollmentUnits()
    {
        return $this->hasMany('Aham\Models\SQL\StudentEnrollmentUnit','class_id')
                    ->where('status','enrolled');
    }


    public function allEnrollments()
    {
        return $this->hasMany('Aham\Models\SQL\StudentEnrollment','class_id')
                    ->orderBy('created_at','asc');
    }

    public function attachments()
    {
        return $this->hasMany('Aham\Models\SQL\ClassAttachment','class_id');
    }

    public function isScheduled()
    {
        $unitsInTopic = $this->topic->units->pluck('id')->toArray();

        $unitsScheduled = $this->timings->pluck('unit_id')->toArray();

        $remainingUnitsToBeScheduled = count(array_diff($unitsInTopic, $unitsScheduled));

        if($remainingUnitsToBeScheduled)
        {
            return false;
        }

        return true;
    }
}
