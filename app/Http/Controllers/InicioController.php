<?php

namespace App\Http\Controllers;
use App\Models\Articulo;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function inicio()
    {
        $columnas = Articulo::inRandomOrder()->with('revista')->get();
        return view('pdfs.inicio', compact('columnas'));
    }

    public function proximosNumeros()
    {
        $columnas = Articulo::inRandomOrder()->with('revista')->get();
        return view('inicio.proxnumero', compact('columnas'));
    }

}
