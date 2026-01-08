<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Force HTTPS for all generated URLs when running in production or on Vercel.
         * This prevents "Mixed Content" errors where the browser blocks HTTP assets on an HTTPS site.
         */
        if (config('app.env') === 'production' || env('VERCEL')) {
            URL::forceScheme('https');
        }
    }
}