@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">{{ $exercise->name }}</h1>
        <a href="{{ route('exercises.index') }}" class="text-emerald-600 hover:underline">← Back to Library</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        @if($exercise->image_url)
            <img src="{{ $exercise->image_url }}" alt="{{ $exercise->name }}" class="w-full h-80 object-cover">
        @elseif($exercise->image_path)
            <img src="{{ asset('storage/' . $exercise->image_path) }}" alt="{{ $exercise->name }}" class="w-full h-80 object-cover">
        @else
            <div class="w-full h-64 bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                <span class="text-slate-400">No image available</span>
            </div>
        @endif

        <div class="p-8">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b pb-2">Instructions</h3>
            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                {{ $exercise->instructions }}
            </p>
        </div>
    </div>
</div>
@endsection