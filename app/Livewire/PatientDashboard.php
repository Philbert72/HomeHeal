<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DailySessionLog;
use App\Models\Protocol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PatientDashboard extends Component
{
    public $user;
    public $averagePainScore = 0;
    public $sessionsCompleted = 0;
    public $currentProtocol = null;
    public $recentSessions = [];
    public $hasLoggedToday = false; // CRITICAL: New property to control button state

    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user || $this->user->role !== 'patient') {
            return;
        }

        $this->loadMetrics();
        $this->loadRecentSessions();
        
        // CRITICAL FIX: Check if a log exists for today
        if ($this->currentProtocol) {
            $this->hasLoggedToday = DailySessionLog::where('patient_id', $this->user->id)
                                                  ->where('protocol_id', $this->currentProtocol->id)
                                                  ->whereDate('log_date', Carbon::today())
                                                  ->exists();
        }
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
        return view('livewire.patient-dashboard');
    }
}