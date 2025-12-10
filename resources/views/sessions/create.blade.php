@extends('layouts.app')

@section('content')
<!-- Redesigned session logging form with better visual sections -->
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Log Today's Session</h1>
        <p class="text-slate-600">Protocol: <span class="font-semibold text-emerald-600">ACL Rehab - Phase 2</span></p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900">Exercise Checklist</h2>
        </div>

        <form action="#" method="POST" class="p-8 space-y-8">
            @csrf
            
            <!-- Exercise Checklist Section -->
            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition cursor-pointer">
                    <input id="ex1" name="exercises[]" type="checkbox" class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 cursor-pointer mt-1">
                    <div class="flex-1">
                        <label for="ex1" class="font-semibold text-slate-900 block">Quad Sets (3 sets x 10 reps)</label>
                        <p class="text-sm text-slate-500 mt-1">Hold for 5 seconds each rep. Focus on controlled movement.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition cursor-pointer">
                    <input id="ex2" name="exercises[]" type="checkbox" class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 cursor-pointer mt-1">
                    <div class="flex-1">
                        <label for="ex2" class="font-semibold text-slate-900 block">Heel Slides (3 sets x 15 reps)</label>
                        <p class="text-sm text-slate-500 mt-1">Use a towel if needed. Move through full range of motion.</p>
                    </div>
                </div>
            </div>

            <div class="h-px bg-slate-200"></div>

            <!-- Assessment Section -->
            <div class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="pain_score" class="block text-sm font-semibold text-slate-900 mb-3">Pain Score (0-10)</label>
                        <select id="pain_score" name="pain_score" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white cursor-pointer font-medium">
                            <option value="">Select a pain level</option>
                            <option value="0">0 - No Pain</option>
                            <option value="1">1 - Minimal</option>
                            <option value="2">2 - Very Light</option>
                            <option value="3">3 - Light</option>
                            <option value="4">4</option>
                            <option value="5">5 - Moderate</option>
                            <option value="6">6</option>
                            <option value="7">7 - Significant</option>
                            <option value="8">8 - Heavy</option>
                            <option value="9">9 - Severe</option>
                            <option value="10">10 - Worst Pain Possible</option>
                        </select>
                    </div>

                    <div>
                        <label for="difficulty" class="block text-sm font-semibold text-slate-900 mb-3">Exercise Difficulty</label>
                        <select id="difficulty" name="difficulty" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white cursor-pointer font-medium">
                            <option value="">Select difficulty</option>
                            <option value="1">1 - Very Easy</option>
                            <option value="2">2 - Easy</option>
                            <option value="3">3 - Moderate</option>
                            <option value="4">4 - Hard</option>
                            <option value="5">5 - Extreme Effort</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-slate-900 mb-3">Session Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none" placeholder="How did you feel today? Any improvements or concerns?"></textarea>
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
