<?php

use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * API Endpoints Global Application Routes
 * Collection of endpoints routes for the application
 */

Route::post('v1/patients', [PatientController::class, 'store'])->name('api.store.patient');
