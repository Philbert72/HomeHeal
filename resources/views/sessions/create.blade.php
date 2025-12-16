@extends('layouts.app')

@section('content')
<!-- Redesigned session logging form with better visual sections -->
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Log Today's Session</h1>
        <p class="text-slate-600">Protocol: <span class="font-semibold text-emerald-600">{{ $protocol->title }}</span></p>
    </div>

    <!-- Error Display -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 text-sm" role="alert">
            <strong class="font-bold">Validation Error!</strong>
            <span class="block sm:inline">Please check the required fields.</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-lg">
        <div class="px-8 py-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-900">1. Exercise Checklist</h2>
        </div>

        <form action="{{ route('sessions.store') }}" method="POST" class="p-8 space-y-8">
            @csrf
            
            <!-- Hidden field to send the protocol ID -->
            <input type="hidden" name="protocol_id" value="{{ $protocol->id }}">

            <!-- Exercise Checklist Section -->
            <div class="space-y-4">
                @forelse ($protocol->exercises as $exercise)
                    @php
                        // Format prescription details
                        $sets = $exercise->pivot->sets;
                        $reps = $exercise->pivot->reps;
                        $resistance = $exercise->pivot->resistance_amount > 0 
                                      ? ' with ' . number_format($exercise->pivot->resistance_amount, 1) . ' ' . $exercise->pivot->resistance_original_unit 
                                      : '';
                        $prescription = "{$sets} sets x {$reps} reps{$resistance}";
                    @endphp

                    <label for="ex-{{ $exercise->id }}" class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition cursor-pointer">
                        <input id="ex-{{ $exercise->id }}" name="exercises[]" value="{{ $exercise->id }}" type="checkbox" 
                               class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 cursor-pointer mt-1">
                        <div class="flex-1">
                            <span class="font-semibold text-slate-900 block">{{ $exercise->name }} ({{ $prescription }})</span>
                            <p class="text-sm text-slate-500 mt-1">{{ $exercise->instructions }}</p>
                        </div>
                    </label>
                @empty
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800">
                        This protocol has no exercises to log.
                    </div>
                @endforelse
            </div>

            <div class="h-px bg-slate-200"></div>

            <!-- Assessment Section -->
            <div class="space-y-6">
                <h2 class="text-xl font-bold text-slate-900">2. Self-Assessment</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="pain_score" class="block text-sm font-semibold text-slate-900 mb-3">Pain Score (0-10) <span class="text-red-500">*</span></label>
                        <select id="pain_score" name="pain_score" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white cursor-pointer font-medium @error('pain_score') border-red-500 @enderror">
                            <option value="">Select a pain level</option>
                            @for ($i = 0; $i <= 10; $i++)
                                <option value="{{ $i }}" @selected(old('pain_score') == $i)>
                                    {{ $i }} @if ($i == 0) - No Pain @elseif ($i == 5) - Moderate @elseif ($i == 10) - Worst Possible @endif
                                </option>
                            @endfor
                        </select>
                        @error('pain_score') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="difficulty_score" class="block text-sm font-semibold text-slate-900 mb-3">Exercise Difficulty <span class="text-red-500">*</span></label>
                        <select id="difficulty_score" name="difficulty_score" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white cursor-pointer font-medium @error('difficulty_score') border-red-500 @enderror">
                            <option value="">Select difficulty</option>
                            <option value="1" @selected(old('difficulty_score') == 1)>1 - Very Easy</option>
                            <option value="2" @selected(old('difficulty_score') == 2)>2 - Easy</option>
                            <option value="3" @selected(old('difficulty_score') == 3)>3 - Moderate</option>
                            <option value="4" @selected(old('difficulty_score') == 4)>4 - Hard</option>
                            <option value="5" @selected(old('difficulty_score') == 5)>5 - Extreme Effort</option>
                        </select>
                        @error('difficulty_score') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-slate-900 mb-3">Session Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none" placeholder="How did you feel today? Any improvements or concerns?">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4 pt-6 border-t border-slate-200">
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                    Complete Session
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 border-2 border-slate-300 text-slate-900 font-semibold rounded-lg hover:border-slate-400 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection