@extends('layouts.app')

@section('content')
    @php
        $user = Auth::user();
    @endphp

    @if ($user && $user->role === 'therapist')
        <livewire:therapist-dashboard />
    @elseif ($user && $user->role === 'patient')
        <livewire:patient-dashboard />
    @else
        <div class="text-center py-20 bg-white rounded-lg shadow-md border border-slate-200">
            <h2 class="text-2xl font-bold text-slate-900">Access Denied</h2>
            <p class="text-slate-600 mt-2">Please log in to view your dashboard.</p>
        </div>
    @endif
@endsection