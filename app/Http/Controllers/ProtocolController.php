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
    // Uses traits for unit conversion and policy authorization
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

        // 1. Validation for Protocol and Exercises
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exercises' => 'required|array|min:1',
            'exercises.*.exercise_id' => ['required', 'exists:exercises,id'],
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            // Resistance is optional, but if present, units must be valid
            'exercises.*.resistance_value' => 'nullable|numeric|min:0',
            'exercises.*.resistance_unit' => [
                'required_with:exercises.*.resistance_value', 
                Rule::in(['g', 'kg', 'lb', 'm', 'ft', 'cm'])
            ],
        ]);

        // Start a transaction to ensure atomic saving of the protocol and its exercises
        DB::transaction(function () use ($validated, $request) {
            
            // 2. Create the Protocol (linked to the therapist)
            $protocol = Protocol::create([
                'therapist_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            // 3. Attach Exercises and Convert Units
            $pivotData = [];
            foreach ($validated['exercises'] as $exerciseData) {
                
                $resistanceValue = $exerciseData['resistance_value'] ?? 0;
                $resistanceUnit = $exerciseData['resistance_unit'] ?? 'g'; // Default to base unit

                $baseUnitValue = 0;
                if (in_array($resistanceUnit, ['lb', 'kg', 'g'])) {
                    // Convert to Grams (base weight unit)
                    $baseUnitValue = $this->getUnitConverter()->weightToGrams($resistanceValue, $resistanceUnit);
                } elseif (in_array($resistanceUnit, ['ft', 'm', 'cm'])) {
                    // Convert to Meters (base distance unit)
                    $baseUnitValue = $this->getUnitConverter()->distanceToMeters($resistanceValue, $resistanceUnit);
                }

                $pivotData[$exerciseData['exercise_id']] = [
                    'sets' => $exerciseData['sets'],
                    'reps' => $exerciseData['reps'],
                    
                    // CRITICAL: Save the converted value into the 'resistance_amount' column
                    'resistance_amount' => $baseUnitValue, 
                    
                    // Save the original unit for displaying back to the user
                    'resistance_original_unit' => $resistanceUnit,

                    // Default rest time
                    'rest_seconds' => 60, 
                ];
            }

            // 4. Attach exercises to the protocol
            $protocol->exercises()->sync($pivotData);
        });

        return redirect()->route('protocols.index')->with('success', 'Protocol and associated exercises created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Protocol $protocol)
    {
        // Policy check: Authorization handled here (e.g., patient can view assigned protocol, therapist can view created protocol)
        $this->authorize('view', $protocol);

        return view('protocols.show', compact('protocol'));
    }

    public function edit(Protocol $protocol)
    {
        $this->authorize('update', $protocol);
        // Implementation goes here...
    }

    public function update(Request $request, Protocol $protocol)
    {
        $this->authorize('update', $protocol);
        // Implementation goes here...
    }

    public function destroy(Protocol $protocol)
    {
        $this->authorize('delete', $protocol);
        // Implementation goes here...
    }
}