<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RevistaController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ColumnistaController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TriviaReportController;
use App\Http\Controllers\PdfTrackingController;






Route::get('/',[InicioController::class, 'inicio'])->name('inicio');
Route::get('/proximos-numeros',[InicioController::class, 'proximosNumeros'])->name('proximos-numeros');

Route::get('/aportes', function () {
    return view('aportes');
})->name('aportes');

Route::get('/gracias-aporte', function () {
    return view('gracias-aporte');
})->name('gracias.aporte');

Route::get('/nosotros', [InicioController::class, 'nosotros'])->name('nosotros');

Route::get('/buscar', [BusquedaController::class, 'buscar'])->name('buscar');

Route::get('/limpiador-correos', function () {
    return view('limpiador-correos');
})->name('limpiador.correos');

// Rutas de Juegos
Route::get('/juegos', function () {
    return view('juegos.index');
})->name('juegos.index');

Route::get('/juegos/sopa-letras', function () {
    return view('juegos.crucigrama');
})->name('juegos.sopa-letras');

Route::get('/juegos/trivia', function () {
    return view('juegos.trivia');
})->name('juegos.trivia');

Route::post('/trivia/reportar', [TriviaReportController::class, 'store'])->name('trivia.reportar');

// Rutas de tracking de PDFs
Route::get('/pdf/{pdfName}/{action}', [PdfTrackingController::class, 'track'])->name('pdf.track');

Route::get('/sitemap-noticias.xml', [SitemapController::class, 'noticias'])->name('sitemap.noticias');

Route::get('/noticias',[NoticiaController::class, 'noticiasIndex'])->name('noticias.index');
Route::get('/noticia/{slug}', [NoticiaController::class, 'showBySlug'])->name('noticia.show');
Route::get('/editoriales', [InicioController::class, 'editoriales'])->name('editoriales.index');
Route::get('/editorial/{slug}', [InicioController::class, 'showEditorial'])->name('editorial.show');
Route::get('/entrevistas', [InicioController::class, 'entrevistas'])->name('entrevistas.index');
Route::get('/entrevista/{slug}', [InicioController::class, 'showEntrevista'])->name('entrevista.show');
Route::get('/revistas-lista', [InicioController::class, 'revistas'])->name('revistas.lista');
    

Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');


Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto.formulario');
Route::post('/contacto', [ContactoController::class, 'enviarFormulario'])->name('contacto.enviar');

Route::get('/contactos2', [ContactoController::class, 'listarContactos'])->name('contactos.listar');

Route::get('articulo/{slug}',[ArticuloController::class, 'showArticulo'])->name('inicio.articulo');
Route::get('/columnas',[ArticuloController::class, 'showColumnas'])->name('inicio.columnas');

Route::get('/revistas/{revista}/pdf', [RevistaController::class, 'generarPDF'])->name('revistas.generar-pdf');
Route::get('/previsualizar-revista/pdf', [RevistaController::class, 'previsualizarPDF'])->name('previsualizar.pdf');



// Dashboard Blade deshabilitado - se usa dashboard Vue
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/admin/suscriptores', [NewsletterController::class, 'index'])->name('newsletter.index');
    Route::resource('articulos', ArticuloController::class);
    Route::resource('revistas', RevistaController::class);
    Route::resource('columnistas', ColumnistaController::class);
    Route::resource('admin/noticias', NoticiaController::class);

    // API para estadísticas de PDFs
    Route::get('/api/pdf-stats', [PdfTrackingController::class, 'stats'])->name('pdf.stats');

    // Ruta principal del Dashboard Vue con Router (catch-all para Vue Router)
    Route::get('/dashboard-vue/{any?}', function () {
        return view('dashboard-vue');
    })->where('any', '.*')->name('dashboard.vue');
});

require __DIR__.'/auth.php';

