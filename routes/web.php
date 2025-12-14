<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProtocolController; // Keep this import

Route::get('/', function () {
    return view('welcome');
});

// --- 1. UNAUTHENTICATED ROUTES (From UI Prototype - HEAD) ---
// These are routes necessary before login (login form, registration form, etc.)

Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// The friend's POST route is kept as a placeholder for submitting registration.
Route::post('/register', function () {
    return "Logic to save user goes here!";
})->name('register.store');


// --- 2. AUTHENTICATED APPLICATION ROUTES (From Security Layer - MAIN) ---
// All routes here require the user to be logged in, enforced by 'auth' middleware.

Route::middleware(['auth'])->group(function () {

    // Dashboard route (from UI prototype, now secured)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Sessions creation route (from UI prototype, now secured)
    Route::get('/sessions/create', function () {
        return view('sessions.create');
    })->name('sessions.create');

    // RESOURCE ROUTING FOR PROTOCOLS (From Security Layer - MAIN)
    // This defines the secure CRUD endpoints, replacing the friend's simple protocols route.
    Route::resource('protocols', ProtocolController::class);
    
    // Placeholder for Daily Session Logs route (Patient CRUD)
    // Route::resource('session-logs', SessionLogController::class);
});