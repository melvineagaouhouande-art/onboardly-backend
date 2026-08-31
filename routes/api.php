<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StagiaireController;
use App\Http\Controllers\ParcoursController;
use App\Http\Controllers\QueteController;
use App\Http\Controllers\StagiaireQueteController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\StagiaireBadgeController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\AuthController;

// ============================================================
// ROUTES PUBLIQUES (Accessibles sans authentification)
// ============================================================
Route::post('register', [AuthController::class, 'register']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('login', [AuthController::class, 'login']);
Route::get('departements', [DepartementController::class, 'index']);

// ============================================================
// ROUTES PROTÉGÉES PAR AUTHENTIFICATION JWT
// ============================================================
Route::middleware('auth:api')->group(function () {
    
    // --- Profil & Session utilisateur ---
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // --- Gestion RH & Administration (Réservé aux admin_rh) ---
    Route::middleware('role:admin_rh')->group(function () {
        // Validation des comptes utilisateurs (activer/suspendre)
        Route::patch('users/{id}/statut', [AuthController::class, 'changerStatutCompte']);
        
        // CRUD complet pour les utilisateurs généraux
        Route::apiResource('users', UserController::class);
    });

    // --- Gestion des Stagiaires (Admins et Managers) ---
    Route::middleware('role:admin_rh,manager')->group(function () {
        Route::apiResource('stagiaires', StagiaireController::class);
        
        // Modification des parcours et quêtes
        Route::post('parcours', [ParcoursController::class, 'store']);
        Route::put('parcours/{id}', [ParcoursController::class, 'update']);
        Route::delete('parcours/{id}', [ParcoursController::class, 'destroy']);
        
        Route::post('quetes', [QueteController::class, 'store']);
        Route::put('quetes/{id}', [QueteController::class, 'update']);
        Route::delete('quetes/{id}', [QueteController::class, 'destroy']);

        Route::post('badges', [BadgeController::class, 'store']);
        Route::put('badges/{id}', [BadgeController::class, 'update']);
        Route::delete('badges/{id}', [BadgeController::class, 'destroy']);
    });

    // --- Lecture autorisée à tous (Employés, Managers, RH) ---
    Route::get('parcours', [ParcoursController::class, 'index']);
    Route::get('parcours/{id}', [ParcoursController::class, 'show']);
    
    Route::get('quetes', [QueteController::class, 'index']);
    Route::get('quetes/{id}', [QueteController::class, 'show']);
    
    Route::get('badges', [BadgeController::class, 'index']);
    Route::get('badges/{id}', [BadgeController::class, 'show']);

    // --- Suivi des Quêtes et Progression des Stagiaires ---
    // Les stagiaires peuvent voir et mettre à jour leur avancement
    Route::apiResource('stagiaire-quetes', StagiaireQueteController::class);
    Route::apiResource('stagiaire-badges', StagiaireBadgeController::class);
});