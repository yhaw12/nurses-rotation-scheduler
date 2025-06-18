<?php

use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [UserController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::resource('rosters', RosterController::class)->only(['create', 'store', 'show']);
    Route::middleware('admin')->group(function () {
        Route::resource('disciplines', DisciplineController::class);
        Route::resource('disciplines.units', UnitController::class);
    });
});