<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DailySessionLog;
use App\Models\Protocol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class PatientDashboard extends Component
{
    public $user;
    public $averagePainScore = 0;
    public $sessionsCompleted = 0;
    public $currentProtocol = null;
    public $recentSessions = [];

    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user || $this->user->role !== 'patient') {
            // Safety check: only load data if a patient is logged in
            return;
        }

        $this->loadMetrics();
        $this->loadRecentSessions();
    }

    private function loadMetrics()
    {
        // 1. Calculate Sessions Completed
        $this->sessionsCompleted = $this->user->dailySessionLogs()->count();

        // 2. Calculate Average Pain Score (Last 30 days)
        $last30DaysLogs = $this->user->dailySessionLogs()
                                     ->where('log_date', '>=', now()->subDays(30))
                                     ->get();

        if ($last30DaysLogs->isNotEmpty()) {
            $this->averagePainScore = round($last30DaysLogs->avg('pain_score'), 1);
        }

        // 3. Determine Current Protocol
        // We use the 'patients' relationship on the User model to find assigned protocols.
        // The relationship is defined on the Protocol model, so we need to ensure the inverse is set up on the User model
        // For now, we assume a relationship exists to fetch assigned protocols:
        $this->currentProtocol = $this->user->protocols()->with('therapist')->latest('pivot_created_at')->first();
    }

    private function loadRecentSessions()
    {
        // Get the 5 most recent session logs
        $this->recentSessions = $this->user->dailySessionLogs()
                                           ->with('protocol') // Load protocol name for display
                                           ->latest('log_date')
                                           ->take(5)
                                           ->get();
    }

    public function render()
    {
        // This links to the component view file
        return view('livewire.patient-dashboard');
    }
}