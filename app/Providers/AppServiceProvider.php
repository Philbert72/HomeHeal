<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire; 
use Illuminate\Routing\Router;

use App\Models\Protocol;
use App\Models\DailySessionLog;
use App\Policies\ProtocolPolicy;
use App\Policies\DailySessionLogPolicy;

use App\Livewire\PatientDashboard;
use App\Livewire\TherapistDashboard;
use App\Livewire\ProtocolIndex;

use App\Http\Middleware\RoleMiddleware; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
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
    public function boot(Router $router): void // Inject Router
    {
        // Explicitly register policies via Gate::policy for reliability
        foreach ($this->policies as $model => $policy) {
            
            // This is the clean, recommended way to register a policy
            Gate::policy($model, $policy);
            
        }
        
        // CRITICAL FIX: Register the custom Role middleware alias directly here
        $router->aliasMiddleware('role', RoleMiddleware::class);
        
        // Register Livewire Components
        Livewire::component('patient-dashboard', PatientDashboard::class);
        Livewire::component('therapist-dashboard', TherapistDashboard::class);
        Livewire::component('protocol-index', ProtocolIndex::class);
    }
}