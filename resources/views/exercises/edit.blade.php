@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Edit Exercise</h1>
        <p class="text-slate-600 dark:text-slate-400">Update details for {{ $exercise->name }}</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <form action="{{ route('exercises.update', $exercise) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Exercise Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Exercise Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" required value="{{ old('name', $exercise->name) }}" class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-700 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Image URL -->
            <div>
                <label for="image_url" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Image URL (Vercel-Friendly)</label>
                <input 
                    type="url" 
                    id="image_url" 
                    name="image_url" 
                    value="{{ old('image_url', $exercise->image_url) }}"
                    placeholder="https://example.com/image.jpg"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-700 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500 transition"
                >
            </div>

            <!-- Video URL (Restored) -->
            <div>
                <label for="video_url" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Video URL</label>
                <input 
                    type="url" 
                    id="video_url" 
                    name="video_url" 
                    value="{{ old('video_url', $exercise->video_url) }}"
                    placeholder="https://www.youtube.com/watch?v=..."
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-700 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500 transition"
                >
            </div>

            <!-- Instructions -->
            <div>
                <label for="instructions" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Instructions <span class="text-red-500">*</span></label>
                <textarea id="instructions" name="instructions" required rows="6" class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-700 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500 resize-none transition">{{ old('instructions', $exercise->instructions) }}</textarea>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition shadow-md">
                    Update Exercise
                </button>
                <a href="{{ route('exercises.index') }}" class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection