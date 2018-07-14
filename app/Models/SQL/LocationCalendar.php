<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class LocationCalendar extends RevSoftModel
{

    protected $table = 'location_calendar';

    protected $guarded = [];

    protected $dates = ['from_date','to_date'];

    public function location()
    {
    	return $this->belongsTo('Aham\Models\SQL\Location','location_id');
    }

    public function dayType()
    {
    	return $this->belongsTo('Aham\Models\SQL\DayType','day_type_id');
    }
}
