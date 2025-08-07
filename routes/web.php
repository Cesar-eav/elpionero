<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RevistaController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\NewsletterController;




Route::get('/', function () {
    
    return redirect('/columnas');
});

Route::get('/nosotros', function () {
    
    return view('welcome');
});
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');


Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto.formulario');
Route::post('/contacto', [ContactoController::class, 'enviarFormulario'])->name('contacto.enviar');

Route::get('/contactos2', [ContactoController::class, 'listarContactos'])->name('contactos.listar');

Route::resource('articulos', ArticuloController::class);
Route::get('articulo/{id}',[ArticuloController::class, 'showArticulo'])->name('inicio.articulo');
Route::get('/columnas',[ArticuloController::class, 'showColumnas'])->name('inicio.columnas');



Route::resource('revistas', RevistaController::class);
Route::get('/revistas/{revista}/pdf', [RevistaController::class, 'generarPDF'])->name('revistas.generar-pdf');
Route::get('/previsualizar-revista/pdf', [RevistaController::class, 'previsualizarPDF'])->name('previsualizar.pdf');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/admin/suscriptores', [NewsletterController::class, 'index'])->name('newsletter.index');
    
});

require __DIR__.'/auth.php';

