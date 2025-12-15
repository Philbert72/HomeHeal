<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// UNAUTHENTICATED ROUTES (Access BEFORE Login)

Route::get('/', function () {
    return view('welcome');
});

// Registration Routes
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');


// LOGIN ROUTES (Now handled by the controller)
Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

// FIX: Add POST route to handle form submission and authentication
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');


// AUTHENTICATED APPLICATION ROUTES (Access AFTER Login)

Route::middleware(['auth'])->group(function () {

    // Dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Add a Logout route here for completeness
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');


    // Sessions creation route
    Route::get('/sessions/create', function () {
        return view('sessions.create');
    })->name('sessions.create');

    // RESOURCE ROUTING FOR PROTOCOLS (Secure CRUD Endpoints)
    Route::resource('protocols', ProtocolController::class);
    
});