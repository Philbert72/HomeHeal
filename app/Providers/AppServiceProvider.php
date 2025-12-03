<?php

namespace App\Providers;

use App\Models\DailySessionLog;
use App\Policies\DailySessionLogPolicy;
use App\Policies\ProtocolPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        Protocol::class => ProtocolPolicy::class,
        DailySessionLog::class => DailySessionLogPolicy::class
    ];

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
        foreach ($this->policies as $model => $policy) {
            // Get the Policy methods (e.g., 'view', 'create', 'update', 'delete')
            $methods = get_class_methods($policy);

            foreach ($methods as $method) {
                // Skip constructor and other internal methods
                if (in_array($method, ['__construct', 'before', 'after'])) {
                    continue;
                }

                // Define the Gate: 'view-App\Models\Protocol'
                // This is less common but a powerful way to define policies via Gates.
                Gate::policy($model, $policy);
            }
        }
    }
}
