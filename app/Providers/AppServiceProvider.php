<?php

namespace App\Providers;

use App\Models\DailySessionLog;
use App\Models\Protocol;
use App\Policies\DailySessionLogPolicy;
use App\Policies\ProtocolPolicy;
use Illuminate\Support\Facades\Gate;
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

        Gate::policy(Protocol::class, ProtocolPolicy::class);
        Gate::policy(DailySessionLog::class, DailySessionLogPolicy::class);
        
        URL::forceScheme('https');

        if (!empty(config('app.url'))) {
            URL::forceRootUrl(config('app.url'));
        }
    }
}