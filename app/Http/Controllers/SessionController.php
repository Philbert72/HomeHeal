<?php

namespace App\Http\Controllers;

use App\Models\DailySessionLog;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Display a listing of the patient's session logs.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'patient') {
            abort(403, 'Unauthorized access.');
        }

        $sessions = $user->dailySessionLogs()
            ->with('protocol')
            ->orderBy('log_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        
        // Only patients can log sessions
        if (!$user || $user->role !== 'patient') {
            return redirect()->route('dashboard')->with('error', 'Only patients can log sessions.');
        }

        // Get protocols assigned to this patient
        $allProtocols = $user->protocols()->with('exercises')->get();

        // Filter out protocols already completed TODAY
        $completedProtocolIds = $user->dailySessionLogs()
            ->whereDate('log_date', now()->today())
            ->pluck('protocol_id')
            ->toArray();

        $protocols = $allProtocols->reject(function ($protocol) use ($completedProtocolIds) {
            return in_array($protocol->id, $completedProtocolIds);
        });

        if ($protocols->isEmpty()) {
            // Check if it's because they are all done, or none assigned
            if ($allProtocols->isNotEmpty()) {
                 return redirect()->route('dashboard')->with('success', 'Great job! You have completed all your assigned protocols for today.');
            }
            return redirect()->route('dashboard')->with('error', 'No active protocols assigned. Please contact your therapist.');
        }

        $selectedProtocolId = $request->query('protocol_id');

        return view('sessions.create', compact('protocols', 'selectedProtocolId'));
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
    /**
     * Show the form for editing the specified session.
     */
    public function edit(DailySessionLog $session)
    {
        $user = Auth::user();

        // Authorization: Ensure the log belongs to the authenticated user
        if ($session->patient_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Get protocols for the dropdown (though users usually shouldn't change protocol, it's possible)
        $protocols = $user->protocols()->with('exercises')->get();

        return view('sessions.edit', compact('session', 'protocols'));
    }

    /**
     * Update the specified session in storage.
     */
    public function update(Request $request, DailySessionLog $session)
    {
        $user = Auth::user();

        if ($session->patient_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'protocol_id' => 'required|exists:protocols,id',
            'log_date' => 'required|date|before_or_equal:today',
            'pain_score' => 'required|integer|min:0|max:10',
            'difficulty_rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verify protocol assignment if changed
        if ($validated['protocol_id'] != $session->protocol_id) {
            $protocol = Protocol::find($validated['protocol_id']);
            if (!$protocol->patients()->where('user_id', $user->id)->exists()) {
                return back()->withErrors(['protocol_id' => 'You are not assigned to this protocol.']);
            }
        }

        $session->update($validated);

        return redirect()->route('dashboard')->with('success', 'Session log updated successfully!');
    }
}
