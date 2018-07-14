<?php

namespace Aham\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class EpisodeTiming extends RevSoftModel
{
    protected $table = 'episode_timings';

    protected $guarded = [];

    protected $dates = ['date'];

    public function episode()
    {
    	return $this->belongsTo('Aham\Models\SQL\GuestSeriesEpisode','episode_id');
    }

    public function slot()
    {
    	return $this->belongsTo('Aham\Models\SQL\Slot','slot_id');
    }

    public function classroom()
    {
        return $this->belongsTo('Aham\Models\SQL\Classroom','classroom_id');
    }

    public function series()
    {
        return $this->belongsTo('Aham\Models\SQL\GuestSeries','series_id');
    }
}
