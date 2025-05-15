<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GameRequestsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function() {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/play/{game}', [GameController::class, 'index'])->name('games.play');
Route::put('/play/{game}', [GameController::class, 'update'])->name('games.update');

Route::get('/games', [GameRequestsController::class, 'index'])->middleware(['auth'])->name('games.list');
Route::post('/games', [GameRequestsController::class, 'store'])->middleware(['auth'])->name('games.store');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
