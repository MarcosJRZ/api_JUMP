<?php

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

Route::controller(\App\Http\Controllers\user::class)->group(function () {
    Route::GET('/user', 'index');
    Route::POST('/user', 'store');
    Route::PUT('/user/{id}', 'update');
    Route::GET('/user/{id}', 'show');
    Route::DELETE('/user/{id}', 'destroy');
});

Route::controller(\App\Http\Controllers\service_order::class)->group(function () {
    Route::GET('/service_order', 'index');
    Route::POST('/service_order', 'store');
    Route::PUT('/service_order/{id}', 'update');
    Route::GET('/service_order/{id}', 'show');
    Route::DELETE('/service_order/{id}', 'destroy');
});
