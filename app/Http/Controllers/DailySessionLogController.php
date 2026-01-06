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
        // CRITICAL FIX: Use the standardized 'protocols()' method
        $protocol = Auth::user()->protocols()
            ->with(['exercises' => function ($query) {
                // Ensure exercises and their pivot data are loaded
                $query->withPivot(['sets', 'reps', 'resistance_amount', 'resistance_original_unit']);
            }])
            ->first();

        // 3. Handle case where no protocol is assigned
        if (!$protocol) {
            return redirect()->route('dashboard')->with('error', 'You have no active protocols assigned to log a session.');
        }

        // 4. Check if a session is already logged for today
        $today = Carbon::today()->toDateString();
        $existingLog = DailySessionLog::where('patient_id', Auth::id())
                                      ->where('protocol_id', $protocol->id)
                                      ->whereDate('log_date', $today)
                                      ->exists();

        if ($existingLog) {
            return redirect()->route('dashboard')->with('error', 'You have already logged a session for the protocol "' . $protocol->title . '" today.');
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
            'exercises' => 'nullable|array',
            'exercises.*' => 'exists:exercises,id',
            'pain_score' => 'required|integer|min:0|max:10',
            'difficulty_score' => 'required|integer|min:1|max:5', 
            'notes' => 'nullable|string|max:1000',
        ]);

        // Re-check existence before storing to prevent race conditions or repeated submission errors
        $today = Carbon::today()->toDateString();
        $existingLog = DailySessionLog::where('patient_id', Auth::id())
                                      ->where('protocol_id', $validated['protocol_id'])
                                      ->whereDate('log_date', $today)
                                      ->exists();

        if ($existingLog) {
             return redirect()->route('dashboard')->with('error', 'A session log for this protocol has already been submitted today.');
        }


        DB::transaction(function () use ($validated) {
            
            // Create the log record
            DailySessionLog::create([
                'patient_id' => Auth::id(), 
                'protocol_id' => $validated['protocol_id'],
                
                // Set log_date to today
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