<?php namespace Aham\Presenters;

use Karl456\Presenter\Presenter;

class UserPresenter extends Presenter{

    public function picture()
    {
        if($this->entity->picture)
        {
            return $this->entity->picture->public_id.'.jpg';
        }
        else
        {
            return 'aham_icon_m6ljr5.jpg';
        }
        
    }
}
