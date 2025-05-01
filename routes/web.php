<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GameRequestsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [GameController::class, 'index'])->name('home');

Route::get('/games', [GameRequestsController::class, 'index'])->middleware(['auth'])->name('games.list');
Route::post('/games', [GameRequestsController::class, 'store'])->middleware(['auth'])->name('games.store');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
