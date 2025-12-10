@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to HomeHeal</h1>
        <p class="text-lg text-gray-600 mb-8">
            Your secure companion for injury recovery and physical therapy tracking.
        </p>
        
        <div class="flex justify-center gap-4">
            <div class="p-6 bg-red-50 rounded-lg border border-red-100">
                <h3 class="font-bold text-red-800">For Patients</h3>
                <p class="text-sm text-red-600 mt-2">Track pain scores and view daily exercises.</p>
            </div>
            <div class="p-6 bg-blue-50 rounded-lg border border-blue-100">
                <h3 class="font-bold text-blue-800">For Therapists</h3>
                <p class="text-sm text-blue-600 mt-2">Assign protocols and monitor recovery progress.</p>
            </div>
        </div>
    </div>
</div>
@endsection