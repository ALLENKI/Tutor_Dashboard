<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class UserEnrollment extends RevSoftModel
{
    protected $table = 'user_enrollments';

    protected $guarded = [];

    public function episode()
    {
    	return $this->belongsTo('Aham\Models\SQL\GuestSeriesEpisode','episode_id');
    }

    public function level()
    {
        return $this->belongsTo('Aham\Models\SQL\GuestSeriesLevel','episode_id');
    }

    public function user()
    {
    	return $this->belongsTo('Aham\Models\SQL\User','user_id');
    }


}
