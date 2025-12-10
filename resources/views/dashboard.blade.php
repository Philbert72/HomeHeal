@extends('layouts.app')

@section('content')
<!-- Completely redesigned dashboard with better stats cards and visual hierarchy -->
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Your Recovery Dashboard</h1>
            <p class="text-slate-600">Track your progress and manage your therapy</p>
        </div>
        <a href="{{ route('sessions.create') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
            + Log Session
        </a>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-8 border border-slate-200 hover:shadow-lg hover:border-emerald-200 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium mb-2">Average Pain Score</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-slate-900">3.2</span>
                        <span class="text-sm font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">/10</span>
                    </div>
                    <p class="text-emerald-600 text-sm mt-3 font-medium">↓ 0.8 this week</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-8 border border-slate-200 hover:shadow-lg hover:border-teal-200 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium mb-2">Sessions Completed</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-slate-900">12</span>
                        <span class="text-sm text-slate-500">sessions</span>
                    </div>
                    <p class="text-slate-500 text-sm mt-3">This month: 8 sessions</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-8 border border-slate-200 hover:shadow-lg hover:border-cyan-200 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium mb-2">Current Protocol</p>
                    <div class="text-2xl font-bold text-slate-900 mt-2">ACL Rehab</div>
                    <p class="text-cyan-600 text-sm mt-3 font-medium">Phase 2 - Week 3/8</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sessions -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-900">Recent Session Logs</h2>
        </div>
        <div class="divide-y divide-slate-200">
            <div class="px-8 py-6 hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-slate-900">Morning Routine - Leg Day</h3>
                        <p class="text-sm text-slate-500 mt-1">Logged 2 days ago</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Completed
                        </span>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Pain Score:</span>
                        <span class="font-semibold text-slate-900">4/10</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Difficulty:</span>
                        <span class="font-semibold text-slate-900">Moderate</span>
                    </div>
                </div>
            </div>

            <div class="px-8 py-6 hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-slate-900">Evening Stretch</h3>
                        <p class="text-sm text-slate-500 mt-1">Logged 3 days ago</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Completed
                        </span>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Pain Score:</span>
                        <span class="font-semibold text-slate-900">2/10</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Difficulty:</span>
                        <span class="font-semibold text-slate-900">Easy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
