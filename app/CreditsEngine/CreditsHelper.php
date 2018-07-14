<?php

namespace Aham\CreditsEngine;

use Aham\CreditsEngine\Add;
use Aham\CreditsEngine\Used;
use Aham\CreditsEngine\Refund;
use Aham\Models\SQL\CreditsBucket;

class CreditsHelper
{

    public $addCredits;
    public $updateCredits;
    public $refundCredits;

    public function __construct()
    {
        $this->addCredits = new Add();
        $this->updateCredits = new Used();
        $this->refundCredits = new Refund();
    }

    public function add($array)
    {
       return $this->addCredits->add($array);
    }

    public function update($array)
    {
        return $this->updateCredits->update($array);
    }

    public function refund($array)
    {
        return $this->refundCredits->refund($array);
    }
    
}
