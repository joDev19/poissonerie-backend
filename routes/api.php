<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EntrerController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->middleware("auth:sanctum");
Route::apiResource("/marques", MarqueController::class)->middleware("auth:sanctum");
Route::apiResource("/fournisseurs", FournisseurController::class)->middleware("auth:sanctum");
Route::resource("/entrers", EntrerController::class)->middleware("auth:sanctum");
Route::resource('/sells', VenteController::class)->middleware("auth:sanctum");
Route::resource('/sells', VenteController::class)->middleware("auth:sanctum");
Route::put('sells/encaisser/{id}', [VenteController::class, 'encaisser'])->middleware("auth:sanctum");
Route::get('/stat', [StatController::class, 'index'])->middleware("auth:sanctum");
Route::resource("/products", ProductController::class)->middleware("auth:sanctum");
Route::put('/change-infos', [UserController::class, 'changeInfo'])->middleware("auth:sanctum");
Route::post('/password-change', [UserCOntroller::class, 'changePassword'])->middleware('auth:sanctum');
Route::resource('employees', EmployeeController::class)->middleware('auth:sanctum');
// Route::resource('employees', EmployeeController::class);
Route::get('/vente/stats', [VenteController::class, 'statVente'])->middleware('auth:sanctum');
