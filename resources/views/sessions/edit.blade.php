@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Edit Session Log</h1>
        <p class="text-slate-600 dark:text-slate-400">Update your session details and assessment</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="px-8 py-6 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/30 dark:to-blue-900/30 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Session Details</h2>
        </div>

        <form action="{{ route('sessions.update', $session) }}" method="POST" class="p-8 space-y-8" x-data="sessionForm()">
            @csrf
            @method('PUT')
            
            <!-- Protocol Selection -->
            <div>
                <label for="protocol_id" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Protocol</label>
                <select 
                    id="protocol_id" 
                    name="protocol_id" 
                    required
                    x-model="selectedProtocol"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white dark:bg-slate-700 dark:text-white cursor-pointer font-medium @error('protocol_id') border-red-500 @enderror"
                >
                    @foreach($protocols as $protocol)
                        <option value="{{ $protocol->id }}">
                            {{ $protocol->title }}
                        </option>
                    @endforeach
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
                        <div x-show="selectedProtocol == '{{ $protocol->id }}'" class="space-y-2">
                            @foreach($protocol->exercises as $exercise)
                                <div class="p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600 transition-all duration-200" x-data="{ expanded: false }">
                                    <div class="flex justify-between items-start cursor-pointer group" @click="expanded = !expanded">
                                        <div>
                                            <div class="font-medium text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $exercise->name }}</div>
                                            <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                                {{ $exercise->pivot->sets }} sets × {{ $exercise->pivot->reps }} reps
                                            </div>
                                        </div>
                                        <button type="button" class="text-slate-400 group-hover:text-emerald-500 transition-colors">
                                            <span x-show="!expanded" class="text-xs font-semibold">Show Details</span>
                                            <span x-show="expanded" class="text-xs font-semibold">Hide Details</span>
                                        </button>
                                    </div>

                                    <!-- Expandable Details -->
                                    <div x-show="expanded" class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-600 space-y-3">
                                        <div class="text-sm text-slate-600 dark:text-slate-300">
                                            <p class="font-semibold mb-1 text-slate-800 dark:text-slate-200">Instructions:</p>
                                            <p>{{ $exercise->instructions }}</p>
                                        </div>
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
                <label for="log_date" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Session Date</label>
                <input 
                    type="date" 
                    id="log_date" 
                    name="log_date" 
                    required
                    value="{{ old('log_date', $session->log_date->format('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition dark:bg-slate-700 dark:text-white dark:[color-scheme:dark]"
                >
            </div>

            <!-- Assessment Section -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Pain Score -->
                <div>
                    <label for="pain_score" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Pain Score (0-10)</label>
                    <select id="pain_score" name="pain_score" required class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 dark:text-white">
                        @for ($i = 0; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('pain_score', $session->pain_score) == $i ? 'selected' : '' }}>
                                {{ $i }} {{ $i === 0 ? '- No Pain' : ($i === 10 ? '- Worst' : '') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Difficulty Rating -->
                <div>
                    <label for="difficulty_rating" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Exercise Difficulty</label>
                    <select id="difficulty_rating" name="difficulty_rating" required class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 dark:text-white">
                        <option value="1" {{ old('difficulty_rating', $session->difficulty_rating) == 1 ? 'selected' : '' }}>1 - Very Easy</option>
                        <option value="2" {{ old('difficulty_rating', $session->difficulty_rating) == 2 ? 'selected' : '' }}>2 - Easy</option>
                        <option value="3" {{ old('difficulty_rating', $session->difficulty_rating) == 3 ? 'selected' : '' }}>3 - Moderate</option>
                        <option value="4" {{ old('difficulty_rating', $session->difficulty_rating) == 4 ? 'selected' : '' }}>4 - Hard</option>
                        <option value="5" {{ old('difficulty_rating', $session->difficulty_rating) == 5 ? 'selected' : '' }}>5 - Very Hard</option>
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">Session Notes</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="4" 
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white dark:bg-slate-700 dark:text-white outline-none transition resize-none"
                    placeholder="Describe how you felt..."
                >{{ old('notes', $session->notes) }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                    Update Session
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 border-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-semibold rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function sessionForm() {
    return {
        selectedProtocol: '{{ old('protocol_id', $session->protocol_id) }}',
    }
}
</script>
@endsection