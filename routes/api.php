<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

// Rutas de prueba sin autenticación
Route::get('/test-projects', [ProjectController::class, 'index']);
Route::post('/test-projects', [ProjectController::class, 'store']);
Route::get('/test-tasks', [ProjectController::class, 'taskIndex']);
Route::post('/test-tasks', [ProjectController::class, 'taskStore']);