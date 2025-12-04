<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticuloApiController;

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

// Rutas API para Artículos
Route::prefix('articulos')->group(function () {
    Route::get('/', [ArticuloApiController::class, 'index']);
    Route::get('/{articulo}', [ArticuloApiController::class, 'show']);
    Route::post('/', [ArticuloApiController::class, 'store']);
    Route::put('/{articulo}', [ArticuloApiController::class, 'update']);
    Route::delete('/{articulo}', [ArticuloApiController::class, 'destroy']);
});

// Rutas adicionales para obtener datos relacionados
Route::get('/revistas', function () {
    return \App\Models\Revista::all();
});

Route::get('/columnistas', function () {
    return \App\Models\Columnista::all();
});
