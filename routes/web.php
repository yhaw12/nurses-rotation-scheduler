<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SubunitController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Root route to redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [UserController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Disciplines CRUD
    Route::resource('disciplines', DisciplineController::class);

    // Units nested under discipline
    Route::resource('disciplines.units', UnitController::class)->shallow();

    // Subunits nested under units
    Route::get('disciplines/{discipline}/units/{unit}/subunits/edit', [SubunitController::class, 'edit'])->name('disciplines.units.subunits.edit');
    Route::patch('disciplines/{discipline}/units/{unit}/subunits', [SubunitController::class, 'update'])->name('disciplines.units.subunits.update');
    Route::delete('disciplines/{discipline}/units/{unit}/subunits/{subunit}', [SubunitController::class, 'destroy'])->name('disciplines.units.subunits.destroy');

    // Rosters
    Route::get('rosters', [RosterController::class, 'index'])->name('rosters.index');
    Route::get('rosters/create/{discipline}', [RosterController::class, 'create'])->name('rosters.create');
    Route::post('rosters', [RosterController::class, 'store'])->name('rosters.store');
    Route::get('rosters/{roster}', [RosterController::class, 'show'])->name('rosters.show');
    Route::patch('rosters/{roster}/reshuffle', [RosterController::class, 'reshuffle'])->name('rosters.reshuffle');
    Route::post('/rosters/{roster}/shuffle', [RosterController::class, 'shuffle'])->name('rosters.shuffle');
    Route::delete('/rosters/{roster}', [RosterController::class, 'destroy'])->name('rosters.destroy');
});