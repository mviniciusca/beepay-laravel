<?php

use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * API Endpoints Global Application Routes
 * Collection of endpoints routes for the application
 */

Route::prefix('v1')->group(function () {
    Route::get('/patients', [PatientController::class, 'index'])->name('api.index.patient');
    Route::post('/patients', [PatientController::class, 'store'])->name('api.store.patient');
    Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('api.destroy.patient');
    Route::put('/patients/{id}', [PatientController::class, 'update'])->name('api.update.patient');
});

