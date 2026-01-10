<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use App\Models\Exercise; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProtocolController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the protocols.
     */
    public function index()
    {
        $this->authorize('viewAny', Protocol::class);
        
        $protocols = Auth::user()->createdProtocols()
            ->with('exercises')
            ->latest()
            ->paginate(10);
            
        return view('protocols.index', compact('protocols'));
    }

    /**
     * Show the form for creating a new protocol.
     */
    public function create()
    {
        $this->authorize('create', Protocol::class);
        $exercises = Exercise::all();
        return view('protocols.create', compact('exercises'));
    }

    /**
     * Store a newly created protocol.
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
        ]);

        DB::transaction(function () use ($validated, $request) {
            $protocol = Protocol::create([
                'therapist_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            $pivotData = [];
            foreach ($validated['exercises'] as $ex) {
                $pivotData[$ex['exercise_id']] = [
                    'sets' => $ex['sets'],
                    'reps' => $ex['reps'],
                    'resistance_amount' => 0, // Simplified for now
                    'resistance_original_unit' => 'g',
                    'rest_seconds' => 60, 
                ];
            }

            $protocol->exercises()->sync($pivotData);
        });

        return redirect()->route('protocols.index')->with('success', 'Protocol created successfully.');
    }

    /**
     * Display the specified protocol.
     */
    public function show(Protocol $protocol)
    {
        $this->authorize('view', $protocol);
        $protocol->load(['exercises', 'therapist', 'patients']); 
        return view('protocols.show', compact('protocol'));
    }

    /**
     * Show the form for assigning the protocol to patients.
     */
    public function assign(Protocol $protocol)
    {
        $this->authorize('update', $protocol); 

        // Use the scope defined in your User model
        $allPatients = User::where('role', 'patient')->orderBy('name')->get();
        $assignedPatientIds = $protocol->patients->pluck('id')->toArray();

        return view('protocols.assign', compact('protocol', 'allPatients', 'assignedPatientIds'));
    }

    /**
     * Handle the assignment logic.
     */
    public function processAssignment(Request $request, Protocol $protocol)
    {
        $this->authorize('update', $protocol);

        $validated = $request->validate([
            'patients' => 'nullable|array',
            'patients.*' => 'exists:users,id',
            'duration_days' => 'required|integer|min:1|max:365',
        ]);

        $patientIds = $validated['patients'] ?? [];
        $durationDays = $validated['duration_days'];

        $pivotData = [];
        foreach ($patientIds as $id) {
            // This maps the duration_days to the pivot table
            $pivotData[$id] = ['duration_days' => $durationDays];
        }

        // Sync updates the list and saves the duration_days
        $protocol->patients()->sync($pivotData);

        return redirect()->route('protocols.show', $protocol)
            ->with('success', 'Protocol assignment updated successfully.');
    }

    /**
     * Remove the protocol.
     */
    public function destroy(Protocol $protocol)
    {
        $this->authorize('delete', $protocol);
        $protocol->delete();

        return redirect()->route('protocols.index')->with('success', 'Protocol deleted.');
    }
}