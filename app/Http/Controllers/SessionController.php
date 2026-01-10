<?php

namespace App\Http\Controllers;

use App\Models\DailySessionLog;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the patient's session logs.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure only patients access their own logs
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
        // Use your DailySessionLogPolicy 'create' method
        $this->authorize('create', DailySessionLog::class);

        $user = Auth::user();
        
        // Get all protocols assigned to this patient
        // Ensure the protocols() relationship is defined in User.php
        $allProtocols = $user->protocols()->with('exercises')->get();

        // Filter out protocols that have already been logged today
        // This prevents the 500 error caused by duplicate unique constraint violations
        $today = now()->format('Y-m-d');
        $completedProtocolIds = $user->dailySessionLogs()
            ->whereDate('log_date', $today)
            ->pluck('protocol_id')
            ->toArray();

        $protocols = $allProtocols->reject(function ($protocol) use ($completedProtocolIds) {
            return in_array($protocol->id, $completedProtocolIds);
        });

        // Redirect if the patient has finished their tasks for today
        if ($protocols->isEmpty()) {
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
        $this->authorize('create', DailySessionLog::class);

        try {
            $validated = $request->validate([
                'protocol_id' => 'required|exists:protocols,id',
                'log_date' => 'required|date|before_or_equal:today',
                'pain_score' => 'required|integer|min:0|max:10',
                'difficulty_rating' => 'required|integer|min:1|max:5',
                'notes' => 'nullable|string|max:1000',
            ]);
    
            $user = Auth::user();
    
            // 1. Security Check: Verify patient is actually assigned to this protocol
            $isAssigned = DB::table('protocol_user')
                ->where('protocol_id', $validated['protocol_id'])
                ->where('user_id', $user->id)
                ->exists();
    
            if (!$isAssigned) {
                return back()->withErrors(['protocol_id' => 'You are not assigned to this protocol.']);
            }
    
            // 2. Prevent Double Logging (Final safety check)
            $alreadyLogged = DailySessionLog::where('patient_id', $user->id)
                ->where('protocol_id', $validated['protocol_id'])
                ->whereDate('log_date', $validated['log_date'])
                ->exists();
    
            if ($alreadyLogged) {
                return redirect()->route('dashboard')->with('error', 'You have already logged a session for this protocol today.');
            }
    
            // 3. Save the log
            DailySessionLog::create([
                'patient_id' => $user->id,
                'protocol_id' => $validated['protocol_id'],
                'log_date' => $validated['log_date'],
                'pain_score' => $validated['pain_score'],
                'difficulty_rating' => $validated['difficulty_rating'],
                'notes' => $validated['notes'] ?? null,
            ]);
    
            return redirect()->route('dashboard')->with('success', 'Session logged successfully!');

        } catch (\Exception $e) {
            Log::channel('stderr')->error('Error completing session: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'An unexpected error occurred while saving your session. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(DailySessionLog $session)
    {
        // Use DailySessionLogPolicy 'update' method
        $this->authorize('update', $session);

        $user = Auth::user();
        $protocols = $user->protocols()->get();

        return view('sessions.edit', compact('session', 'protocols'));
    }

    /**
     * Update the specified session in storage.
     */
    public function update(Request $request, DailySessionLog $session)
    {
        $this->authorize('update', $session);

        $validated = $request->validate([
            'protocol_id' => 'required|exists:protocols,id',
            'log_date' => 'required|date|before_or_equal:today',
            'pain_score' => 'required|integer|min:0|max:10',
            'difficulty_rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Security check if switching protocols
        if ($validated['protocol_id'] != $session->protocol_id) {
            $isAssigned = DB::table('protocol_user')
                ->where('protocol_id', $validated['protocol_id'])
                ->where('user_id', Auth::id())
                ->exists();

            if (!$isAssigned) {
                return back()->withErrors(['protocol_id' => 'You are not assigned to this new protocol.']);
            }
        }

        $session->update($validated);

        return redirect()->route('dashboard')->with('success', 'Session log updated successfully!');
    }
}