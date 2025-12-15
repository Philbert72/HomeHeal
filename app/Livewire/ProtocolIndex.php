<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Protocol;
use Illuminate\Support\Facades\Auth;

class ProtocolIndex extends Component
{
    public $protocols = [];
    public $user;
    public $isTherapist;

    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user) {
            return; // Not logged in
        }
        $this->isTherapist = $this->user->role === 'therapist';
        $this->loadProtocols();
    }

    private function loadProtocols()
    {
        if ($this->isTherapist) {
            // Therapist: Load all protocols they created, with patient counts.
            $this->protocols = $this->user->createdProtocols()
                ->with(['therapist', 'patients']) // Eager load relationships
                ->withCount('patients')
                ->latest()
                ->get();
        } else {
            // Patient: Load all protocols they are assigned to.
            $this->protocols = $this->user->protocols()
                ->with(['therapist'])
                ->latest('pivot_created_at') // Sort by assignment date
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.protocol-index');
    }
}