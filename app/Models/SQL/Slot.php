<?php

namespace Aham\Models\SQL;

use Karl456\Presenter\Traits\PresentableTrait;

class Slot extends RevSoftModel
{
    use PresentableTrait;

    protected $presenter = 'Aham\Presenters\SlotPresenter';

    protected $table = 'slots';

    protected $guarded = [];

    // protected $dates = ['start_time'];

    public function getStartTimeAttribute($value)
    {
        return \Carbon::createFromTimeStamp(strtotime($value))->format('H:i');
    }

    public function getEndTimeAttribute($value)
    {
        return \Carbon::createFromTimeStamp(strtotime($value))->format('H:i');
    }
}
