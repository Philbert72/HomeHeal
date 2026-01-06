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
        $this->sessionsCompleted = $this->user->dailySessionLogs()->count();

        $last30DaysLogs = $this->user->dailySessionLogs()
                                     ->where('log_date', '>=', now()->subDays(30))
                                     ->get();

        if ($last30DaysLogs->isNotEmpty()) {
            $this->averagePainScore = round($last30DaysLogs->avg('pain_score'), 1);
        }
    }

    private function loadDailyChecklist()
    {
        $allProtocols = $this->user->protocols()->with('therapist')->get();
        
        $checklist = [];

        foreach ($allProtocols as $protocol) {
            $assignedAt = $protocol->pivot->created_at;
            $durationDays = $protocol->pivot->duration_days ?? 30;

            $endDate = $assignedAt->copy()->addDays($durationDays);

            $isActive = now()->startOfDay()->lte($endDate->endOfDay());

            if ($isActive) {
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
        $this->recentSessions = $this->user->dailySessionLogs()
                                           ->with('protocol')
                                           ->latest('log_date')
                                           ->take(5)
                                           ->get();
    }

    private function loadChartData()
    {
        $painLogs = $this->user->dailySessionLogs()
                               ->where('log_date', '>=', now()->subDays(30))
                               ->orderBy('log_date')
                               ->get(['log_date', 'pain_score']);

        $this->painChartData = [
            'labels' => $painLogs->pluck('log_date')->map(fn($date) => $date->format('M j'))->toArray(),
            'data' => $painLogs->pluck('pain_score')->toArray(),
        ];

        $weeklyData = $this->user->dailySessionLogs()
                                 ->where('log_date', '>=', now()->subWeeks(8))
                                 ->get()
                                 ->groupBy(function($session) {
                                     return $session->log_date->format('Y-W');
                                 })
                                 ->map(function($group) {
                                     return $group->count();
                                 });

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
        return view('livewire.patient-dashboard');
    }
}