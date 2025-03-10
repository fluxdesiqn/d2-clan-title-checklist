<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BungieAuthController;
use App\Http\Controllers\ChecklistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('bungie-login');
})->name('home');

Route::get('/checklist', [ChecklistController::class, 'index'])->name('checklist');
Route::post('/checklist', [ChecklistController::class, 'submit'])->name('checklist.submit');

Route::get('/bungie/login', [BungieAuthController::class, 'redirectToBungie'])->name('bungie.login');
Route::get('/bungie/redirect', [BungieAuthController::class, 'handleBungieCallback'])->name('bungie.redirect');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
