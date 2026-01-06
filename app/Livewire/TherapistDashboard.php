<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Protocol;
use Illuminate\Support\Facades\Auth;

class TherapistDashboard extends Component
{
    public $user;
    public $createdProtocols = [];
    public $assignedPatientsCount = 0;

    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user || $this->user->role !== 'therapist') {
            return;
        }

        $this->loadData();
    }

    public $patients = [];

    private function loadData()
    {
        $this->createdProtocols = $this->user->createdProtocols()->withCount('patients')->get();
        $this->assignedPatientsCount = $this->createdProtocols->sum('patients_count');

        $protocolIds = $this->createdProtocols->pluck('id');

        $this->patients = User::whereHas('assignedProtocols', function ($query) use ($protocolIds) {
            $query->whereIn('protocol_id', $protocolIds);
        })->with(['latestSessionLog', 'assignedProtocols' => function($q) use ($protocolIds) {
            $q->whereIn('protocol_id', $protocolIds);
        }])->get()->map(function ($patient) {
            $lastLog = $patient->latestSessionLog;
            
            $avgPain = $patient->dailySessionLogs()
                ->latest('log_date')
                ->take(5)
                ->avg('pain_score');

            $sessionsLastWeek = $patient->dailySessionLogs()
                ->where('log_date', '>=', now()->subDays(7))
                ->count();

            return [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'protocols' => $patient->assignedProtocols->pluck('title')->join(', '),
                'last_session' => $lastLog ? $lastLog->log_date->format('M d, Y') : 'Never',
                'avg_pain' => $avgPain ? number_format($avgPain, 1) : 'N/A',
                'sessions_last_week' => $sessionsLastWeek,
            ];
        });
    }

    public function render()
    {
        return view('livewire.therapist-dashboard');
    }
}