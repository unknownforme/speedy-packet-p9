<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\CourierController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/track/{tracking_number}', [TrackingController::class, 'show'])->name('tracking.show');


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
    // Courier routes
    Route::middleware('role:Koerier|Admin')->prefix('courier')->name('courier.')->group(function () {
        Route::get('/', [CourierController::class, 'index'])->name('index');
        Route::get('/{package}', [CourierController::class, 'show'])->name('show');
        Route::post('/{package}/outcome', [CourierController::class, 'update'])->name('update');
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

    // Return Management
    Route::post('/packages/{package}/return', [ReturnController::class, 'store'])->name('packages.return.store');
    Route::get('/packages/{package}/return-receipt', [ReturnController::class, 'downloadReceipt'])->name('packages.return.receipt');
