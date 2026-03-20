<?php

use App\Http\Controllers\FiliereController;
use App\Http\Controllers\FiliereResourceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('filieres-resources', FiliereResourceController::class)
    ->parameters(['filieres-resources' => 'codeFiliere']);
