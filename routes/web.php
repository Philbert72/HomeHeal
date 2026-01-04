<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// UNAUTHENTICATED ROUTES (Access BEFORE Login)

Route::get('/', function () {
    return view('welcome');
});

// Registration Routes
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');


// LOGIN ROUTES 
Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');


// AUTHENTICATED APPLICATION ROUTES (Access AFTER Login)

Route::middleware(['auth'])->group(function () {

    // Dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Logout route
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');




    // Sessions routes (Patient only)
    Route::get('/sessions', [App\Http\Controllers\SessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create', [App\Http\Controllers\SessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions', [App\Http\Controllers\SessionController::class, 'store'])->name('sessions.store');
    Route::get('/sessions/{session}/edit', [App\Http\Controllers\SessionController::class, 'edit'])->name('sessions.edit');
    Route::put('/sessions/{session}', [App\Http\Controllers\SessionController::class, 'update'])->name('sessions.update');




    // RESOURCE ROUTING FOR EXERCISES (Therapist only)
    Route::resource('exercises', App\Http\Controllers\ExerciseController::class);

    // Protocol Assignment and Management Routes
    Route::get('/protocols/{protocol}/assign', [App\Http\Controllers\ProtocolController::class, 'assign'])->name('protocols.assign');
    Route::post('/protocols/{protocol}/assign', [App\Http\Controllers\ProtocolController::class, 'processAssignment'])->name('protocols.processAssignment');
    Route::put('/protocols/{protocol}/patient/{patient}', [App\Http\Controllers\ProtocolController::class, 'updatePatientAssignment'])->name('protocols.updatePatientAssignment');
    Route::resource('protocols', App\Http\Controllers\ProtocolController::class);
    
    // Patient Monitoring Route
    Route::get('/patients/{patient}', [App\Http\Controllers\PatientController::class, 'show'])
        ->middleware('role:therapist')
        ->name('patients.show');
    // RESOURCE ROUTING FOR PROTOCOLS (Secure CRUD Endpoints)
    Route::resource('protocols', ProtocolController::class);
    
    // --- PROTOCOL ASSIGNMENT ROUTES (FIXED) ---
    // We apply the 'role' middleware to the individual routes here,
    // ensuring both 'auth' and 'role:therapist' are required.
    Route::prefix('protocols/{protocol}')->group(function () {
        
        // GET route for the assignment form
        Route::get('assign', [ProtocolController::class, 'assign'])
             ->middleware('role:therapist')
             ->name('protocols.assign');
        
        // POST route for processing the form submission
        Route::post('assign', [ProtocolController::class, 'processAssignment'])
             ->middleware('role:therapist')
             ->name('protocols.processAssignment');
    });
    
});