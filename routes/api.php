<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CepController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ImportPatientController;

/**
 * API Endpoints Global Application Routes
 * Collection of endpoints routes for the application
 */

Route::prefix('v1')->group(function () {
    //
    Route::get('/patients', [PatientController::class, 'index'])->name('api.index.patient');
    Route::post('/patients', [PatientController::class, 'store'])->name('api.store.patient');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('api.show.patient');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('api.update.patient');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('api.destroy.patient');
    //
    Route::get('/cep/{cep}', [CepController::class, 'show'])->name('api.cep.show');
    //
    Route::post('/patients/import', [ImportPatientController::class, 'import'])->name('api.import.patient');
});

