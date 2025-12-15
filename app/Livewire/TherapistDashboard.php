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

    private function loadData()
    {
        // Fetch all protocols created by this therapist
        $this->createdProtocols = $this->user->createdProtocols()->withCount('patients')->get();

        // Calculate the total number of unique patients assigned across all protocols
        $this->assignedPatientsCount = $this->createdProtocols->sum('patients_count');
    }

    public function render()
    {
        return view('livewire.therapist-dashboard');
    }
}