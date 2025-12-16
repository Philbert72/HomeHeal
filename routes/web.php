<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DailySessionLogController; // CRITICAL: New Import for Logging
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


    // --- DAILY SESSION LOGGING ROUTES (NEW) ---
    Route::resource('sessions', DailySessionLogController::class)->only(['create', 'store']);
    

    // RESOURCE ROUTING FOR PROTOCOLS (Secure CRUD Endpoints)
    Route::resource('protocols', ProtocolController::class);
    
    // --- PROTOCOL ASSIGNMENT ROUTES ---
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