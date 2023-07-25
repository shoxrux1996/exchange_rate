<?php

namespace App\Console\Commands;

use App\Jobs\ExchangeRateDataCollectionJob;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ExchangeRateDataCollectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:exchange-rate-data-collection {quote} {base=RUR} {days=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collecting exchange rates with given range of days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->argument('days', 1);

        if ($days < 1) {
            return;
        }

        for ($i = 0; $i < $days; $i++) {
            $date = $i == 0 ? now() : now()->subDays($i);
            ExchangeRateDataCollectionJob::dispatch($date, Str::upper($this->argument('quote')), $this->argument('base'));
        }
    }
}
