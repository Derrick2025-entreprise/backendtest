<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ECController;
use App\Http\Controllers\EnseignementController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProgrammationController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\UEController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('filieres/export-pdf', [FiliereController::class, 'exportPdf']);
    Route::get('/filieres/export-excel', [FiliereController::class, 'exportExcel']);
    Route::apiResource("filieres", FiliereController::class);
    Route::apiResource("niveaux", NiveauController::class);
    Route::apiResource("ue", UEController::class);
    Route::apiResource("ec", ECController::class);
    Route::apiResource("salles", SalleController::class);
    Route::apiResource("programmations", ProgrammationController::class);
    Route::apiResource("enseignements", EnseignementController::class);

    Route::post('logout', [AuthController::class, 'logout']);

});
Route::apiResource("personnels", PersonnelController::class);


Route::post('login', [AuthController::class, 'login']);
