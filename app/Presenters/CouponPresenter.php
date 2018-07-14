<?php namespace Aham\Presenters;

use Karl456\Presenter\Presenter;

class CouponPresenter extends Presenter{

    public function descriptionStyled()
    {
        if($this->entity->additional_type == 'additional_units')
        {
            $message = 'You will get additional '.round($this->entity->additional_value).' units. If you buy '.$this->entity->min_units_to_purchase.' or more units';

            return $message;
        }

        if($this->entity->additional_type == 'additional_percent')
        {
            $message = 'You will get additional '.round($this->entity->additional_value).' percent of units. If you buy '.$this->entity->min_units_to_purchase.' or more units';

            return $message;
        }
    }

}
