<?php

use App\Http\Controllers\ModelController;

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/red-view', function () {
//     return view('red-viewer');
// });

// Route::get('/yellow-view', function () {
//     return view('yellow-viewer');
// });

Route::get('/model-view', [ModelController::class, 'view']);