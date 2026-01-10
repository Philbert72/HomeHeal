<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PatientController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // Sessions (Patient)
    Route::resource('sessions', SessionController::class);

    // Exercises (Therapist)
    Route::resource('exercises', ExerciseController::class);

    // Protocols (Therapist)
    Route::prefix('protocols/{protocol}')->group(function () {
        Route::get('assign', [ProtocolController::class, 'assign'])->name('protocols.assign');
        Route::post('assign', [ProtocolController::class, 'processAssignment'])->name('protocols.processAssignment');
        Route::put('patient/{patient}', [ProtocolController::class, 'updatePatientAssignment'])->name('protocols.updatePatientAssignment');
    });
    
    Route::resource('protocols', ProtocolController::class);
    
    // Patient Monitoring
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
});