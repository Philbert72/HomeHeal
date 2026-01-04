<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-start">
        <div>
            <!-- Dynamically greet the user -->
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Welcome Back, {{ $user->name ?? 'Guest' }}!</h1>
            <p class="text-slate-600 dark:text-slate-400">Track your progress and manage your therapy</p>
        </div>
        
        <!-- Only show Log Session button if user is a Patient -->
        @if ($user && $user->role === 'patient')
            <a href="{{ route('sessions.create') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                + Log Session
            </a>
        @endif
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-emerald-200 dark:hover:border-emerald-900 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">Average Pain Score (30 Days)</p>
                    <div class="flex items-baseline gap-2">
                        <!-- DYNAMIC VALUE: Average Pain Score -->
                        <span class="text-4xl font-bold text-slate-900 dark:text-white">{{ $averagePainScore }}</span>
                        <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-lg">/10</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-3 font-medium">Data based on {{ $sessionsCompleted }} logs</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-teal-200 dark:hover:border-teal-900 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">Total Sessions Logged</p>
                    <div class="flex items-baseline gap-2">
                        <!-- DYNAMIC VALUE: Total Sessions -->
                        <span class="text-4xl font-bold text-slate-900 dark:text-white">{{ $sessionsCompleted }}</span>
                        <span class="text-sm text-slate-500 dark:text-slate-400">sessions</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-3">Data from all time</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-cyan-200 dark:hover:border-cyan-900 transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">Current Protocol</p>
                    <!-- DYNAMIC VALUE: Current Protocol Title -->
                    <div class="text-2xl font-bold text-slate-900 dark:text-white mt-2">
                        @if ($currentProtocol)
                            {{ $currentProtocol->title }}
                        @else
                            No Protocol Assigned
                        @endif
                    </div>
                    <p class="text-cyan-600 dark:text-cyan-400 text-sm mt-3 font-medium">
                        @if ($currentProtocol)
                            Assigned by Dr. {{ $currentProtocol->therapist->name ?? 'Unknown' }}
                        @else
                            Contact your therapist.
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Charts -->
    @if($sessionsCompleted > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Pain Score Trend Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Pain Score Trend</h3>
                <div class="h-64">
                    <canvas id="painChart"></canvas>
                </div>
            </div>

            <!-- Session Frequency Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Session Frequency</h3>
                <div class="h-64">
                    <canvas id="sessionChart"></canvas>
                </div>
            </div>

            <!-- Difficulty Trend Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Difficulty Trend</h3>
                <div class="h-64">
                    <canvas id="difficultyChart"></canvas>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Sessions -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Recent Session Logs</h2>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($recentSessions as $session)
                <div class="px-8 py-6 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white">{{ $session->protocol->title ?? 'Unassigned Protocol' }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Logged {{ $session->created_at->diffForHumans() }} ({{ $session->log_date->format('M j, Y') }})</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-sm font-medium rounded-full">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Completed
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-6">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Pain Score:</span>
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $session->pain_score }}/10</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Difficulty:</span>
                            <!-- Basic logic to map 1-5 to a word -->
                            <span class="font-semibold text-slate-900 dark:text-white">
                                @if ($session->difficulty_rating <= 2) Easy @elseif ($session->difficulty_rating <= 4) Moderate @else Hard @endif
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-8 py-6 text-slate-500 dark:text-slate-400 text-center">
                    No sessions logged yet. Log your first session to see progress!
                </div>
            @endforelse
        </div>
    </div>
</div>

@if($sessionsCompleted > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pain Score Chart
    const painCtx = document.getElementById('painChart');
    if (painCtx) {
        new Chart(painCtx, {
            type: 'line',
            data: {
                labels: @json($painChartData['labels']),
                datasets: [{
                    label: 'Pain Score',
                    data: @json($painChartData['data']),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10,
                        ticks: {
                            stepSize: 2
                        },
                        title: {
                            display: true,
                            text: 'Pain Level (0-10)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    }

    // Session Frequency Chart
    const sessionCtx = document.getElementById('sessionChart');
    if (sessionCtx) {
        new Chart(sessionCtx, {
            type: 'bar',
            data: {
                labels: @json($sessionFrequencyData['labels']),
                datasets: [{
                    label: 'Sessions',
                    data: @json($sessionFrequencyData['data']),
                    backgroundColor: 'rgba(20, 184, 166, 0.8)',
                    borderColor: 'rgb(20, 184, 166)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Number of Sessions'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Week Starting'
                        }
                    }
                }
            }
        });
    }

    // Difficulty Trend Chart
    const difficultyCtx = document.getElementById('difficultyChart');
    if (difficultyCtx) {
        new Chart(difficultyCtx, {
            type: 'line',
            data: {
                labels: @json($difficultyChartData['labels']),
                datasets: [{
                    label: 'Difficulty',
                    data: @json($difficultyChartData['data']),
                    borderColor: 'rgb(251, 146, 60)',
                    backgroundColor: 'rgba(251, 146, 60, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Difficulty (1-5)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endif
