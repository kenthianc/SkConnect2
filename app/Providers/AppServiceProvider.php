<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Services\OllamaService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register OllamaService as singleton
        $this->app->singleton(OllamaService::class, function ($app) {
            return new OllamaService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 for pagination (or Tailwind if preferred)
        Paginator::useBootstrapFive();
        // For Tailwind: Paginator::useTailwind();
    }
}
