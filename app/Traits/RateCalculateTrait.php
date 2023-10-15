<?php

namespace App\Traits;

trait RateCalculateTrait
{
    public function rateCalculate($rate,$amount)
    {
        $withdrawalRates = $rate;
        $withdrawalFee = $amount * $withdrawalRates;
        return $withdrawalFee;
    }
}
