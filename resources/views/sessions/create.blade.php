@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
                Log Today's Session
            </h2>
            <p class="mt-1 text-sm text-gray-500">Protocol: ACL Rehab - Phase 2</p>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="#" method="POST">
                @csrf
                
                <div class="mb-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Exercise Checklist</h3>
                    <div class="space-y-4">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="ex1" name="exercises[]" type="checkbox" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="ex1" class="font-medium text-gray-700">Quad Sets (3 sets x 10 reps)</label>
                                <p class="text-gray-500">Hold for 5 seconds each rep.</p>
                            </div>
                        </div>

                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="ex2" name="exercises[]" type="checkbox" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="ex2" class="font-medium text-gray-700">Heel Slides (3 sets x 15 reps)</label>
                                <p class="text-gray-500">Use a towel if needed.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    <div class="sm:col-span-3">
                        <label for="pain_score" class="block text-sm font-medium text-gray-700">Pain Score (0-10)</label>
                        <div class="mt-1">
                            <select id="pain_score" name="pain_score" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="0">0 - No Pain</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5 - Moderate</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10 - Worst Pain Possible</option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty (1-5)</label>
                        <div class="mt-1">
                            <select id="difficulty" name="difficulty" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="1">1 - Very Easy</option>
                                <option value="2">2 - Easy</option>
                                <option value="3">3 - Moderate</option>
                                <option value="4">4 - Hard</option>
                                <option value="5">5 - Extreme Effort</option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Session Notes</label>
                        <div class="mt-1">
                            <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="How did you feel today?"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                        Complete Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection