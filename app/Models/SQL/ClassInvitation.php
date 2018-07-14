<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

use Karl456\Presenter\Traits\PresentableTrait;

class ClassInvitation extends RevSoftModel
{
    use PresentableTrait;

    protected $table = 'class_invitations';

    protected $presenter = 'Aham\Presenters\ClassInvitiationPresenter';

    protected $guarded = [];

    protected $dates = ['from_date','to_date'];


    public static function boot()
    {
        parent::boot();

        static::created(function ($invite) {

            event(new \Aham\Events\TeacherInvited($invite));

        });

        static::updated(function ($invite) {

            if($invite->status == 'accepted')
            {
                event(new \Aham\Events\TeacherAccepted($invite));
            }

            if($invite->status == 'awarded')
            {
                event(new \Aham\Events\TeacherAwarded($invite));
            }

            
        });

    }
    
    public function scopePending($query)
    {
        return $query->where('status','pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status','accepted');
    }

    public function scopeAwarded($query)
    {
        return $query->where('status','awarded');
    }

    public function scopeExpired($query)
    {
        return $query->where('status','expired');
    }

    public function ahamClass()
    {
    	return $this->belongsTo('Aham\Models\SQL\AhamClass','class_id');
    }

    public function teacher()
    {
    	return $this->belongsTo('Aham\Models\SQL\Teacher','teacher_id');
    }
}
