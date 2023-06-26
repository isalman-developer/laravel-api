<?php

use App\Http\Controllers\Api\V1\TravelController;
use App\Http\Controllers\TourController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// resource routes
Route::resource('/travels', TravelController::class);
Route::resource('/tours', TourController::class);

// specific routes

// specifying that travel should be slug, or you can write the function getRouteName in model
Route::get('travels/{travel:slug}/tours', [TourController::class, 'index']);
