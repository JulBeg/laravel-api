<?php

use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\Token\TokenAuthenticatedController;
use App\Http\Controllers\Auth\Token\TokenRegisteredController;
use Illuminate\Support\Facades\Route;

Route::post('/register', TokenRegisteredController::class);

Route::get('/user', AuthenticatedUserController::class)->middleware('auth:sanctum');

Route::post('/login', [TokenAuthenticatedController::class, 'store']);
Route::post('/logout', [TokenAuthenticatedController::class, 'destroy'])->middleware('auth:sanctum');
