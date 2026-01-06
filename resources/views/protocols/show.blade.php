@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <!-- Header and Nav -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-5xl font-extrabold text-slate-900 dark:text-white mb-2">{{ $protocol->title }}</h1>
            <p class="text-lg text-slate-600 dark:text-slate-400">{{ $protocol->description }}</p>
        </div>
        
        <div class="flex flex-col items-end gap-3">
            <a href="{{ route('protocols.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">← Back to Protocols</a>
            
            @can('update', $protocol)
                <a href="{{ route('protocols.edit', $protocol) }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                    Edit Protocol
                </a>
                <a href="{{ route('protocols.assign', $protocol) }}" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow transition">
                    Assign/Unassign Patients
                </a>
            @endcan
        </div>
    </div>

    <!-- Metadata Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-slate-700 dark:text-slate-300 border-t border-b border-slate-200 dark:border-slate-700 py-6">
        <div class="space-y-1">
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Created By</p>
            <p class="text-base font-medium">Dr. {{ $protocol->therapist->name }}</p>
        </div>
        <div class="space-y-1">
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Date Created</p>
            <p class="text-base">{{ $protocol->created_at->format('M d, Y') }}</p>
        </div>
        <div class="space-y-1 md:col-span-2">
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Currently Assigned Patients ({{ $protocol->patients->count() }})</p>
            @if ($protocol->patients->isEmpty())
                <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-300 rounded-full">Not Currently Assigned</span>
            @else
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach ($protocol->patients as $patient)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 rounded-full">
                            {{ $patient->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Exercises List Section -->
    <section class="space-y-6">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Exercise Prescription</h2>

        <div class="divide-y divide-slate-200 dark:divide-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm">
            @forelse ($protocol->exercises as $index => $exercise)
                <div class="p-6 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-emerald-700 dark:text-emerald-400">
                            {{ $index + 1 }}. {{ $exercise->name }}
                        </h3>
                        @if (Auth::user()->role === 'patient')
                            <button class="px-3 py-1 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition">
                                Log Completion
                            </button>
                        @endif
                    </div>
                    
                    <!-- Prescription Details -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mt-4 p-4 bg-slate-100 dark:bg-slate-700/50 rounded-lg">
                        
                        <!-- Sets -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Sets</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $exercise->pivot->sets }}</p>
                        </div>
                        
                        <!-- Reps -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Reps</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $exercise->pivot->reps }}</p>
                        </div>
                        
                        <!-- Resistance -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Resistance</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">
                                @if ($exercise->pivot->resistance_amount > 0)
                                    {{ number_format($exercise->pivot->resistance_amount, 2) }} {{ $exercise->pivot->resistance_original_unit }}
                                @else
                                    Bodyweight / N/A
                                @endif
                            </p>
                        </div>

                        <!-- Rest Time -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Rest</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $exercise->pivot->rest_seconds }} seconds</p>
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Instructions:</h4>
                        <p class="text-sm text-slate-700 dark:text-slate-300 italic">{{ $exercise->instructions }}</p>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800">
                    No exercises have been added to this protocol yet.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection