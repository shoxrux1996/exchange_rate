<?php

namespace App\Contracts;

use Illuminate\Support\Carbon;

interface GetExchangeRateRepositoryInterface
{
    public function getRate(Carbon $date, string $quoteCurrencyCode, string $baseCurrencyCode);
    public function getRateDayBeforeWithDifference(Carbon $date, string $quoteCurrencyCode, string $baseCurrencyCode): array;

}
