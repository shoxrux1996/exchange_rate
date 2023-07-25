<?php

namespace App\Jobs;

use App\Contracts\GetExchangeRateRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ExchangeRateDataCollectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $date;
    private $quote;
    private $base;
    /**
     * Create a new job instance.
     */
    public function __construct(Carbon $date, $quote, $base)
    {
        $this->date = $date;
        $this->quote = $quote;
        $this->base = $base;
    }

    /**
     * Execute the job.
     */
    public function handle(GetExchangeRateRepositoryInterface $getExchangeRepo): void
    {
        try {
            $rate = $getExchangeRepo->getRateDayBeforeWithDifference($this->date, $this->quote, $this->base);

            Storage::put("Rates/{$this->base}/{$this->quote}/{$this->date->format('Y-m-d')}.txt", json_encode($rate));
            logger()->info("{$this->base}/{$this->quote}:{$this->date->format('Y-m-d')}:" . json_encode($rate));
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }
}
