<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use App\Models\Exercise; 
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
        // Policy check: Only therapists can view all protocols
        $this->authorize('viewAny', Protocol::class);

        // Fetch protocols created by the currently authenticated therapist
        $protocols = Auth::user()->createdProtocols()->with('exercises')->paginate(10);

        return view('protocols.index', compact('protocols'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Policy check: Only therapists can create protocols
        $this->authorize('create', Protocol::class);

        // Fetch all exercises to populate the dropdown/selector on the form
        $exercises = Exercise::all();

        return view('protocols.create', compact('exercises'));
    }

    /**
     * Store a newly created resource in storage (CRUCIAL STEP FOR UNIT CONVERSION).
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
            
            // 1. Update Protocol details
            $protocol->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            // 2. Prepare and sync exercises (pivot data)
            $pivotData = $this->preparePivotData($validated['exercises']);

            $protocol->exercises()->sync($pivotData);
        });

        return redirect()->route('protocols.index')->with('success', 'Protocol "' . $protocol->title . '" updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Protocol $protocol)
    {
        $this->authorize('view', $protocol);
        $protocol->load(['exercises', 'therapist']);
        return view('protocols.show', compact('protocol'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Protocol $protocol)
    {
        // 1. Authorization: Ensure only the creator/authorized therapist can delete
        $this->authorize('delete', $protocol);

        $protocolTitle = $protocol->title;
        
        // 2. Deletion: Laravel deletes the protocol, and CASCADE deletes pivot records
        $protocol->delete();

        // 3. Redirect with success message
        return redirect()->route('protocols.index')
                         ->with('success', 'Protocol "' . $protocolTitle . '" and all associated exercises were successfully deleted.');
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