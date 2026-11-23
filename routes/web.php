<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Impersonation routes
    Route::middleware('impersonate')->group(function () {
        Route::get('/impersonate/{user}', [App\Http\Controllers\ImpersonationController::class, 'start'])->name('impersonate.start');
        Route::get('/impersonate/stop', [App\Http\Controllers\ImpersonationController::class, 'stop'])->name('impersonate.stop');
    });
});

require __DIR__.'/auth.php';
