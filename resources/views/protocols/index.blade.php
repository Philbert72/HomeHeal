@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 mb-2">My Recovery Protocols</h1>
            <p class="text-slate-600">View your assigned exercises and track your progress</p>
        </div>
        <div class="flex gap-2">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Active Plans
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-emerald-200 transition group">
            <div class="p-6 space-y-6">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        Active
                    </span>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-1">ACL Rehab - Phase 2</h3>
                    <p class="text-sm text-slate-500">Focus on strengthening and range of motion.</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 border border-slate-200">
                        DP
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Assigned by</p>
                        <p class="text-sm font-medium text-slate-900">Dr. Philbert</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-medium">
                        <span class="text-slate-600">Progress</span>
                        <span class="text-emerald-600">65%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <span class="text-xs font-medium text-slate-500">Last session: 2 days ago</span>
                <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">View Details →</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-amber-200 transition group">
            <div class="p-6 space-y-6">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        Pending Start
                    </span>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-1">Shoulder Mobility</h3>
                    <p class="text-sm text-slate-500">Basic rotation exercises and stretches.</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 border border-slate-200">
                        DS
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Assigned by</p>
                        <p class="text-sm font-medium text-slate-900">Dr. Sarah</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-medium">
                        <span class="text-slate-600">Progress</span>
                        <span class="text-slate-900">0%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <span class="text-xs font-medium text-slate-500">Assigned today</span>
                <a href="#" class="text-sm font-semibold text-amber-600 hover:text-amber-700 hover:underline">View Details →</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-blue-200 transition opacity-75 hover:opacity-100">
            <div class="p-6 space-y-6">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Completed
                    </span>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-1">Ankle Sprain Rehab</h3>
                    <p class="text-sm text-slate-500">Completed on Nov 15, 2024</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 border border-slate-200">
                        DP
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Assigned by</p>
                        <p class="text-sm font-medium text-slate-900">Dr. Philbert</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-medium">
                        <span class="text-slate-600">Progress</span>
                        <span class="text-blue-600">100%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <span class="text-xs font-medium text-slate-500">Archive</span>
                <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">History →</a>
            </div>
        </div>

    </div>
</div>
@endsection