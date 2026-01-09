@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">{{ $exercise->name }}</h1>
            <a href="{{ route('exercises.index') }}" class="text-emerald-600 hover:underline">← Back to Library</a>
        </div>
        @if(Auth::user()->role === 'therapist')
            <a href="{{ route('exercises.edit', $exercise) }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white font-medium rounded-lg hover:bg-slate-200 transition">Edit</a>
        @endif
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <!-- Display Image (Checking URL first, then local path) -->
        @if($exercise->image_url)
            <img src="{{ $exercise->image_url }}" alt="{{ $exercise->name }}" class="w-full h-80 object-cover border-b dark:border-slate-700">
        @elseif($exercise->image_path)
            <img src="{{ asset('storage/' . $exercise->image_path) }}" alt="{{ $exercise->name }}" class="w-full h-80 object-cover border-b dark:border-slate-700">
        @else
            <div class="w-full h-64 bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center border-b dark:border-slate-700">
                <span class="text-slate-400 font-medium italic">No visual guide available</span>
            </div>
        @endif

        <div class="p-8 space-y-6">
            <!-- Video Link -->
            @if($exercise->video_url)
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800 flex items-center justify-between">
                    <div>
                        <p class="text-emerald-800 dark:text-emerald-400 font-bold">Video Demonstration</p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-500">Watch the correct form for this exercise</p>
                    </div>
                    <a href="{{ $exercise->video_url }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition shadow-sm">
                        Watch Video
                    </a>
                </div>
            @endif

            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-3">Instructions</h3>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                    {{ $exercise->instructions }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection