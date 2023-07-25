<?php

namespace App\Contracts;

use App\Models\RateModel;
use Illuminate\Support\Carbon;

interface GetExchangeRatesClientInterface
{
    public function getRateByDate(Carbon $date, string $quoteCurrencyCode, string $baseCurrencyCode);
}
