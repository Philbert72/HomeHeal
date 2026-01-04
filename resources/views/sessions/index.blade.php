@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">My Session History</h1>
            <p class="text-slate-600 dark:text-slate-400">View and manage your complete rehabilitation journey.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-medium rounded-lg transition">
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Date & Protocol</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Assessment</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Difficulty</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Notes</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition group">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $session->log_date->format('M d, Y') }}</div>
                                <div class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $session->protocol->title ?? 'Unassigned Protocol' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Pain:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $session->pain_score > 5 ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300' }}">
                                        {{ $session->pain_score }}/10
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        @if ($session->difficulty_rating <= 2) Easy 
                                        @elseif ($session->difficulty_rating <= 4) Moderate 
                                        @else Hard 
                                        @endif
                                    </span>
                                    <span class="text-xs text-slate-400">({{ $session->difficulty_rating }}/5)</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300 max-w-xs truncate">
                                {{ $session->notes ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('sessions.edit', $session) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    Edit Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="text-lg font-medium text-slate-900 dark:text-white">No sessions found</p>
                                    <p class="text-sm mt-1">You haven't logged any therapy sessions yet.</p>
                                    <a href="{{ route('sessions.create') }}" class="mt-4 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">Log First Session</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sessions->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/30 border-t border-slate-200 dark:border-slate-700">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
