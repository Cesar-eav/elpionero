<?php

namespace App\Http\Controllers;
use App\Models\Articulo;
use App\Models\Noticia;


use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function inicio()
    {
        $columnas = Articulo::inRandomOrder()->with('revista')->get();
        $noticias = Noticia::latest()->paginate(5);

        return view('pdfs.inicio', compact([
            'columnas', 'noticias'
        ]));
    }

    public function proximosNumeros()
    {
        $columnas = Articulo::inRandomOrder()->with('revista')->get();
        return view('inicio.proxnumero', compact('columnas'));
    }

}
