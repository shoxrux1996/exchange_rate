<?php

namespace App\Providers;

use App\Contracts\GetExchangeRateRepositoryInterface;
use App\Repositories\GetExchangeRateRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GetExchangeRateRepositoryInterface::class, GetExchangeRateRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
