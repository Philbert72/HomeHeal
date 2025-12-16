<?php

namespace App\Http\Controllers;

use App\Models\DailySessionLog;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // CRITICAL: Import Carbon for date handling

class DailySessionLogController extends Controller
{
    /**
     * Show the form for creating a new daily session log.
     */
    public function create()
    {
        // 1. Authorization Check: Only patients can log sessions
        if (Auth::user()->role !== 'patient') {
            abort(403, 'Only patients can log a session.');
        }

        // 2. Fetch the patient's assigned protocol
        $protocol = Auth::user()->assignedProtocols()
            ->with(['exercises' => function ($query) {
                // Ensure exercises and their pivot data are loaded
                $query->withPivot(['sets', 'reps', 'resistance_amount', 'resistance_original_unit']);
            }])
            ->first();

        // 3. Handle case where no protocol is assigned
        if (!$protocol) {
            return redirect()->route('dashboard')->with('error', 'You have no active protocols assigned to log a session.');
        }

        // We pass the protocol and its exercises to the view
        return view('sessions.create', compact('protocol'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'patient') {
            abort(403, 'Only patients can store a session log.');
        }

        // 1. Validation
        $validated = $request->validate([
            'protocol_id' => 'required|exists:protocols,id',
            'exercises' => 'nullable|array', // Array of completed exercise IDs
            'exercises.*' => 'exists:exercises,id',
            'pain_score' => 'required|integer|min:0|max:10',
            'difficulty_score' => 'required|integer|min:1|max:5', 
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            
            // 2. Create the log record
            DailySessionLog::create([
                'patient_id' => Auth::id(), 
                'protocol_id' => $validated['protocol_id'],
                
                // CRITICAL FIX: Add log_date, setting it to today
                'log_date' => Carbon::today(), 
                
                'pain_score' => $validated['pain_score'],
                'difficulty_rating' => $validated['difficulty_score'],
                'notes' => $validated['notes'],
                'completed_exercises' => $validated['exercises'] ?? [],
            ]);
        });
        
        return redirect()->route('dashboard')->with('success', 'Session successfully logged!');
    }
}