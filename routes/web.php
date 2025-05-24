<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Calendar routes
Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');
