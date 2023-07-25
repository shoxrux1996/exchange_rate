<?php

namespace App\Repositories;

use App\Clients\ClientFactory;
use App\Contracts\GetExchangeRatesClientInterface;
use App\Contracts\GetExchangeRateRepositoryInterface;
use App\Models\RateModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class GetExchangeRateRepository implements GetExchangeRateRepositoryInterface
{
    private $client;

    public function getRate(Carbon $date, string $quoteCurrencyCode, string $baseCurrencyCode = 'RUR')
    {
        //Cbr only supports RUR as a base currency. If base currency is not RUR,
        //it will threw an error with message 'Unsupported client'.
        $client = $baseCurrencyCode == 'RUR' ? 'cbr-xml' : 'undefined';

        $this->client = (new ClientFactory())->initializeClient($client);

        return Cache::rememberForever("exchange_{$baseCurrencyCode}_{$quoteCurrencyCode}" . $date->format('Y-m-d'), function () use ($date, $quoteCurrencyCode, $baseCurrencyCode) {
            return $this->client->getRateByDate($date, $quoteCurrencyCode, $baseCurrencyCode);
        });
    }

    public function getRateDayBeforeWithDifference(Carbon $date, string $quoteCurrencyCode, string $baseCurrencyCode = 'RUR'): array
    {
        $rate = $this->getRate($date, $quoteCurrencyCode, $baseCurrencyCode);
        $rateDayBefore = $this->getRate($date->copy()->subDay(), $quoteCurrencyCode, $baseCurrencyCode);
        $difference = $rate->rate - ($rateDayBefore->rate ?? 0);
        $prefix = $difference > 0 ? '+' : '-';

        return [number_format($rate->rate, 6), $prefix . number_format($difference, 6)];
    }
}
