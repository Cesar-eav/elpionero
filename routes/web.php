<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RevistaController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ContactoController;




Route::get('/', function () {
    
    return view('welcome');
});


Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto.formulario');
Route::post('/contacto', [ContactoController::class, 'enviarFormulario'])->name('contacto.enviar');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::resource('articulos', ArticuloController::class);

Route::resource('revistas', RevistaController::class);
Route::get('/revistas/{revista}/pdf', [RevistaController::class, 'generarPDF'])->name('revistas.generar-pdf');
Route::get('/previsualizar-revista/{revista}/pdf', [RevistaController::class, 'previsualizarPDF'])->name('previsualizar.pdf');


