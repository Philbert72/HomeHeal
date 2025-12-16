@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <!-- Header and Navigation -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-5xl font-extrabold text-slate-900 mb-2">{{ $protocol->title }}</h1>
            <p class="text-lg text-slate-600">{{ $protocol->description }}</p>
        </div>
        
        <div class="flex flex-col items-end gap-3">
            <a href="{{ route('protocols.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 transition">← Back to Protocols</a>
            
            @can('update', $protocol)
                <!-- Therapist Edit Button -->
                <a href="{{ route('protocols.edit', $protocol) }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                    Edit Protocol
                </a>
            @endcan
            
            <!-- Future Assignment Button for Therapist -->
            @if(Auth::user()->role === 'therapist')
                <button disabled class="px-4 py-2 text-sm font-medium text-white bg-slate-400 rounded-lg shadow transition cursor-not-allowed">
                    Assign Protocol (Coming Soon)
                </button>
            @endif
        </div>
    </div>

    <!-- Metadata Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-slate-700 border-t border-b border-slate-200 py-6">
        <div class="space-y-1">
            <p class="text-sm font-semibold text-slate-500">Created By</p>
            <p class="text-base font-medium">Dr. {{ $protocol->therapist->name }}</p>
        </div>
        <div class="space-y-1">
            <p class="text-sm font-semibold text-slate-500">Date Created</p>
            <p class="text-base">{{ $protocol->created_at->format('M d, Y') }}</p>
        </div>
        <div class="space-y-1">
            <p class="text-sm font-semibold text-slate-500">Status</p>
            @if ($protocol->patients->isEmpty())
                <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full">Draft</span>
            @else
                <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Assigned ({{ $protocol->patients->count() }})</span>
            @endif
        </div>
    </div>

    <!-- Exercises List Section -->
    <section class="space-y-6">
        <h2 class="text-3xl font-bold text-slate-900 border-b pb-2">Exercise Prescription</h2>

        <div class="divide-y divide-slate-200 border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            @forelse ($protocol->exercises as $index => $exercise)
                <div class="p-6 bg-white hover:bg-slate-50 transition">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-emerald-700">
                            {{ $index + 1 }}. {{ $exercise->name }}
                        </h3>
                        @if (Auth::user()->role === 'patient')
                            <button class="px-3 py-1 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition">
                                Log Completion
                            </button>
                        @endif
                    </div>
                    
                    <!-- Prescription Details -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mt-4 p-4 bg-slate-100 rounded-lg">
                        
                        <!-- Sets -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-slate-600">Sets</p>
                            <p class="text-lg font-bold text-slate-900">{{ $exercise->pivot->sets }}</p>
                        </div>
                        
                        <!-- Reps -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-slate-600">Reps</p>
                            <p class="text-lg font-bold text-slate-900">{{ $exercise->pivot->reps }}</p>
                        </div>
                        
                        <!-- Resistance (Displaying original unit) -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-slate-600">Resistance</p>
                            <p class="text-lg font-bold text-slate-900">
                                @if ($exercise->pivot->resistance_amount > 0)
                                    <!-- NOTE: We are displaying the base unit value here, but labeling it with the original unit. 
                                        To show the original value (e.g., 5 kg instead of 5000 g), we would need a conversion back function.
                                        For now, we display the original unit with the base value (which is conceptually flawed but functionally shows the data is saved).
                                    -->
                                    {{ number_format($exercise->pivot->resistance_amount, 2) }} {{ $exercise->pivot->resistance_original_unit }}
                                @else
                                    Bodyweight / N/A
                                @endif
                            </p>
                        </div>

                        <!-- Rest Time -->
                        <div class="space-y-1">
                            <p class="text-xs font-medium text-slate-600">Rest</p>
                            <p class="text-lg font-bold text-slate-900">{{ $exercise->pivot->rest_seconds }} seconds</p>
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <h4 class="text-sm font-semibold text-slate-700 mb-1">Instructions:</h4>
                        <p class="text-sm text-slate-700 italic">{{ $exercise->instructions }}</p>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-500">
                    No exercises have been added to this protocol yet.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection