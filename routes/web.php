<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// --- PUBLIC ROUTES ---
Route::get('/', function () { return view('welcome'); });

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Sessions (Patient)
    Route::resource('sessions', SessionController::class);

    // Exercises (Therapist)
    Route::resource('exercises', ExerciseController::class);

    // Protocol Management
    // Note: Assign routes must be defined BEFORE the resource to avoid 404s
    Route::get('/protocols/{protocol}/assign', [ProtocolController::class, 'assign'])->name('protocols.assign');
    Route::post('/protocols/{protocol}/assign', [ProtocolController::class, 'processAssignment'])->name('protocols.processAssignment');
    Route::put('/protocols/{protocol}/patient/{patient}', [ProtocolController::class, 'updatePatientAssignment'])->name('protocols.updatePatientAssignment');
    
    Route::resource('protocols', ProtocolController::class);
    
    // Patient Monitoring
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
});