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

use App\Http\Controllers\AtractivoController;
use App\Http\Controllers\DenunciaController;






Route::get('/',[InicioController::class, 'inicio'])->name('inicio');
Route::get('/proximos-numeros',[InicioController::class, 'proximosNumeros'])->name('proximos-numeros');

Route::get('/apoyanos', function () {
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

// Sección especial Paseo
Route::get('/cuidemoselpaseo', function () {
    return view('paseo.index');
})->name('paseo.index');

// Galería del Paseo: los textos están en resources/data/paseo-galeria.php y la fecha sale del nombre de cada foto
Route::get('/cuidemoselpaseo/galeria', function () {
    $salas = require resource_path('data/paseo-galeria.php');
    $web = 'cuidemos_el_paseo/galeria/web/';

    $todas = collect($salas)->flatMap(function ($sala) use ($web) {
        return array_map(function ($foto) use ($sala, $web) {
            $nombre = pathinfo($foto['f'], PATHINFO_FILENAME);
            preg_match('/IMG_(\d{8})_(\d{6})/', $nombre, $m);
            [$w, $h] = @getimagesize(public_path($web . $nombre . '-800.webp')) ?: [800, 600];

            return $foto + [
                'fecha' => \Carbon\Carbon::createFromFormat('YmdHis', $m[1] . $m[2])->locale('es'),
                'src' => asset($web . $nombre . '-800.webp'),
                'src2' => asset($web . $nombre . '-1800.webp'),
                'w' => $w,
                'h' => $h,
                'sn' => $sala['num'],
                'sala' => $sala['titulo'],
            ];
        }, $sala['fotos']);
    })
        // Numeración global en orden cronológico, la que recorre el visor
        ->sortBy('fecha')->values()
        ->map(fn ($foto, $i) => $foto + ['i' => $i])
        ->keyBy('f');

    foreach ($salas as $s => $sala) {
        $salas[$s]['fotos'] = array_map(fn ($foto) => $todas[$foto['f']], $sala['fotos']);
        // Fecha de apertura de la etapa: la de su primera foto
        $salas[$s]['apertura'] = collect($salas[$s]['fotos'])->min('fecha');
        if (isset($sala['diptico'])) {
            $salas[$s]['diptico']['a'] = $todas[$sala['diptico']['a']];
            $salas[$s]['diptico']['b'] = $todas[$sala['diptico']['b']];
        }
    }
    $todas = $todas->values();

    return view('paseo.galeria', ['salas' => $salas, 'todas' => $todas]);
})->name('paseo.galeria');

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

// Rutas públicas para Atractivos (La Brújula)
Route::get('/labrujula', [AtractivoController::class, 'index'])->name('atractivos.index');
Route::get('/atractivos/{atractivo}', [AtractivoController::class, 'show'])->name('atractivos.show');
Route::get('/atractivos/categoria/{categoria}', [AtractivoController::class, 'filtrarPorCategoria'])->name('atractivos.categoria');
Route::get('/atractivos/ciudad/{ciudad}', [AtractivoController::class, 'filtrarPorCiudad'])->name('atractivos.ciudad');
Route::get('/panoramas', [AtractivoController::class, 'panoramas'])->name('atractivos.panoramas');

Route::get('/juegos/portle', function () {
    return view('juegos.wordle');
})->name('juegos.portle');

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
Route::get('/cable-a-tierra', [InicioController::class, 'cableATierra'])->name('cable-a-tierra.index');
Route::get('/cable-a-tierra/{slug}', [InicioController::class, 'showCableATierra'])->name('cable-a-tierra.show');
Route::get('/revistas-lista', [InicioController::class, 'revistas'])->name('revistas.lista');
Route::get('/revista/{slug}', [InicioController::class, 'showRevista'])->name('revista.show');
    

Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Rutas de Denuncia
Route::get('/denuncias', [DenunciaController::class, 'index'])->name('denuncia.index');
Route::get('/denuncias/{denuncia}', [DenunciaController::class, 'show'])->name('denuncia.show');
Route::get('/denuncia', [DenunciaController::class, 'formulario'])->name('denuncia.formulario');
Route::post('/denuncia', [DenunciaController::class, 'store'])->name('denuncia.store');
Route::get('/denuncia/gracias', [DenunciaController::class, 'gracias'])->name('denuncia.gracias');


Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto.formulario');
Route::post('/contacto', [ContactoController::class, 'enviarFormulario'])->name('contacto.enviar');

Route::get('/suscriptores', [ContactoController::class, 'listarContactos'])->name('contactos.listar');

Route::get('articulo/{slug}',[ArticuloController::class, 'showArticulo'])->name('inicio.articulo');
Route::get('/columnas',[ArticuloController::class, 'showColumnas'])->name('inicio.columnas');
Route::get('/columnista/{id}',[ArticuloController::class, 'showColumnista'])->name('columnista.show');

Route::get('/revistas/{revista}/pdf', [RevistaController::class, 'generarPDF'])->name('revistas.generar-pdf');
Route::get('/previsualizar-revista/pdf', [RevistaController::class, 'previsualizarPDF'])->name('previsualizar.pdf');
Route::get('/gps-buscar', [AtractivoController::class, 'gpsBuscar'])->name('gps-buscar');

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
    Route::resource('admin/noticias', NoticiaController::class)->names('admin.noticias');

    // API para estadísticas de PDFs
    Route::get('/api/pdf-stats', [PdfTrackingController::class, 'stats'])->name('pdf.stats');

    // Ruta principal del Dashboard Vue con Router (catch-all para Vue Router)
    Route::get('/dashboard-vue/{any?}', function () {
        return view('dashboard-vue');
    })->where('any', '.*')->name('dashboard.vue');
});

require __DIR__.'/auth.php';

