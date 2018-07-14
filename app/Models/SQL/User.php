<?php

namespace Aham\Models\SQL;

use Karl456\Presenter\Traits\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Cviebrock\EloquentSluggable\Sluggable;

class User extends \Cartalyst\Sentinel\Users\EloquentUser
{
    use PresentableTrait;
    use SoftDeletes;
    use Sluggable;

    public function sluggable()
    {
        return [
            'username' => [
                'source' => 'name',
                'separator' => '',
                'max_length' => 12,
                'reserved' => ['admin', 'superuser']
            ]
        ];
    }

    protected $fillable = [];
    protected $guarded = [];
    protected $dates = ['last_login'];
    protected $table = 'users';


    protected $presenter = 'Aham\Presenters\UserPresenter';

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if ($user->interested_in == 'teacher') {
                event(new \Aham\Events\TeacherRegistered($user));
            }

            if ($user->interested_in == 'student') {
                event(new \Aham\Events\StudentRegistered($user));
            }
        });

        static::deleting(function ($user) {
            $user->student->delete();
            $user->teacher->delete();
        });
    }

    public function hasAccess($permissions)
    {
        return $this->can($permissions);

        //If user has superuser - send true - else return what parent returns

        if (parent::hasAccess('superuser')) {
            return true;
        }

        return parent::hasAccess($permissions);
    }

    public function isSuperUser()
    {
        if (parent::hasAccess('superuser')) {
            return true;
        }

        return false;
    }

    public function permissions()
    {
        return $this->hasMany('Aham\Models\SQL\UserPermission', 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany('Aham\Models\SQL\UserEnrollment', 'user_id');
    }

    public function can($permission, $location = null)
    {
        if ($this->isSuperUser()) {
            return true;
        }

        if (!is_null($location)) {
            $exists = UserPermission::where('permission', $permission)
                            ->where('of_type', 'Aham\Models\SQL\Location')
                            ->where('of_id', $location)
                            ->where('user_id', $this->id)
                            ->first();

            if ($exists) {
                return true;
            }
        } else {
            $exists = UserPermission::where('permission', $permission)
                            ->where('user_id', $this->id)
                            ->first();

            if ($exists) {
                return true;
            }
        }

        return false;
    }

    public function accessibleLocations($permission)
    {
        if ($this->isSuperUser()) {
            return Location::pluck('id')->toArray();
        }

        return UserPermission::where('permission', $permission)
                        ->where('of_type', 'Aham\Models\SQL\Location')
                        ->where('user_id', $this->id)
                        ->pluck('of_id')
                        ->toArray();
    }

    public function city()
    {
        return $this->belongsTo('Aham\Models\SQL\City', 'city_id');
    }

    public function student()
    {
        return $this->hasOne('Aham\Models\SQL\Student', 'user_id');
    }

    public function teacher()
    {
        return $this->hasOne('Aham\Models\SQL\Teacher', 'user_id');
    }

    public function picture()
    {
        return $this->morphOne('Aham\Models\SQL\CloudinaryImage', 'of');
    }

    public function seriesEnrollments()
    {
        return $this->hasMany('Aham\Models\SQL\UserEnrollment', 'user_id')
                    ->where('method', 'credits');
    }

    public function pushTokens()
    {
        return $this->hasMany('Aham\Models\SQL\CloudMsgId', 'user_id');
    }

    public function getSystemUserId()
    {
        dd('Iam here');

        try {
            if (class_exists($class = '\SleepingOwl\AdminAuth\Facades\AdminAuth')
                || class_exists($class = '\Cartalyst\Sentry\Facades\Laravel\Sentry')
                || class_exists($class = '\Cartalyst\Sentinel\Laravel\Facades\Sentinel')
            ) {
                return ($class::check()) ? $class::getUser()->id : null;
            } elseif (\Auth::check()) {
                return \Auth::user()->getAuthIdentifier();
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }


    public function creditBuckets()
    {
        return $this->hasMany(CreditsBucket::class, 'user_id');
    }

    public function hubOnlyCredits()
    {
        return $this->hasMany(CreditsHubOnly::class, 'user_id');
    }

    public function promotionalCredits()
    {
        return $this->hasMany(CreditsPromotional::class, 'user_id');
    }

    public function purchasedCredits()
    {
        return $this->hasMany(CreditsPurchased::class, 'user_id');
    }

    public function refundedCredits()
    {
        return $this->hasMany(CreditsRefund::class, 'user_id');
    }

    public function usedCredits()
    {
        return $this->hasMany(CreditsUsed::class, 'user_id');
    }
}
