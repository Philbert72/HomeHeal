<?php

namespace App\Http\Controllers;

use App\Models\DailySessionLog;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SessionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'patient') abort(403);

        $sessions = $user->dailySessionLogs()
            ->with('protocol')
            ->latest('log_date')
            ->paginate(10);

        return view('sessions.index', compact('sessions'));
    }

    public function create(Request $request)
    {
        // 1. Check Policy
        $this->authorize('create', DailySessionLog::class);

        $user = Auth::user();
        $allProtocols = $user->protocols()->with('exercises')->get();

        // 2. Filter out protocols already logged today
        $completedProtocolIds = $user->dailySessionLogs()
            ->whereDate('log_date', now()->format('Y-m-d'))
            ->pluck('protocol_id')
            ->toArray();

        $protocols = $allProtocols->reject(fn($p) => in_array($p->id, $completedProtocolIds));

        if ($protocols->isEmpty()) {
            return redirect()->route('dashboard')->with('success', 'You have completed all protocols for today!');
        }

        $selectedProtocolId = $request->query('protocol_id');
        return view('sessions.create', compact('protocols', 'selectedProtocolId'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', DailySessionLog::class);

        $validated = $request->validate([
            'protocol_id' => 'required|exists:protocols,id',
            'log_date' => 'required|date|before_or_equal:today',
            'pain_score' => 'required|integer|min:0|max:10',
            'difficulty_rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $protocol = Protocol::findOrFail($validated['protocol_id']);

        // Security check: Ensure patient is actually assigned to this protocol
        if (!$protocol->patients()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['protocol_id' => 'You are not assigned to this protocol.']);
        }

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

    public function edit(DailySessionLog $session)
    {
        $this->authorize('update', $session);
        $protocols = Auth::user()->protocols;
        return view('sessions.edit', compact('session', 'protocols'));
    }

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

        $session->update($validated);
        return redirect()->route('sessions.index')->with('success', 'Log updated.');
    }
}