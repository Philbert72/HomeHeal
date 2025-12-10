<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- UI PROTOTYPE ROUTES ---

Route::get('/login', function () {
    return view('auth.login'); // Make sure this file exists!
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard'); // Make sure this file exists!
})->name('dashboard');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    return "Logic to save user goes here!";
})->name('register.store');

Route::get('/sessions/create', function () {
    return view('sessions.create');
})->name('sessions.create');

Route::get('/protocols', function () {
    return view('protocols.index');
})->name('protocols.index');