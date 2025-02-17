<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\MedController;



Route::get('/', [MedController::class, 'index']);
Route::post('/medscheck', [MedController::class, 'MedsCheck']);