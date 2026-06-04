<?php

namespace App\Http\Controllers;
use App\Models\Articulo;
use App\Models\Noticia;
use App\Models\Editorial;
use App\Models\Entrevista;
use App\Models\CableATierra;
use App\Models\Revista;
use App\Models\Denuncia;


use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function inicio()
    {
        $columnas = Articulo::with(['revista', 'columnista'])->get();
        $noticias = Noticia::latest()->paginate(3);
        $ultimasDenuncias = Denuncia::where('estado', 'aprobada')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('approved_at')
            ->take(3)
            ->get();

        $ultimaRevista = Revista::latest()->first();
        $articulosDestacados = $ultimaRevista
            ? Articulo::with(['revista', 'columnista'])
                ->where('revista_id', $ultimaRevista->id)
                ->orderBy('id')
                ->get()
            : $columnas->sortByDesc('created_at');

        $total = $articulosDestacados->count();
        $indice = $total > 0 ? (int)(time() / 1800) % $total : 0;
        $destacada = $articulosDestacados->values()->get($indice)
            ?? $columnas->sortByDesc('created_at')->first();

        $ultimoCableATierra = CableATierra::latest('fecha_publicacion')->first();

        return view('inicio.inicio', compact([
            'columnas', 'noticias', 'destacada', 'ultimasDenuncias', 'ultimoCableATierra'
        ]));
    }

    public function proximosNumeros()
    {
        $columnas = Articulo::with(['revista', 'columnista'])->inRandomOrder()->get();
        return view('inicio.proxnumero', compact('columnas'));
    }

    public function nosotros()
    {
        return view('inicio.nosotros');
    }

    public function editoriales()
    {
        $editoriales = Editorial::with('revista')
            ->latest()
            ->paginate(12);

        return view('inicio.editoriales', compact('editoriales'));
    }

    public function showEditorial($slug)
    {
        $editorial = Editorial::with('revista')->where('slug', $slug)->firstOrFail();
        $otrasEditoriales = Editorial::with('revista')
            ->where('id', '!=', $editorial->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('inicio.editorial', compact('editorial', 'otrasEditoriales'));
    }

    public function entrevistas()
    {
        $entrevistas = Entrevista::latest()
            ->paginate(12);

        return view('inicio.entrevistas', compact('entrevistas'));
    }

    public function showEntrevista($slug)
    {
        $entrevista = Entrevista::where('slug', $slug)->firstOrFail();
        $otrasEntrevistas = Entrevista::where('id', '!=', $entrevista->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('inicio.entrevista', compact('entrevista', 'otrasEntrevistas'));
    }

    public function cableATierra()
    {
        $articulos = CableATierra::latest('fecha_publicacion')
            ->paginate(12);

        return view('inicio.cable-a-tierra', compact('articulos'));
    }

    public function showCableATierra($slug)
    {
        $articulo = CableATierra::where('slug', $slug)->firstOrFail();
        $otrosArticulos = CableATierra::where('id', '!=', $articulo->id)
            ->latest('fecha_publicacion')
            ->limit(4)
            ->get();

        return view('inicio.cable-a-tierra-detalle', compact('articulo', 'otrosArticulos'));
    }

    public function revistas()
    {
        $revistas = Revista::withCount('articulos')
            ->latest()
            ->paginate(12);

        return view('inicio.revistas', compact('revistas'));
    }

    public function showRevista($slug)
    {
        $revista = Revista::where('slug', $slug)->firstOrFail();
        $articulos = $revista->articulos()->with('columnista')->orderByDesc('created_at')->get();

        return view('inicio.revista-detalle', compact('revista', 'articulos'));
    }

}
