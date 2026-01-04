@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $patient->name }}</h1>
            </div>
            <p class="text-slate-600 dark:text-slate-400 ml-9">{{ $patient->email }}</p>
        </div>
    </div>

    <!-- Assigned Protocols -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Assigned Protocols</h2>
        </div>
        <div class="p-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($patient->assignedProtocols as $protocol)
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 border border-slate-200 dark:border-slate-600 relative group" x-data="{ editing: false }">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-slate-900 dark:text-white">{{ $protocol->title }}</h3>
                        
                        <!-- Status Badge -->
                        @php
                            $assignedAt = $protocol->pivot->created_at;
                            $durationDays = $protocol->pivot->duration_days ?? 30;
                            $endDate = $assignedAt->copy()->addDays($durationDays);
                            $isActive = now()->startOfDay()->lte($endDate->endOfDay());
                            $completedToday = $patient->dailySessionLogs()
                                ->where('protocol_id', $protocol->id)
                                ->whereDate('log_date', now()->today())
                                ->exists();
                        @endphp

                        <div class="flex gap-2">
                            @if($completedToday)
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                    Done Today
                                </span>
                            @endif
                            <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $isActive ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' : 'bg-slate-200 text-slate-800 dark:bg-slate-600 dark:text-slate-300' }}">
                                {{ $isActive ? 'Active' : 'Expired' }}
                            </span>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 dark:text-slate-300 mb-4 line-clamp-2">{{ $protocol->description }}</p>
                    
                    <!-- Info / Edit Form -->
                    <div x-show="!editing">
                        <div class="flex justify-between items-center text-xs text-slate-500 dark:text-slate-400">
                            <span>{{ $durationDays }} Days ({{ floor(now()->floatDiffInDays($endDate, false)) }} remaining)</span>
                            <button @click="editing = true" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                Update
                            </button>
                        </div>
                    </div>

                    <div x-show="editing" x-cloak class="mt-2 pt-2 border-t border-slate-200 dark:border-slate-600">
                        <form action="{{ route('protocols.updatePatientAssignment', ['protocol' => $protocol, 'patient' => $patient]) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <div class="flex-1">
                                <label class="sr-only">Duration (Days)</label>
                                <input type="number" name="duration_days" value="{{ $durationDays }}" min="1" max="365" class="w-full px-2 py-1 text-sm border rounded dark:bg-slate-800 dark:border-slate-600 dark:text-white focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <button type="submit" class="p-1.5 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                            <button type="button" @click="editing = false" class="p-1.5 bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300 rounded hover:bg-slate-300 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-slate-500 dark:text-slate-400 py-4">
                    No protocols assigned.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Session History -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Session History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Date</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Protocol</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Pain (0-10)</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Difficulty (1-5)</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">
                                {{ $log->log_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                {{ $log->protocol->title ?? 'Deleted Protocol' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $log->pain_score > 5 ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300' }}">
                                    {{ $log->pain_score }}/10
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                {{ $log->difficulty_rating }}/5
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 text-sm max-w-xs break-words">
                                {{ $log->notes ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                No sessions logged yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/30 border-t border-slate-200 dark:border-slate-700">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
