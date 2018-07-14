<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class ClassroomSlot extends RevSoftModel
{
    protected $table = 'classroom_slots';

    protected $guarded = [];

    public function slot()
    {
    	return $this->belongsTo('Aham\Models\SQL\Slot','slot_id');
    }

    public function dayType()
    {
    	return $this->belongsTo('Aham\Models\SQL\DayType','day_type_id');
    }

	public function getStartTimeAttribute($value)
    {
        return \Carbon::createFromTimeStamp(strtotime($value))->format('H:i');
    }

	public function getEndTimeAttribute($value)
    {
        return \Carbon::createFromTimeStamp(strtotime($value))->format('H:i');
    }
}
