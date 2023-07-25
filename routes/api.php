<?php

use App\Http\Controllers\Api\V1\TravelController;
use App\Http\Controllers\TourController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\LoginController;

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

// specifying that travel should be slug, or you can write the function getRouteName in model
Route::get('travels/{travel:slug}/tours', [TourController::class, 'index']);

// admin travel route
Route::group(['prefix' => 'admin', 'middleware' =>  ['auth:sanctum']], function(){

    Route::middleware(['role:admin'])->group(function(){
        Route::post('/travels', [Admin\TravelController::class, 'store']);
        Route::post('/travels/{travel}/tours', [Admin\TourController::class, 'store']);
    });

    Route::put('/travels/{travel}', [Admin\TravelController::class, 'update']);
});

// login api
Route::post('/login', LoginController::class);
