@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-4xl font-bold text-slate-900">Create New Protocol</h1>
        <a href="{{ route('protocols.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 transition">← Back to Protocols</a>
    </div>

    <!-- Main Protocol Form -->
    <form action="{{ route('protocols.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-8">
        @csrf

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                <strong class="font-bold">Protocol Creation Failed!</strong>
                <span class="block sm:inline">Please check the fields below for errors.</span>
            </div>
        @endif

        <!-- Protocol Details Section -->
        <section class="space-y-6 border-b pb-6 border-slate-200">
            <h2 class="text-2xl font-semibold text-slate-800">1. Protocol Information</h2>
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Protocol Title</label>
                <input type="text" name="title" id="title" required value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition"
                       placeholder="e.g., ACL Rehab Phase 1: Range of Motion">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description (Optional)</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition"
                          placeholder="A brief overview of the goals for this phase.">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

        <!-- Exercises Section -->
        <section class="space-y-6">
            <h2 class="text-2xl font-semibold text-slate-800">2. Exercises & Prescription</h2>
            <div id="exercises-container" class="space-y-6">
                <!-- Exercise slots will be added here by JavaScript -->
                @error('exercises') <p class="text-red-500 text-xs mt-1">Protocols must contain at least one exercise.</p> @enderror
            </div>

            <button type="button" id="add-exercise-btn" class="flex items-center gap-2 px-4 py-2 border border-dashed border-emerald-500 text-emerald-600 rounded-lg hover:bg-emerald-50 transition w-full justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Exercise
            </button>
        </section>

        <!-- Submit Button -->
        <div class="pt-6 border-t border-slate-200">
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-md">
                Save Protocol and Prescribe
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('exercises-container');
        const addButton = document.getElementById('add-exercise-btn');
        let exerciseIndex = 0;

        // Exercise list passed from the controller
        const exercises = @json($exercises);

        function createExerciseSlot(index) {
            const slot = document.createElement('div');
            slot.className = 'bg-slate-50 p-6 rounded-xl border border-slate-300 space-y-4 relative';
            slot.dataset.index = index;

            // Template needs to be safe from PHP Blade compilation error. 
            // We use placeholders for error messages (which are handled by the re-render on validation failure).
            const exerciseSlotHtml = `
                <h3 class="text-lg font-bold text-slate-800">Exercise #${index + 1}</h3>
                
                <button type="button" class="remove-exercise-btn absolute top-3 right-3 text-red-500 hover:text-red-700 transition" title="Remove Exercise">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <!-- Exercise Selector -->
                <div>
                    <label for="exercises[${index}][exercise_id]" class="block text-sm font-medium text-slate-700 mb-2">Select Exercise</label>
                    <select name="exercises[${index}][exercise_id]" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition bg-white">
                        <option value="">-- Choose an Exercise --</option>
                        ${exercises.map(ex => `<option value="${ex.id}">${ex.name}</option>`).join('')}
                    </select>
                    <!-- Error placeholder removed to prevent PHP constant error -->
                </div>
                
                <!-- Sets, Reps, Resistance Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <!-- Sets -->
                    <div>
                        <label for="exercises[${index}][sets]" class="block text-sm font-medium text-slate-700 mb-2">Sets</label>
                        <input type="number" name="exercises[${index}][sets]" required min="1" value="3"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition"
                               placeholder="e.g., 3">
                        <!-- Error placeholder removed to prevent PHP constant error -->
                    </div>
                    
                    <!-- Reps -->
                    <div>
                        <label for="exercises[${index}][reps]" class="block text-sm font-medium text-slate-700 mb-2">Reps</label>
                        <input type="number" name="exercises[${index}][reps]" required min="1" value="10"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition"
                               placeholder="e.g., 10">
                        <!-- Error placeholder removed to prevent PHP constant error -->
                    </div>
                    
                    <!-- Resistance Value -->
                    <div class="col-span-1 sm:col-span-2 grid grid-cols-3 gap-2">
                        <div class="col-span-2">
                            <label for="exercises[${index}][resistance_value]" class="block text-sm font-medium text-slate-700 mb-2">Resistance Value (Optional)</label>
                            <input type="number" name="exercises[${index}][resistance_value]" min="0" step="0.1"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition"
                                   placeholder="e.g., 5">
                            <!-- Error placeholder removed to prevent PHP constant error -->
                        </div>
                        
                        <!-- Resistance Unit -->
                        <div class="col-span-1">
                            <label for="exercises[${index}][resistance_unit]" class="block text-sm font-medium text-slate-700 mb-2">Unit</label>
                            <select name="exercises[${index}][resistance_unit]"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition bg-white">
                                <option value="g">g (Grams)</option>
                                <option value="kg">kg (Kilograms)</option>
                                <option value="lb">lb (Pounds)</option>
                                <option value="m">m (Meters)</option>
                                <option value="ft">ft (Feet)</option>
                                <option value="cm">cm (Centimeters)</option>
                            </select>
                            <!-- Error placeholder removed to prevent PHP constant error -->
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
                    // Regex replaces the old index [X] with the new index [Y]
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

        // Initialize with one slot if the container is empty
        if (container.children.length === 0) {
            createExerciseSlot(exerciseIndex++);
        }
    });
</script>
@endsection