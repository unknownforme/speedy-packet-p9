<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistributionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $stats = [
        'total' => \App\Models\Package::where('receiver_id', $user->id)->orWhere('company_id', $user->company_id ?? 0)->count(),
        'active' => \App\Models\Package::where('receiver_id', $user->id)->orWhere('company_id', $user->company_id ?? 0)->where('current_status', '!=', 'Bezorgd')->count(),
        'delivered' => \App\Models\Package::where('receiver_id', $user->id)->orWhere('company_id', $user->company_id ?? 0)->where('current_status', 'Bezorgd')->count(),
    ];
    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Package routes
    Route::resource('packages', App\Http\Controllers\PackageController::class)->only(['index', 'create', 'store', 'show']);

    // Distribution Center routes
    Route::middleware('role:Distributiemedewerker|Admin')->group(function () {
        Route::get('/distribution', [DistributionController::class, 'index'])->name('distribution.index');
        Route::post('/distribution/scan', [DistributionController::class, 'scan'])->name('distribution.scan');
        Route::post('/distribution/update-status', [DistributionController::class, 'updateStatus'])->name('distribution.update-status');
    });

    // Impersonation routes
    Route::middleware('impersonate')->group(function () {
        Route::get('/impersonate/{user}', [App\Http\Controllers\ImpersonationController::class, 'start'])->name('impersonate.start');
        Route::get('/impersonate/stop', [App\Http\Controllers\ImpersonationController::class, 'stop'])->name('impersonate.stop');
    });

    // Admin Dashboard
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';
