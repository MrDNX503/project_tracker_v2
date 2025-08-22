<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

// Rutas de prueba sin autenticación
Route::post('/test-projects', [ProjectController::class, 'store']);
Route::get('/test-projects', [ProjectController::class, 'index']);