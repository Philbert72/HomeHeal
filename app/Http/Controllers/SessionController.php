<?php

namespace App\Http\Controllers;

use App\Models\DailySessionLog;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Only patients can log sessions
        if (!$user || $user->role !== 'patient') {
            return redirect()->route('dashboard')->with('error', 'Only patients can log sessions.');
        }

        // Get protocols assigned to this patient
        $protocols = $user->protocols()->with('exercises')->get();

        if ($protocols->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'No protocols assigned yet. Contact your therapist.');
        }

        return view('sessions.create', compact('protocols'));
    }

    /**
     * Store a newly created session in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate
        $validated = $request->validate([
            'protocol_id' => 'required|exists:protocols,id',
            'log_date' => 'required|date|before_or_equal:today',
            'pain_score' => 'required|integer|min:0|max:10',
            'difficulty_rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verify the protocol is actually assigned to this patient
        $protocol = Protocol::find($validated['protocol_id']);
        if (!$protocol->patients()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['protocol_id' => 'You are not assigned to this protocol.']);
        }

        // Create the session log
        DailySessionLog::create([
            'patient_id' => $user->id,
            'protocol_id' => $validated['protocol_id'],
            'log_date' => $validated['log_date'],
            'pain_score' => $validated['pain_score'],
            'difficulty_rating' => $validated['difficulty_rating'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Session logged successfully!');
    }
}
