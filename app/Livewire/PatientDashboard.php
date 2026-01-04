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
    // public $currentProtocol = null; // Removed in favor of checklist
    public $dailyChecklist = [];
    public $recentSessions = [];
    public $painChartData = [];
    public $sessionFrequencyData = [];
    public $difficultyChartData = [];

    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user || $this->user->role !== 'patient') {
            return;
        }

        $this->loadMetrics();
        $this->loadDailyChecklist();
        $this->loadRecentSessions();
        $this->loadChartData();
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
    }

    private function loadDailyChecklist()
    {
        // Fetch all assigned protocols with pivot data
        $allProtocols = $this->user->protocols()->with('therapist')->get();
        
        $checklist = [];

        foreach ($allProtocols as $protocol) {
            $assignedAt = $protocol->pivot->created_at;
            $durationDays = $protocol->pivot->duration_days ?? 30; // Default to 30 days if null
            
            // Calculate end date
            $endDate = $assignedAt->copy()->addDays($durationDays);

            // Check if active (today is before or equal to end date)
            $isActive = now()->startOfDay()->lte($endDate->endOfDay());

            if ($isActive) {
                // Check if completed today
                $completedToday = $this->user->dailySessionLogs()
                    ->where('protocol_id', $protocol->id)
                    ->whereDate('log_date', now()->today())
                    ->exists();

                $checklist[] = [
                    'protocol' => $protocol,
                    'assigned_at' => $assignedAt,
                    'end_date' => $endDate,
                    'completed_today' => $completedToday,
                    'days_remaining' => (int) ceil(now()->floatDiffInDays($endDate, false)),
                ];
            }
        }

        $this->dailyChecklist = $checklist;
    }

    private function loadRecentSessions()
    {
        // Get the 5 most recent session logs
        $this->recentSessions = $this->user->dailySessionLogs()
                                           ->with('protocol')
                                           ->latest('log_date')
                                           ->take(5)
                                           ->get();
    }

    private function loadChartData()
    {
        // Pain Score Chart Data (Last 30 days)
        $painLogs = $this->user->dailySessionLogs()
                               ->where('log_date', '>=', now()->subDays(30))
                               ->orderBy('log_date')
                               ->get(['log_date', 'pain_score']);

        $this->painChartData = [
            'labels' => $painLogs->pluck('log_date')->map(fn($date) => $date->format('M j'))->toArray(),
            'data' => $painLogs->pluck('pain_score')->toArray(),
        ];

        // Session Frequency (Last 8 weeks, grouped by week)
        $weeklyData = $this->user->dailySessionLogs()
                                 ->where('log_date', '>=', now()->subWeeks(8))
                                 ->get()
                                 ->groupBy(function($session) {
                                     return $session->log_date->format('Y-W'); // Group by year-week
                                 })
                                 ->map(function($group) {
                                     return $group->count();
                                 });

        // Generate last 8 weeks labels
        $weekLabels = [];
        $weekCounts = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekKey = $weekStart->format('Y-W');
            $weekLabels[] = $weekStart->format('M j');
            $weekCounts[] = $weeklyData->get($weekKey, 0);
        }

        $this->sessionFrequencyData = [
            'labels' => $weekLabels,
            'data' => $weekCounts,
        ];

        // Difficulty Trend Chart (Last 30 days)
        $difficultyLogs = $this->user->dailySessionLogs()
                                     ->where('log_date', '>=', now()->subDays(30))
                                     ->orderBy('log_date')
                                     ->get(['log_date', 'difficulty_rating']);

        $this->difficultyChartData = [
            'labels' => $difficultyLogs->pluck('log_date')->map(fn($date) => $date->format('M j'))->toArray(),
            'data' => $difficultyLogs->pluck('difficulty_rating')->toArray(),
        ];
    }

    public function render()
    {
        // This links to the component view file
        return view('livewire.patient-dashboard');
    }
}