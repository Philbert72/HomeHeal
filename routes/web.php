<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProtocolController;

Route::get('/', function () {
    return view('welcome');
});


// --- 2. AUTHENTICATED APPLICATION ROUTES ---

// Routes wrapped in the 'auth' middleware ensure a user must be logged in.
Route::middleware(['auth'])->group(function () {

    // RESOURCE ROUTING FOR PROTOCOLS (CRUD)
    // This single line defines all 7 CRUD routes for the protocols resource (index, store, show, update, delete, etc.).
    // This fixes the 404 error your tests encountered when trying to POST to /protocols.
    Route::resource('protocols', ProtocolController::class);

    // Placeholder for Daily Session Logs route (Patient CRUD)
    // Route::resource('session-logs', SessionLogController::class);
    
});