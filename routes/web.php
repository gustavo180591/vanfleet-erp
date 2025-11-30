<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
require __DIR__.'/auth.php';

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Redirect root to dashboard for authenticated users
    Route::redirect('/', '/dashboard');
});

// App Routes
Route::middleware('auth')->prefix('app')->name('app.')->group(function () {
    // Vehicles
    Route::resource('vehicles', \App\Http\Controllers\VehicleController::class);
    
    // Customers
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    
    // Rental Contracts
    Route::resource('rental-contracts', \App\Http\Controllers\RentalContractController::class);
    
    // Invoices
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    
    // Maintenances
    Route::resource('maintenances', \App\Http\Controllers\MaintenanceController::class);
    
    // Documents
    Route::resource('documents', \App\Http\Controllers\DocumentController::class);
});
