<?php

namespace App\Models;

class RateModel
{
    public $date;
    public $rate;
    public $quoteCurrencyCode;
    public $baseCurrencyCode;
    public $previousDayRate;

    public function __construct($date, $rate, $quoteCurrencyCode, $baseCurrencyCode = 'RUR')
    {
        $this->date = $date;
        $this->rate = $rate;
        $this->quoteCurrencyCode = $quoteCurrencyCode;
        $this->baseCurrencyCode = $baseCurrencyCode;
    }
}
