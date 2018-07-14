<?php
namespace Aham\Models\SQL;
use Illuminate\Database\Eloquent\Model;
use Aham\Traits\UniqueNoTrait;
use Aham\Traits\UniquifyInterface;
class Teacher extends RevSoftModel implements UniquifyInterface
{
    use UniqueNoTrait;
    protected $table = 'teachers';
    protected $guarded = [];
    protected $unique_no_source = 'name';
    protected $unique_no_destination = 'code';
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uniquify();
        });
        static::created(function ($teacher) {
            event(new \Aham\Events\Teacher\Created($teacher));
        });
        static::deleting(function ($teacher) {
            foreach($teacher->certifications as $certification)
            {
                $certification->delete();
            }
        });
    }
    public function getUniqueNoSource()
    {
        return 'T E'.' '.$this->user->name;
    }
    public function user()
    {
        return $this->belongsTo('Aham\Models\SQL\User','user_id');
    }
    public function tutorCommission()
    {
        return $this->hasOne('Aham\Models\SQL\TutorCommission','teacher_id');
    }
    public function certifications()
    {
        return $this->hasMany('Aham\Models\SQL\TeacherCertification','teacher_id');
    }
    public function availabilities()
    {
        return $this->hasMany('Aham\Models\SQL\TeacherAvailability','teacher_id');
    }
    public function completedClasses()
    {
        return $this->hasMany('Aham\Models\SQL\AhamClass','teacher_id')->where('status','completed');
    }
     public function classesTimings()
    {
        return $this->hasMany('Aham\Models\SQL\ClassTiming','teacher_id');
    }
    public function classes()
    {
        return $this->hasMany('Aham\Models\SQL\AhamClass','teacher_id');
    }
   
    public function allEarnings()
    {
        return $this->hasMany('Aham\Models\SQL\TeacherEarning','teacher_id');
    }
    public function invitations()
    {
        return $this->hasMany('Aham\Models\SQL\ClassInvitation','teacher_id');
    }
    public function notes()
    {
        return $this->morphMany('Aham\Models\SQL\Note','of')->orderBy('created_at','asc');
    }
}