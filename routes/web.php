<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BungieAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('bungie-login');
})->name('home');

Route::get('/check-list', function () {
    return view('checklist');
})->name('checklist');

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
