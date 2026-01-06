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
        if (Auth::user()->role !== 'therapist') {
            abort(403, 'Unauthorized access.');
        }
        
        $patient->load(['assignedProtocols', 'dailySessionLogs.protocol']);

        $logs = $patient->dailySessionLogs()->latest('log_date')->paginate(10);

        return view('patients.show', compact('patient', 'logs'));
    }
}
