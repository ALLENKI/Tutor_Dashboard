<?php namespace Aham\Presenters;

use Karl456\Presenter\Presenter;

class SlotPresenter extends Presenter{

    public function slotStyled()
    {
        return $this->entity->start_time.' - '.$this->entity->end_time;
    }

}
