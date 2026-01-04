<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display the specified patient's details, including assigned protocols and session history.
     */
    public function show(User $patient)
    {
        // Ensure the authenticated user is a therapist
        if (Auth::user()->role !== 'therapist') {
            abort(403, 'Unauthorized access.');
        }

        // Ideally, check if this patient is actually assigned to one of the therapist's protocols
        // For now, assuming if you are a therapist, you can view patient details (or we can add a check)
        
        $patient->load(['assignedProtocols', 'dailySessionLogs.protocol']);

        $logs = $patient->dailySessionLogs()->latest('log_date')->paginate(10);

        return view('patients.show', compact('patient', 'logs'));
    }
}
