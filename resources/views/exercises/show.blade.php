@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">{{ $exercise->name }}</h1>
            <p class="text-slate-600 dark:text-slate-400">Exercise details and instructions</p>
        </div>
        @if(Auth::check() && Auth::user()->role === 'therapist')
            <div class="flex gap-2">
                <a href="{{ route('exercises.edit', $exercise) }}" class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-medium rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-800 transition">
                    Edit
                </a>
                <form method="POST" action="{{ route('exercises.destroy', $exercise) }}" onsubmit="return confirm('Are you sure you want to delete this exercise?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition">
                        Delete
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Exercise Details Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <!-- Exercise Image and Video -->
        <div class="p-8 pb-0">
            @if($exercise->image_path)
                <img src="{{ asset('storage/' . $exercise->image_path) }}" alt="{{ $exercise->name }}" class="w-full rounded-lg border border-slate-200 dark:border-slate-700">
            @else
                <div class="aspect-video w-full bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700">
                    <svg class="w-24 h-24 text-emerald-200 dark:text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif

            @if($exercise->video_url)
                <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">External Video Guide</label>
                    <a href="{{ $exercise->video_url }}" target="_blank" class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium break-all">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Watch Video Demonstration
                    </a>
                </div>
            @endif
        </div>

        <div class="px-8 py-6 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Instructions</h2>
        </div>

        <div class="p-8">
            <div class="prose dark:prose-invert max-w-none">
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">{{ $exercise->instructions }}</p>
            </div>

            <!-- Enhanced Information -->
            @if($exercise->equipment_needed || $exercise->safety_warnings || $exercise->step_by_step_guide)
                <div class="mt-8 space-y-6">
                    <!-- Equipment Needed -->
                    @if($exercise->equipment_needed)
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-1">Equipment Needed</h3>
                                    <p class="text-blue-700 dark:text-blue-200 text-sm whitespace-pre-line">{{ $exercise->equipment_needed }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Safety Warnings -->
                    @if($exercise->safety_warnings)
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-amber-900 dark:text-amber-300 mb-1">Safety Warnings</h3>
                                    <p class="text-amber-700 dark:text-amber-200 text-sm whitespace-pre-line">{{ $exercise->safety_warnings }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Step by Step Guide -->
                    @if($exercise->step_by_step_guide)
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-emerald-900 dark:text-emerald-300 mb-1">Step-by-Step Guide</h3>
                                    <p class="text-emerald-700 dark:text-emerald-200 text-sm whitespace-pre-line">{{ $exercise->step_by_step_guide }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Meta Information -->
        <div class="px-8 py-6 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-700">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-slate-600 dark:text-slate-400">Created:</span>
                    <span class="text-slate-900 dark:text-white font-medium ml-2">{{ $exercise->created_at->format('M j, Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-600 dark:text-slate-400">Last Updated:</span>
                    <span class="text-slate-900 dark:text-white font-medium ml-2">{{ $exercise->updated_at->format('M j, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('exercises.index') }}" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Exercise Library
        </a>
    </div>
</div>
@endsection
