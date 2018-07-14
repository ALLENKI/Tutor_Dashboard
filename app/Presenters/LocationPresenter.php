<?php namespace Aham\Presenters;

use Karl456\Presenter\Presenter;

class LocationPresenter extends Presenter{

    public function address()
    {
        $parts = [];

        $parts[] = $this->entity->street_address;
        $parts[] = $this->entity->landmark;
        $parts[] = $this->entity->locality->name;
        $parts[] = $this->entity->city->name;
        $parts[] = $this->entity->pincode;

        return implode(', ',$parts);
    }

    public function latlng()
    {
        return $this->entity->latitude.','.$this->entity->longitude;
    }

}
