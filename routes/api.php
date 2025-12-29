<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticuloApiController;
use App\Http\Controllers\Api\RevistaApiController;
use App\Http\Controllers\Api\ColumnistaApiController;
use App\Http\Controllers\Api\EditorialApiController;
use App\Http\Controllers\Api\NoticiaApiController;
use App\Http\Controllers\Api\EntrevistaApiController;
use App\Http\Controllers\Api\CableATierraApiController;
use App\Http\Controllers\Api\AtractivoApiController;

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
    Route::get('/available-images', [ColumnistaApiController::class, 'getAvailableImages']);
    Route::get('/{columnista}', [ColumnistaApiController::class, 'show']);
    Route::post('/', [ColumnistaApiController::class, 'store']);
    Route::put('/{columnista}', [ColumnistaApiController::class, 'update']);
    Route::delete('/{columnista}', [ColumnistaApiController::class, 'destroy']);
});

// Rutas API para Editoriales
Route::prefix('editoriales')->group(function () {
    Route::get('/', [EditorialApiController::class, 'index']);
    Route::get('/{editorial}', [EditorialApiController::class, 'show']);
    Route::post('/', [EditorialApiController::class, 'store']);
    Route::put('/{editorial}', [EditorialApiController::class, 'update']);
    Route::delete('/{editorial}', [EditorialApiController::class, 'destroy']);
});

// Rutas API para Noticias
Route::prefix('noticias')->group(function () {
    Route::get('/', [NoticiaApiController::class, 'index']);
    Route::get('/{noticia}', [NoticiaApiController::class, 'show']);
    Route::post('/', [NoticiaApiController::class, 'store']);
    Route::put('/{noticia}', [NoticiaApiController::class, 'update']);
    Route::delete('/{noticia}', [NoticiaApiController::class, 'destroy']);
});

// Rutas API para Entrevistas
Route::prefix('entrevistas')->group(function () {
    Route::get('/', [EntrevistaApiController::class, 'index']);
    Route::get('/{entrevista}', [EntrevistaApiController::class, 'show']);
    Route::post('/', [EntrevistaApiController::class, 'store']);
    Route::put('/{entrevista}', [EntrevistaApiController::class, 'update']);
    Route::delete('/{entrevista}', [EntrevistaApiController::class, 'destroy']);
});

// Rutas API para Cable a Tierra
Route::prefix('cable-a-tierra')->group(function () {
    Route::get('/', [CableATierraApiController::class, 'index']);
    Route::get('/{cableATierra}', [CableATierraApiController::class, 'show']);
    Route::post('/', [CableATierraApiController::class, 'store']);
    Route::put('/{cableATierra}', [CableATierraApiController::class, 'update']);
    Route::delete('/{cableATierra}', [CableATierraApiController::class, 'destroy']);
});

// Rutas API para Atractivos
Route::prefix('atractivos')->group(function () {
    Route::get('/', [AtractivoApiController::class, 'index']);
    Route::get('/{atractivo}', [AtractivoApiController::class, 'show']);
    Route::post('/', [AtractivoApiController::class, 'store']);
    Route::put('/{atractivo}', [AtractivoApiController::class, 'update']);
    Route::delete('/{atractivo}', [AtractivoApiController::class, 'destroy']);
});

// Rutas para obtener listas simples (sin paginación)
Route::get('/revistas-list', function () {
    return \App\Models\Revista::orderBy('titulo')->get();
});

Route::get('/columnistas-list', function () {
    return \App\Models\Columnista::orderBy('nombre')->get();
});

// Dashboard Stats
Route::get('stats', function () {
    return response()->json([
        'articulos' => [
            'total' => \App\Models\Articulo::count(),
            'recientes' => \App\Models\Articulo::with(['columnista', 'revista'])
                ->latest()
                ->limit(5)
                ->get()
        ],
        'editoriales' => [
            'total' => \App\Models\Editorial::count(),
            'recientes' => \App\Models\Editorial::with(['revista'])
                ->latest()
                ->limit(5)
                ->get()
        ],
        'noticias' => [
            'total' => \App\Models\Noticia::count(),
            'recientes' => \App\Models\Noticia::latest('fecha_publicacion')
                ->limit(5)
                ->get()
        ],
        'entrevistas' => [
            'total' => \App\Models\Entrevista::count(),
            'recientes' => \App\Models\Entrevista::latest('fecha_publicacion')
                ->limit(5)
                ->get()
        ],
        'cable_a_tierra' => [
            'total' => \App\Models\CableATierra::count(),
            'recientes' => \App\Models\CableATierra::latest('fecha_publicacion')
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

// Ruta para obtener todas las categorías
Route::get('/categorias', function () {
    return \App\Models\Categoria::all();
});
