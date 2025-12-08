<?php

namespace App\Http\Controllers;
use App\Models\Articulo;
use App\Models\Noticia;
use App\Models\Editorial;
use App\Models\Entrevista;
use App\Models\Revista;


use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function inicio()
    {
        $columnas = Articulo::with(['revista', 'columnista'])->inRandomOrder()->get();
        $noticias = Noticia::latest()->paginate(5);

        return view('inicio.inicio', compact([
            'columnas', 'noticias'
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

    public function revistas()
    {
        $revistas = Revista::withCount('articulos')
            ->latest()
            ->paginate(12);

        return view('inicio.revistas', compact('revistas'));
    }

}
