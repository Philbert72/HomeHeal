@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Edit Exercise</h1>
        <p class="text-slate-600 dark:text-slate-400">Update the details for {{ $exercise->name }}</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="px-8 py-6 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/30 dark:to-teal-900/30 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Exercise Details</h2>
        </div>

        <form action="{{ route('exercises.update', $exercise) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Exercise Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Exercise Name <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $exercise->name) }}"
                    required
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition dark:bg-slate-700 dark:text-white"
                >
            </div>

            <!-- Image URL Field -->
            <div>
                <label for="image_url" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Image URL (Vercel-Friendly)</label>
                <input 
                    type="url" 
                    id="image_url" 
                    name="image_url" 
                    value="{{ old('image_url', $exercise->image_url) }}"
                    placeholder="https://example.com/image.jpg"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition dark:bg-slate-700 dark:text-white"
                >
                @if($exercise->image_url)
                    <div class="mt-3">
                        <p class="text-xs text-slate-500 mb-2">Current Preview:</p>
                        <img src="{{ $exercise->image_url }}" class="w-32 h-24 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                    </div>
                @endif
            </div>

            <!-- Instructions -->
            <div>
                <label for="instructions" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Instructions <span class="text-red-500">*</span></label>
                <textarea 
                    id="instructions" 
                    name="instructions" 
                    required
                    rows="6"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition resize-none dark:bg-slate-700 dark:text-white"
                >{{ old('instructions', $exercise->instructions) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition shadow-sm">
                    Save Changes
                </button>
                <a href="{{ route('exercises.index') }}" class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection