<?php

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


Route::controller(\App\Http\Controllers\user::class)->group(function () {
    Route::GET('/user', 'index');
    Route::POST('/user', 'store');
    Route::PUT('/user/{id}', 'update');
    Route::GET('/user/{id}', 'show');
    Route::DELETE('/user/{id}', 'destroy');
});
