@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-xl mx-auto">
    <!-- Header -->
    <div class="flex items-start justify-between">
        <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-2">Assign Protocol</h1>
        <a href="{{ route('protocols.show', $protocol) }}" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">← Back to Protocol</a>
    </div>

    <!-- Protocol Summary -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow border border-slate-200 dark:border-slate-700">
        <h2 class="text-xl font-bold text-indigo-700 dark:text-indigo-400">{{ $protocol->title }}</h2>
        <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">{{ $protocol->description }}</p>
    </div>

    <!-- Assignment Form -->
    <form action="{{ route('protocols.processAssignment', $protocol) }}" method="POST" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 p-8 space-y-6">
        @csrf
        
        <h3 class="text-2xl font-semibold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Assignment Details</h3>

        <!-- Duration Input -->
        <div>
            <label for="duration_days" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">Duration (Days)</label>
            <div class="relative">
                <input 
                    type="number" 
                    id="duration_days" 
                    name="duration_days" 
                    value="{{ old('duration_days', 30) }}"
                    min="1" 
                    max="365"
                    class="w-full pl-4 pr-16 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition dark:text-white"
                >
                <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                    <span class="text-slate-500 dark:text-slate-400">days</span>
                </div>
            </div>
            @error('duration_days') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Default is 30 days. Patients will see this in their daily checklist for this duration.</p>
        </div>

        <h3 class="text-2xl font-semibold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2 pt-4">Select Patients to Assign</h3>
        
        @if ($allPatients->isEmpty())
            <div class="bg-yellow-100 dark:bg-yellow-900/40 border-l-4 border-yellow-500 text-yellow-700 dark:text-yellow-300 p-4 rounded" role="alert">
                <p class="font-bold">No Patients Found</p>
                <p>Please create patient user accounts before assigning protocols.</p>
            </div>
        @else
            <!-- Patients List (Checkboxes) -->
            <div class="space-y-3 max-h-80 overflow-y-auto p-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-900">
                @foreach ($allPatients as $patient)
                    <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <input type="checkbox" name="patients[]" value="{{ $patient->id }}" 
                                @checked(in_array($patient->id, $assignedPatientIds))
                                class="form-checkbox h-5 w-5 text-indigo-600 dark:text-indigo-500 rounded border-slate-300 dark:border-slate-600 focus:ring-indigo-500 bg-white dark:bg-slate-700">
                        
                        <div class="flex flex-col">
                            <span class="text-base font-medium text-slate-900 dark:text-white">{{ $patient->name }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $patient->email }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            @error('patients') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        @endif

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-md">
                Update Assignment
            </button>
        </div>
    </form>
</div>
@endsection