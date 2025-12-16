@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-xl mx-auto">
    <!-- Header -->
    <div class="flex items-start justify-between">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-2">Assign Protocol</h1>
        <a href="{{ route('protocols.show', $protocol) }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 transition">← Back to Protocol</a>
    </div>

    <!-- Protocol Summary -->
    <div class="bg-white p-6 rounded-xl shadow border border-slate-200">
        <h2 class="text-xl font-bold text-indigo-700">{{ $protocol->title }}</h2>
        <p class="text-slate-600 text-sm mt-1">{{ $protocol->description }}</p>
    </div>

    <!-- Assignment Form -->
    <form action="{{ route('protocols.processAssignment', $protocol) }}" method="POST" class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8 space-y-6">
        @csrf
        
        <h3 class="text-2xl font-semibold text-slate-800 border-b pb-2">Select Patients to Assign</h3>
        
        @if ($allPatients->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded" role="alert">
                <p class="font-bold">No Patients Found</p>
                <p>Please create patient user accounts before assigning protocols.</p>
            </div>
        @else
            <!-- Patients List (Checkboxes) -->
            <div class="space-y-3 max-h-80 overflow-y-auto p-3 border rounded-lg bg-slate-50">
                @foreach ($allPatients as $patient)
                    <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-slate-100 transition">
                        <input type="checkbox" name="patients[]" value="{{ $patient->id }}" 
                                @checked(in_array($patient->id, $assignedPatientIds))
                                class="form-checkbox h-5 w-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                        
                        <div class="flex flex-col">
                            <span class="text-base font-medium text-slate-900">{{ $patient->name }}</span>
                            <span class="text-xs text-slate-500">{{ $patient->email }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            @error('patients') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        @endif

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-200">
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-md">
                Update Assignment
            </button>
        </div>
    </form>
</div>
@endsection