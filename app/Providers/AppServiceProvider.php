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
    protected $policies = [
        Protocol::class => ProtocolPolicy::class,
        DailySessionLog::class => DailySessionLogPolicy::class
    ];

    public function register(): void
    {
        //
    }

    public function boot(Router $router): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
        
        $router->aliasMiddleware('role', RoleMiddleware::class);
        
        Livewire::component('patient-dashboard', PatientDashboard::class);
        Livewire::component('therapist-dashboard', TherapistDashboard::class);
        Livewire::component('protocol-index', ProtocolIndex::class);
    }
}