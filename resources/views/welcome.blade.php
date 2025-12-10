@extends('layouts.app')

@section('content')
<!-- Redesigned welcome page with hero section and improved card layout -->
<div class="space-y-16">
    <!-- Hero Section -->
    <div class="text-center space-y-6">
        <div class="space-y-4">
            <h1 class="text-5xl sm:text-6xl font-bold text-slate-900">
                <span class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 bg-clip-text text-transparent">Recovery</span> Made Simple
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                Your intelligent companion for injury recovery and physical therapy tracking. Track progress, manage exercises, and recover faster.
            </p>
        </div>
        <div class="flex justify-center gap-4 pt-4">
            <a href="{{ route('register') }}" class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-medium rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-lg">Get Started</a>
            <a href="{{ route('login') }}" class="px-8 py-3 border-2 border-slate-300 text-slate-900 font-medium rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition">Sign In</a>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto w-full">
        <div class="bg-white rounded-2xl p-8 border border-slate-200 hover:shadow-lg hover:border-emerald-200 transition">
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2 1m0 0l-2-1m2 1v2.5"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">For Patients</h3>
            <p class="text-slate-600">Track your pain levels, log daily exercises, and visualize your progress over time with intuitive dashboards.</p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 border border-slate-200 hover:shadow-lg hover:border-teal-200 transition">
            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">For Therapists</h3>
            <p class="text-slate-600">Assign personalized protocols, monitor patient recovery in real-time, and adjust treatment plans as needed.</p>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid md:grid-cols-3 gap-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-12 border border-emerald-200">
        <div class="text-center">
            <div class="text-4xl font-bold text-emerald-600 mb-2">500+</div>
            <div class="text-slate-600">Active Users</div>
        </div>
        <div class="text-center border-x border-slate-200">
            <div class="text-4xl font-bold text-teal-600 mb-2">98%</div>
            <div class="text-slate-600">Recovery Rate</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold text-cyan-600 mb-2">12K+</div>
            <div class="text-slate-600">Sessions Logged</div>
        </div>
    </div>
</div>
@endsection
