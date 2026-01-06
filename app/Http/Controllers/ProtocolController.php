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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProtocolController extends Controller
{
    use ConvertsUnits, AuthorizesRequests;

    /**
     * Display a listing of the resource (Therapist View).
     */
    public function index()
    {
        $this->authorize('viewAny', Protocol::class);
        $protocols = Auth::user()->createdProtocols()->with('exercises')->paginate(10);
        return view('protocols.index', compact('protocols'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Protocol::class);
        $exercises = Exercise::all();
        return view('protocols.create', compact('exercises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Protocol::class);

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

        DB::transaction(function () use ($validated, $request) {
            
            $protocol = Protocol::create([
                'therapist_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            $pivotData = $this->preparePivotData($validated['exercises']);

            $protocol->exercises()->sync($pivotData);
        });

        return redirect()->route('protocols.index')->with('success', 'Protocol and associated exercises created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Protocol $protocol)
    {
        $this->authorize('view', $protocol);
        // Ensure patients are loaded for assignment display
        $protocol->load(['exercises', 'therapist', 'patients']); 
        return view('protocols.show', compact('protocol'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Protocol $protocol)
    {
        $this->authorize('update', $protocol);
        $protocol->load('exercises');
        $exercises = Exercise::all();
        return view('protocols.edit', compact('protocol', 'exercises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Protocol $protocol)
    {
        $this->authorize('update', $protocol);

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

        DB::transaction(function () use ($validated, $protocol) {
            
            $protocol->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            $pivotData = $this->preparePivotData($validated['exercises']);
            $protocol->exercises()->sync($pivotData);
        });

        return redirect()->route('protocols.index')->with('success', 'Protocol "' . $protocol->title . '" updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Protocol $protocol)
    {
        $this->authorize('delete', $protocol);

        $protocolTitle = $protocol->title;
        $protocol->delete();

        return redirect()->route('protocols.index')
                         ->with('success', 'Protocol "' . $protocolTitle . '" and all associated exercises were successfully deleted.');
    }

    /**
     * Show the form/modal for assigning the protocol to patients.
     */
    public function assign(Protocol $protocol)
    {
        $this->authorize('update', $protocol); 

        $allPatients = User::patient()->orderBy('name')->get();
        $assignedPatientIds = $protocol->patients->pluck('id')->toArray();

        return view('protocols.assign', compact('protocol', 'allPatients', 'assignedPatientIds'));
    }

    /**
     * Handle the assignment update (syncing patients).
     */
    public function processAssignment(Request $request, Protocol $protocol)
    {
        $this->authorize('update', $protocol);

        $validated = $request->validate([
            'patients' => 'nullable|array',
            'patients.*' => ['exists:users,id', Rule::in(User::patient()->pluck('id'))],
            'duration_days' => 'required|integer|min:1|max:365',
        ]);

        $patientIds = $validated['patients'] ?? [];
        $durationDays = $validated['duration_days'];

        $pivotData = [];
        foreach ($patientIds as $id) {
            $pivotData[$id] = ['duration_days' => $durationDays];
        }

        // Sync the patient list to the protocol_user pivot table with the extra data
        $protocol->patients()->sync($pivotData);

        $patientCount = count($patientIds);
        $message = $patientCount > 0 
                   ? "Protocol assigned successfully to $patientCount patient(s) for $durationDays days." 
                   : "All patients unassigned from the protocol.";

        return redirect()->route('protocols.show', $protocol)->with('success', $message);
    }
    
    /**
     * Update the assignment details for a specific patient.
     */
    public function updatePatientAssignment(Request $request, Protocol $protocol, User $patient)
    {
        $this->authorize('update', $protocol);

        $validated = $request->validate([
            'duration_days' => 'required|integer|min:1|max:365',
        ]);

        $protocol->patients()->updateExistingPivot($patient->id, [
            'duration_days' => $validated['duration_days'],
        ]);

        return redirect()->back()->with('success', 'Assignment updated successfully for ' . $patient->name);
    }
    
    /**
     * Custom method to handle unit conversion and pivot array creation.
     */
    protected function preparePivotData(array $exerciseDataArray): array
    {
        $pivotData = [];
        foreach ($exerciseDataArray as $exerciseData) {
            
            $resistanceValue = $exerciseData['resistance_value'] ?? 0;
            $resistanceUnit = $exerciseData['resistance_unit'] ?? 'g';

            $baseUnitValue = 0;
            if (in_array($resistanceUnit, ['lb', 'kg', 'g'])) {
                $baseUnitValue = $this->getUnitConverter()->weightToGrams($resistanceValue, $resistanceUnit);
            } elseif (in_array($resistanceUnit, ['ft', 'm', 'cm'])) {
                $baseUnitValue = $this->getUnitConverter()->distanceToMeters($resistanceValue, $resistanceUnit);
            }

            $pivotData[$exerciseData['exercise_id']] = [
                'sets' => $exerciseData['sets'],
                'reps' => $exerciseData['reps'],
                'resistance_amount' => $baseUnitValue, 
                'resistance_original_unit' => $resistanceUnit,
                'rest_seconds' => 60, 
            ];
        }
        return $pivotData;
    }
}