<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use App\Models\Exercise; 
use App\Models\User;
use App\Traits\ConvertsUnits; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProtocolController extends Controller
{
    use ConvertsUnits;

    // Helper to check if user is therapist
    protected function checkTherapist() {
        if (Auth::user()->role !== 'therapist') {
            abort(403, 'Only therapists can perform this action.');
        }
    }

    public function index()
    {
        $this->checkTherapist();
        $protocols = Auth::user()->createdProtocols()->with('exercises')->paginate(10);
        return view('protocols.index', compact('protocols'));
    }

    public function create()
    {
        $this->checkTherapist();
        $exercises = Exercise::all();
        return view('protocols.create', compact('exercises'));
    }

    public function store(Request $request)
    {
        $this->checkTherapist();
        // ... (rest of store logic stays same)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exercises' => 'required|array|min:1',
            'exercises.*.exercise_id' => ['required', 'exists:exercises,id'],
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.resistance_value' => 'nullable|numeric|min:0',
            'exercises.*.resistance_unit' => [
                'required_with:exercises.*.resistance_value', 
                Rule::in(['g', 'kg', 'lb', 'm', 'ft', 'cm'])
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $protocol = Protocol::create([
                'therapist_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);
            $pivotData = $this->preparePivotData($validated['exercises']);
            $protocol->exercises()->sync($pivotData);
        });

        return redirect()->route('protocols.index')->with('success', 'Protocol created.');
    }

    public function show(Protocol $protocol)
    {
        $protocol->load(['exercises', 'therapist', 'patients']); 
        return view('protocols.show', compact('protocol'));
    }

    public function edit(Protocol $protocol)
    {
        $this->checkTherapist();
        $protocol->load('exercises');
        $exercises = Exercise::all();
        return view('protocols.edit', compact('protocol', 'exercises'));
    }

    public function update(Request $request, Protocol $protocol)
    {
        $this->checkTherapist();
        // ... (validation and update logic)
        $protocol->update($request->only('title', 'description'));
        $pivotData = $this->preparePivotData($request->exercises ?? []);
        $protocol->exercises()->sync($pivotData);
        
        return redirect()->route('protocols.index')->with('success', 'Protocol updated.');
    }

    public function assign(Protocol $protocol)
    {
        $this->checkTherapist();
        $allPatients = User::patient()->orderBy('name')->get();
        $assignedPatientIds = $protocol->patients->pluck('id')->toArray();
        return view('protocols.assign', compact('protocol', 'allPatients', 'assignedPatientIds'));
    }

    public function processAssignment(Request $request, Protocol $protocol)
    {
        $this->checkTherapist();

        $validated = $request->validate([
            'patients' => 'nullable|array',
            'patients.*' => 'exists:users,id',
            'duration_days' => 'required|integer|min:1|max:365',
        ]);

        $patientIds = $validated['patients'] ?? [];
        $durationDays = $validated['duration_days'];

        $pivotData = [];
        foreach ($patientIds as $id) {
            $pivotData[$id] = ['duration_days' => $durationDays];
        }

        $protocol->patients()->sync($pivotData);

        return redirect()->route('protocols.show', $protocol)->with('success', 'Protocol assigned successfully.');
    }

    public function destroy(Protocol $protocol)
    {
        $this->checkTherapist();
        $protocol->delete();
        return redirect()->route('protocols.index')->with('success', 'Protocol deleted.');
    }

    protected function preparePivotData(array $exerciseDataArray): array
    {
        $pivotData = [];
        foreach ($exerciseDataArray as $exerciseData) {
            $pivotData[$exerciseData['exercise_id']] = [
                'sets' => $exerciseData['sets'],
                'reps' => $exerciseData['reps'],
                'resistance_amount' => $exerciseData['resistance_value'] ?? 0, 
                'resistance_original_unit' => $exerciseData['resistance_unit'] ?? 'g',
                'rest_seconds' => 60, 
            ];
        }
        return $pivotData;
    }
}