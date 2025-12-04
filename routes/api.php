<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticuloApiController;
use App\Http\Controllers\Api\RevistaApiController;
use App\Http\Controllers\Api\ColumnistaApiController;

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

// Rutas API para Revistas
Route::prefix('revistas')->group(function () {
    Route::get('/', [RevistaApiController::class, 'index']);
    Route::get('/{revista}', [RevistaApiController::class, 'show']);
    Route::post('/', [RevistaApiController::class, 'store']);
    Route::put('/{revista}', [RevistaApiController::class, 'update']);
    Route::delete('/{revista}', [RevistaApiController::class, 'destroy']);
});

// Rutas API para Columnistas
Route::prefix('columnistas')->group(function () {
    Route::get('/', [ColumnistaApiController::class, 'index']);
    Route::get('/{columnista}', [ColumnistaApiController::class, 'show']);
    Route::post('/', [ColumnistaApiController::class, 'store']);
    Route::put('/{columnista}', [ColumnistaApiController::class, 'update']);
    Route::delete('/{columnista}', [ColumnistaApiController::class, 'destroy']);
});

// Rutas para obtener listas simples (sin paginación)
Route::get('/revistas-list', function () {
    return \App\Models\Revista::orderBy('titulo')->get();
});

Route::get('/columnistas-list', function () {
    return \App\Models\Columnista::orderBy('nombre')->get();
});

// Dashboard Stats
Route::get('/dashboard/stats', function () {
    return response()->json([
        'articulos' => [
            'total' => \App\Models\Articulo::count(),
            'recientes' => \App\Models\Articulo::with(['columnista', 'revista'])
                ->latest()
                ->limit(5)
                ->get()
        ],
        'revistas' => [
            'total' => \App\Models\Revista::count(),
            'recientes' => \App\Models\Revista::withCount('articulos')
                ->latest()
                ->limit(5)
                ->get()
        ],
        'columnistas' => [
            'total' => \App\Models\Columnista::count(),
            'recientes' => \App\Models\Columnista::latest()
                ->limit(5)
                ->get()
        ]
    ]);
});
