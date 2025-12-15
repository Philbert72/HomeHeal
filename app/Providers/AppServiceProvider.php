<?php

namespace App\Providers;

use App\Livewire\TherapistDashboard;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

use App\Models\Protocol;
use App\Models\DailySessionLog;
use App\Policies\ProtocolPolicy;
use App\Policies\DailySessionLogPolicy;

use App\Livewire\PatientDashboard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     * Define them here if AuthServiceProvider is missing.
     * Note: PHP resolves the fully qualified class names from the use statements above.
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
    public function boot(): void
    {
        // Explicitly register policies via Gate::policy for reliability
        foreach ($this->policies as $model => $policy) {
            
            // This is the clean, recommended way to register a policy
            Gate::policy($model, $policy);
            
        }
        
        // CRITICAL FIX: Register the PatientDashboard Livewire Component
        // Maps the component tag <livewire:patient-dashboard /> to the PHP class
        Livewire::component('patient-dashboard', PatientDashboard::class);
        Livewire::component('therapist-dashboard', TherapistDashboard::class);
    }
}