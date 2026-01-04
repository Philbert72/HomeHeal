@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-teal-50 to-white dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="absolute inset-0 opacity-30 dark:opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgb(148 163 184) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="text-center space-y-8">
            <!-- Logo -->
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-white dark:bg-slate-800 rounded-full shadow-lg border border-emerald-100 dark:border-emerald-900">
                <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">H</span>
                </div>
                <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">Physical Therapy Platform</span>
            </div>

            <!-- Heading -->
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-slate-900 dark:text-white leading-tight">
                Your Recovery,<br>
                <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Our Priority</span>
            </h1>

            <p class="text-xl md:text-2xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                Professional physical therapy management platform designed to help patients track their recovery and therapists create effective rehabilitation protocols.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Get Started Free
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-semibold rounded-xl border-2 border-slate-200 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition">
                    Sign In
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="bg-white dark:bg-slate-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- For Patients -->
            <div class="group bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-slate-900 dark:to-slate-800 rounded-3xl p-8 border border-emerald-100 dark:border-emerald-900 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">For Patients</h3>
                <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">Track your recovery journey with easy-to-use tools. Log sessions, monitor pain levels, and visualize your progress with interactive charts.</p>
                <ul class="space-y-3">
                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Interactive progress charts</span>
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Session logging with pain tracking</span>
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Personalized exercise protocols</span>
                    </li>
                </ul>
            </div>

            <!-- For Therapists -->
            <div class="group bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-slate-900 dark:to-slate-800 rounded-3xl p-8 border border-teal-100 dark:border-teal-900 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">For Therapists</h3>
                <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">Create and manage comprehensive rehabilitation protocols. Monitor patient progress and adjust treatment plans with data-driven insights.</p>
                <ul class="space-y-3">
                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                        <svg class="w-5 h-5 text-teal-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Protocol builder with exercises</span>
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                        <svg class="w-5 h-5 text-teal-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Exercise library management</span>
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                        <svg class="w-5 h-5 text-teal-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Patient progress monitoring</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-slate-50 dark:bg-slate-900 border-y border-slate-200 dark:border-slate-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-2">100%</div>
                <div class="text-sm text-slate-600 dark:text-slate-400">Free to Use</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-2">24/7</div>
                <div class="text-sm text-slate-600 dark:text-slate-400">Access Anytime</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-2">∞</div>
                <div class="text-sm text-slate-600 dark:text-slate-400">Unlimited Tracking</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-2">🔒</div>
                <div class="text-sm text-slate-600 dark:text-slate-400">Secure & Private</div>
            </div>
        </div>
    </div>
</div>
@endsection
