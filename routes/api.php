<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntrerController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('login', [AuthController::class, 'login']);
Route::apiResource("/marques", MarqueController::class);
Route::apiResource("/fournisseurs", FournisseurController::class)->middleware("auth:sanctum");
Route::resource("/entrers", EntrerController::class)->middleware("auth:sanctum");
Route::resource("/products", ProductController::class)->middleware("auth:sanctum");

