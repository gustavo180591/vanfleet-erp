<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RentalContractController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\DocumentController;

// Ruta inicial: redirige al listado de clientes o a un dashboard
Route::get('/', function () {
    return redirect()->route('customers.index');
})->name('home');

// Grupo de rutas del ERP (en el futuro acá podríamos meter middleware de auth)
Route::prefix('app')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('rental-contracts', RentalContractController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('documents', DocumentController::class)->only(['index', 'show', 'destroy']);
});
