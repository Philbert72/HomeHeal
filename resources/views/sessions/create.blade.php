@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Log Today's Session</h1>
        <p class="text-slate-600 dark:text-slate-400">Track your progress by recording your therapy session</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-8 py-6 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/30 dark:to-teal-900/30 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Session Details</h2>
        </div>

        <form action="{{ route('sessions.store') }}" method="POST" class="p-8 space-y-8" x-data="sessionForm()">
            @csrf
            
            <!-- Protocol Selection -->
            <div>
                <label for="protocol_id" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Select Protocol <span class="text-red-500">*</span></label>
                <select 
                    id="protocol_id" 
                    name="protocol_id" 
                    required
                    x-model="selectedProtocol"
                    @change="updateExercises()"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white dark:bg-slate-700 dark:text-white cursor-pointer font-medium @error('protocol_id') border-red-500 @enderror"
                >
                    <option value="">Choose a protocol</option>
                    @forelse($protocols as $protocol)
                        <option value="{{ $protocol->id }}" {{ (old('protocol_id') == $protocol->id || (isset($selectedProtocolId) && $selectedProtocolId == $protocol->id)) ? 'selected' : '' }}>
                            {{ $protocol->title }}
                        </option>
                    @empty
                        <option disabled>No available protocols (or all done today)</option>
                    @endforelse
                </select>
                @error('protocol_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Protocol Exercises Display -->
            <div x-show="selectedProtocol" x-transition class="space-y-3">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Protocol Exercises:</h3>
                <div class="space-y-2">
                    @foreach($protocols as $protocol)
                        <div x-show="selectedProtocol == {{ $protocol->id }}" class="space-y-2">
                            @foreach($protocol->exercises as $exercise)
                                <div class="p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600 transition-all duration-200" x-data="{ expanded: false }">
                                    <div class="flex justify-between items-start cursor-pointer group" @click="expanded = !expanded">
                                        <div>
                                            <div class="font-medium text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $exercise->name }}</div>
                                            <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                                {{ $exercise->pivot->sets }} sets × {{ $exercise->pivot->reps }} reps
                                                @if($exercise->pivot->rest_seconds)
                                                    • {{ $exercise->pivot->rest_seconds }}s rest
                                                @endif
                                            </div>
                                        </div>
                                        <button type="button" class="text-slate-400 group-hover:text-emerald-500 transition-colors">
                                            <span x-show="!expanded" class="text-xs font-semibold">Show Details</span>
                                            <span x-show="expanded" class="text-xs font-semibold">Hide Details</span>
                                        </button>
                                    </div>

                                    <!-- Expandable Content -->
                                    <div x-show="expanded" x-collapse class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-600 space-y-3">
                                        @if($exercise->image_path)
                                            <img src="{{ asset('storage/' . $exercise->image_path) }}" alt="{{ $exercise->name }}" class="w-full h-48 object-cover rounded-md">
                                        @endif
                                        
                                        <div class="grid md:grid-cols-2 gap-4">
                                            @if($exercise->equipment_needed)
                                                <div class="text-sm">
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300">🛠 Equipment:</span>
                                                    <span class="text-slate-600 dark:text-slate-400">{{ $exercise->equipment_needed }}</span>
                                                </div>
                                            @endif

                                            @if($exercise->video_url)
                                                <div class="flex items-center">
                                                    <a href="{{ $exercise->video_url }}" target="_blank" class="flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Watch Video Guide
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-sm text-slate-600 dark:text-slate-300">
                                            <p class="font-semibold mb-1 text-slate-800 dark:text-slate-200">Overview:</p>
                                            <p>{{ $exercise->instructions }}</p>
                                        </div>

                                        @if($exercise->step_by_step_guide)
                                            <div class="text-sm text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 p-3 rounded-lg border border-slate-200 dark:border-slate-700">
                                                <p class="font-semibold mb-2 text-slate-800 dark:text-slate-200">Step-by-Step Guide:</p>
                                                <div class="whitespace-pre-line">{{ $exercise->step_by_step_guide }}</div>
                                            </div>
                                        @endif

                                        @if($exercise->safety_warnings)
                                            <div class="text-sm text-amber-800 dark:text-amber-200 bg-amber-50 dark:bg-amber-900/30 p-3 rounded-lg border border-amber-200 dark:border-amber-800">
                                                <span class="font-bold">⚠️ Safety Warning:</span> {{ $exercise->safety_warnings }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="h-px bg-slate-200 dark:bg-slate-700"></div>

            <!-- Session Date -->
            <div>
                <label for="log_date" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Session Date <span class="text-red-500">*</span></label>
                <input 
                    type="date" 
                    id="log_date" 
                    name="log_date" 
                    required
                    value="{{ old('log_date', date('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition dark:bg-slate-700 dark:text-white dark:[color-scheme:dark] @error('log_date') border-red-500 @enderror"
                >
                @error('log_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assessment Section -->
            <div class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Pain Score -->
                    <div>
                        <label for="pain_score" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Pain Score (0-10) <span class="text-red-500">*</span></label>
                        <select 
                            id="pain_score" 
                            name="pain_score" 
                            required
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white dark:bg-slate-700 dark:text-white cursor-pointer font-medium @error('pain_score') border-red-500 @enderror"
                        >
                            <option value="">Select pain level</option>
                            <option value="0" {{ old('pain_score') == '0' ? 'selected' : '' }}>0 - No Pain</option>
                            <option value="1" {{ old('pain_score') == '1' ? 'selected' : '' }}>1 - Minimal</option>
                            <option value="2" {{ old('pain_score') == '2' ? 'selected' : '' }}>2 - Very Light</option>
                            <option value="3" {{ old('pain_score') == '3' ? 'selected' : '' }}>3 - Light</option>
                            <option value="4" {{ old('pain_score') == '4' ? 'selected' : '' }}>4</option>
                            <option value="5" {{ old('pain_score') == '5' ? 'selected' : '' }}>5 - Moderate</option>
                            <option value="6" {{ old('pain_score') == '6' ? 'selected' : '' }}>6</option>
                            <option value="7" {{ old('pain_score') == '7' ? 'selected' : '' }}>7 - Significant</option>
                            <option value="8" {{ old('pain_score') == '8' ? 'selected' : '' }}>8 - Heavy</option>
                            <option value="9" {{ old('pain_score') == '9' ? 'selected' : '' }}>9 - Severe</option>
                            <option value="10" {{ old('pain_score') == '10' ? 'selected' : '' }}>10 - Worst Possible</option>
                        </select>
                        @error('pain_score')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Difficulty Rating -->
                    <div>
                        <label for="difficulty_rating" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Exercise Difficulty <span class="text-red-500">*</span></label>
                        <select 
                            id="difficulty_rating" 
                            name="difficulty_rating" 
                            required
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white dark:bg-slate-700 dark:text-white cursor-pointer font-medium @error('difficulty_rating') border-red-500 @enderror"
                        >
                            <option value="">Select difficulty</option>
                            <option value="1" {{ old('difficulty_rating') == '1' ? 'selected' : '' }}>1 - Very Easy</option>
                            <option value="2" {{ old('difficulty_rating') == '2' ? 'selected' : '' }}>2 - Easy</option>
                            <option value="3" {{ old('difficulty_rating') == '3' ? 'selected' : '' }}>3 - Moderate</option>
                            <option value="4" {{ old('difficulty_rating') == '4' ? 'selected' : '' }}>4 - Hard</option>
                            <option value="5" {{ old('difficulty_rating') == '5' ? 'selected' : '' }}>5 - Very Hard</option>
                        </select>
                        @error('difficulty_rating')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Session Notes (Optional)</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4" 
                        maxlength="1000"
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none dark:bg-slate-700 dark:text-white @error('notes') border-red-500 @enderror" 
                        placeholder="How did you feel today? Any improvements or concerns?"
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                    Complete Session
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 border-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-semibold rounded-lg hover:border-slate-400 dark:hover:border-slate-500 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function sessionForm() {
    return {
        selectedProtocol: '{{ old('protocol_id') }}',
        updateExercises() {
        }
    }
}
</script>
@endsection
