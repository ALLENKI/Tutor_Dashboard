<?php namespace Aham\Presenters;

use Karl456\Presenter\Presenter;

class GuestSeriesPresenter extends Presenter{

    public function picture()
    {
        if($this->entity->picture)
        {
            return $this->entity->picture->public_id.'.'.$this->entity->picture->format;
        }
        else
        {
            return 'aham_icon_m6ljr5.png';
        }
        
    }

}
