@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Edit Exercise</h1>
        <p class="text-slate-600 dark:text-slate-400">Update exercise details</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-8 py-6 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/30 dark:to-teal-900/30 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Exercise Details</h2>
        </div>

        <form action="{{ route('exercises.update', $exercise) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
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
                    placeholder="e.g., Quad Sets, Heel Slides"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition dark:bg-slate-700 dark:text-white dark:[color-scheme:dark] @error('name') border-red-500 @enderror"
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Image -->
            @if($exercise->image_path)
                <div>
                    <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Current Image</label>
                    <img src="{{ asset('storage/' . $exercise->image_path) }}" alt="{{ $exercise->name }}" class="w-48 h-48 object-cover rounded-lg border border-slate-200 dark:border-slate-600">
                </div>
            @endif

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">{{ $exercise->image_path ? 'Replace Image' : 'Add Image' }} (Optional)</label>
                <input 
                    type="file" 
                    id="image" 
                    name="image"
                    accept="image/*"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition dark:bg-slate-700 dark:text-slate-300 dark:[color-scheme:dark] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/50 dark:file:text-emerald-400 @error('image') border-red-500 @enderror"
                >
                @error('image')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Accepted: JPG, PNG, GIF (max 2MB)</p>
            </div>

            <div class="space-y-2">
        <label for="video_url" class="block text-sm font-semibold text-slate-900 dark:text-white">Video URL <span class="text-slate-400 font-normal">(Optional)</span></label>
        <input 
            type="url" 
            id="video_url" 
            name="video_url" 
            value="{{ old('video_url', $exercise->video_url) }}"
            placeholder="e.g., https://youtube.com/watch?v=..."
            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition dark:bg-slate-700 dark:text-white dark:[color-scheme:dark] @error('video_url') border-red-500 @enderror"
        >
        @error('video_url')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Instructions -->
    <div class="space-y-2">
                <label for="instructions" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Instructions <span class="text-red-500">*</span></label>
                <textarea 
                    id="instructions" 
                    name="instructions" 
                    required
                    rows="8"
                    placeholder="Provide detailed step-by-step instructions for performing this exercise..."
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none dark:bg-slate-700 dark:text-white dark:[color-scheme:dark] @error('instructions') border-red-500 @enderror"
                >{{ old('instructions', $exercise->instructions) }}</textarea>
                @error('instructions')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Include setup, execution, and any safety tips</p>
            </div>

            <!-- Equipment Needed -->
            <div>
                <label for="equipment_needed" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Equipment Needed (Optional)</label>
                <textarea 
                    id="equipment_needed" 
                    name="equipment_needed" 
                    rows="2"
                    placeholder="e.g., Resistance band, yoga mat, chair for balance"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none dark:bg-slate-700 dark:text-white dark:[color-scheme:dark]"
                >{{ old('equipment_needed', $exercise->equipment_needed) }}</textarea>
            </div>

            <!-- Safety Warnings -->
            <div>
                <label for="safety_warnings" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Safety Warnings (Optional)</label>
                <textarea 
                    id="safety_warnings" 
                    name="safety_warnings" 
                    rows="2"
                    placeholder="e.g., Stop if you feel sharp pain, avoid if you have knee instability"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none dark:bg-slate-700 dark:text-white dark:[color-scheme:dark]"
                >{{ old('safety_warnings', $exercise->safety_warnings) }}</textarea>
            </div>

            <!-- Step by Step Guide -->
            <div>
                <label for="step_by_step_guide" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Step-by-Step Guide (Optional)</label>
                <textarea 
                    id="step_by_step_guide" 
                    name="step_by_step_guide" 
                    rows="4"
                    placeholder="1. Starting position...&#10;2. Movement phase...&#10;3. Return to start..."
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none dark:bg-slate-700 dark:text-white dark:[color-scheme:dark]"
                >{{ old('step_by_step_guide', $exercise->step_by_step_guide) }}</textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                    Update Exercise
                </button>
                <a href="{{ route('exercises.index') }}" class="px-6 py-3 border-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-semibold rounded-lg hover:border-slate-400 dark:hover:border-slate-500 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
