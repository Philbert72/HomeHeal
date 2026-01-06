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
    public $search = '';
    public $sortBy = 'latest'; // latest, oldest, name

    public function mount()
    {
        $this->user = Auth::user();
        if (!$this->user) {
            return; // Not logged in
        }
        $this->isTherapist = $this->user->role === 'therapist';
        $this->loadProtocols();
    }

    public function updatedSearch()
    {
        $this->loadProtocols();
    }

    public function updatedSortBy()
    {
        $this->loadProtocols();
    }

    private function loadProtocols()
    {
        if ($this->isTherapist) {
            $query = $this->user->createdProtocols()
                ->with(['therapist', 'patients'])
                ->withCount('patients');

            if ($this->search) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }

            switch ($this->sortBy) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'name':
                    $query->orderBy('title');
                    break;
                default:
                    $query->latest();
            }

            $this->protocols = $query->get();
        } else {
            $this->protocols = $this->user->protocols()
                ->with(['therapist'])
                ->latest('pivot_created_at')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.protocol-index');
    }
}