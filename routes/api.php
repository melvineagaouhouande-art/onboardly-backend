<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StagiaireController;
use App\Http\Controllers\ParcoursController;
use App\Http\Controllers\QueteController;
use App\Http\Controllers\StagiaireQueteController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\StagiaireBadgeController;

// Route pour la gestion des utilisateurs (RH, Managers, Admins)
Route::apiResource('users', UserController::class);

// Routes pour la gestion des stagiaires et du parcours
Route::apiResource('stagiaires', StagiaireController::class);
Route::apiResource('parcours', ParcoursController::class);
Route::apiResource('quetes', QueteController::class);
Route::apiResource('badges', BadgeController::class);

// Routes d'affectation et de suivi de progression
Route::apiResource('stagiaire-quetes', StagiaireQueteController::class);
Route::apiResource('stagiaire-badges', StagiaireBadgeController::class);