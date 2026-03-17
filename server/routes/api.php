<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourrierController;
use App\Http\Controllers\Api\LigneController;
use App\Http\Controllers\Api\PosteController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SearchController;

use App\Http\Controllers\Api\DownloadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Dashboard & Search
    Route::get('/stats/dashboard', [DashboardController::class, 'index']);
    Route::get('/search/global', [SearchController::class, 'index']);

    // File Download
    Route::get('/download', [DownloadController::class, 'download']);

    // Courriers
    Route::get('/courriers', [CourrierController::class, 'index']);
    Route::get('/courriers/{courrier}', [CourrierController::class, 'show']);
    Route::middleware('role:admin,archiviste')->group(function () {
        Route::post('/courriers', [CourrierController::class, 'store']);
        Route::put('/courriers/{courrier}', [CourrierController::class, 'update']);
        Route::delete('/courriers/{courrier}', [CourrierController::class, 'destroy']);
    });

    // Lignes (Plans)
    Route::get('/lignes', [LigneController::class, 'index']);
    Route::get('/lignes/{ligne}', [LigneController::class, 'show']);
    Route::middleware('role:admin,archiviste')->group(function () {
        Route::post('/lignes', [LigneController::class, 'store']);
        Route::put('/lignes/{ligne}', [LigneController::class, 'update']);
        Route::delete('/lignes/{ligne}', [LigneController::class, 'destroy']);
    });

    // Postes
    Route::get('/postes', [PosteController::class, 'index']);
    Route::get('/postes/{poste}', [PosteController::class, 'show']);
    Route::middleware('role:admin,archiviste')->group(function () {
        Route::post('/postes', [PosteController::class, 'store']);
        Route::put('/postes/{poste}', [PosteController::class, 'update']);
        Route::delete('/postes/{poste}', [PosteController::class, 'destroy']);
    });

    // User Management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
    });
});
