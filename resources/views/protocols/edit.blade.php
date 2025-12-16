@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-4xl font-extrabold text-slate-900">Edit Protocol: {{ $protocol->title }}</h1>
        <a href="{{ route('protocols.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 transition">← Back to Protocols</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative text-sm" role="alert">
            <strong class="font-bold">Protocol Update Failed!</strong>
            <span class="block sm:inline">Please check the fields below for errors.</span>
        </div>
    @endif

    <!-- Main Protocol Form (UPDATE) -->
    <form action="{{ route('protocols.update', $protocol) }}" method="POST" class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8 space-y-10">
        @csrf
        @method('PUT')

        <!-- Protocol Details Section -->
        <section class="space-y-6 border-b pb-8 border-slate-200">
            <h2 class="text-2xl font-bold text-indigo-600 border-b-2 border-indigo-100 inline-block pb-1">1. General Information</h2>
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Protocol Title</label>
                <input type="text" name="title" id="title" required 
                       value="{{ old('title', $protocol->title) }}"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                       placeholder="e.g., ACL Rehab Phase 1: Range of Motion">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description (Optional)</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                          placeholder="A brief overview of the goals for this phase.">{{ old('description', $protocol->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

        <!-- Exercises Section -->
        <section class="space-y-6">
            <h2 class="text-2xl font-bold text-indigo-600 border-b-2 border-indigo-100 inline-block pb-1">2. Exercise Prescription</h2>
            <div id="exercises-container" class="space-y-6">
                @error('exercises') <p class="text-red-500 text-xs mt-1">Protocols must contain at least one exercise.</p> @enderror
            </div>

            <button type="button" id="add-exercise-btn" class="flex items-center gap-2 px-6 py-3 border-2 border-dashed border-emerald-500 text-emerald-600 font-semibold rounded-lg hover:bg-emerald-50 transition w-full justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Another Exercise
            </button>
        </section>

        <!-- Form Actions (UPDATE Button) -->
        <div class="pt-8 border-t border-slate-200 flex justify-end items-center gap-4">
            
            <!-- Delete Button (Hover classes explicitly set to ensure strong visual feedback) -->
            <button type="button" 
                    onclick="if(confirm('WARNING: Are you sure you want to delete this protocol? This action cannot be undone and will remove it for all patients.')) document.getElementById('delete-form').submit();"
                    class="px-6 py-3 text-sm font-semibold text-red-600 bg-red-100 hover:bg-red-700 hover:text-white rounded-lg transition shadow-md">
                Delete Protocol
            </button>

            <!-- Update Button -->
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-lg">
                Save & Update Protocol
            </button>
        </div>
    </form>
    
    <!-- DELETE FORM (Hidden, triggered by the button above) -->
    <form id="delete-form" action="{{ route('protocols.destroy', $protocol) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('exercises-container');
        const addButton = document.getElementById('add-exercise-btn');
        let exerciseIndex = 0;

        const exercises = @json($exercises); // All available exercises
        const existingExercises = @json($protocol->exercises); // Exercises currently on this protocol

        function createExerciseSlot(index, data = {}) {
            const slot = document.createElement('div');
            // Added shadow and better border style for visual separation
            slot.className = 'bg-white p-6 rounded-xl border border-slate-200 shadow-md space-y-4 relative'; 
            slot.dataset.index = index;

            // Determine default values or use existing data
            const currentExerciseId = data.id || '';
            const currentSets = data.pivot?.sets || 3;
            const currentReps = data.pivot?.reps || 10;
            const currentResistanceValue = data.pivot?.resistance_amount || '';
            const currentResistanceUnit = data.pivot?.resistance_original_unit || 'g'; 
            
            // Helper function to generate options HTML
            const optionsHtml = exercises.map(ex => 
                `<option value="${ex.id}" ${currentExerciseId === ex.id ? 'selected' : ''}>${ex.name}</option>`
            ).join('');

            // Helper function for unit selection
            function unitOption(unit, label) {
                return `<option value="${unit}" ${currentResistanceUnit === unit ? 'selected' : ''}>${label}</option>`;
            }


            const exerciseSlotHtml = `
                <!-- Title and Remove Button in a clean flex container -->
                <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2">
                    <h3 class="text-xl font-bold text-slate-800">Exercise #${index + 1}</h3>
                    
                    <button type="button" class="remove-exercise-btn text-slate-400 hover:text-red-500 transition" title="Remove Exercise">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Exercise Selector -->
                <div>
                    <label for="exercises[${index}][exercise_id]" class="block text-sm font-medium text-slate-700 mb-2">Select Exercise</label>
                    <select name="exercises[${index}][exercise_id]" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white">
                        <option value="">-- Choose an Exercise --</option>
                        ${optionsHtml}
                    </select>
                </div>
                
                <!-- Sets, Reps, Resistance Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-4">
                    <!-- Sets -->
                    <div>
                        <label for="exercises[${index}][sets]" class="block text-sm font-medium text-slate-700 mb-2">Sets</label>
                        <input type="number" name="exercises[${index}][sets]" required min="1" 
                               value="${currentSets}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                               placeholder="e.g., 3">
                    </div>
                    
                    <!-- Reps -->
                    <div>
                        <label for="exercises[${index}][reps]" class="block text-sm font-medium text-slate-700 mb-2">Reps</label>
                        <input type="number" name="exercises[${index}][reps]" required min="1" 
                               value="${currentReps}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                               placeholder="e.g., 10">
                    </div>
                    
                    <!-- Resistance Value -->
                    <div class="col-span-1 sm:col-span-2 grid grid-cols-3 gap-2">
                        <div class="col-span-2">
                            <label for="exercises[${index}][resistance_value]" class="block text-sm font-medium text-slate-700 mb-2">Resistance Value (Optional)</label>
                            <input type="number" name="exercises[${index}][resistance_value]" min="0" step="0.1"
                                   value="${currentResistanceValue}"
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                   placeholder="e.g., 5">
                        </div>
                        
                        <!-- Resistance Unit -->
                        <div class="col-span-1">
                            <label for="exercises[${index}][resistance_unit]" class="block text-sm font-medium text-slate-700 mb-2">Unit</label>
                            <select name="exercises[${index}][resistance_unit]"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white">
                                ${unitOption('g', 'g')}
                                ${unitOption('kg', 'kg')}
                                ${unitOption('lb', 'lb')}
                                ${unitOption('m', 'm')}
                                ${unitOption('ft', 'ft')}
                                ${unitOption('cm', 'cm')}
                            </select>
                        </div>
                    </div>
                </div>
            `;
            
            slot.innerHTML = exerciseSlotHtml;
            container.appendChild(slot);
            return slot;
        }

        function reindexSlots() {
            const slots = container.querySelectorAll('[data-index]');
            slots.forEach((slot, newIndex) => {
                slot.querySelector('h3').textContent = `Exercise #${newIndex + 1}`;
                slot.dataset.index = newIndex;
                
                // Reindex all input/select names
                slot.querySelectorAll('[name^="exercises"]').forEach(input => {
                    const currentName = input.getAttribute('name');
                    const newName = currentName.replace(/\[\d+\]/, `[${newIndex}]`);
                    input.setAttribute('name', newName);
                });
            });
            exerciseIndex = slots.length;
        }

        // Event listener for adding a new slot
        addButton.addEventListener('click', function () {
            createExerciseSlot(exerciseIndex++);
        });

        // Event listener for removing a slot (delegated)
        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-exercise-btn')) {
                const slotToRemove = e.target.closest('[data-index]');
                slotToRemove.remove();
                reindexSlots();
            }
        });

        // Initialization: Load existing exercises or create one empty slot
        if (existingExercises.length > 0) {
            existingExercises.forEach(data => {
                createExerciseSlot(exerciseIndex++, data);
            });
        } else {
            createExerciseSlot(exerciseIndex++);
        }
    });
</script>
@endsection