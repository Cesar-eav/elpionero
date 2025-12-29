<?php

namespace App\Http\Controllers;

use App\Models\Atractivo;
use Illuminate\Http\Request;

class AtractivoController extends Controller
{
    /**
     * La Brújula: Listado con filtros de categoría y búsqueda libre
     */
    public function index(Request $request)
    {
        $query = Atractivo::query();

        // Filtro por Categoría
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Buscador (Título o Descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Paginación manteniendo los parámetros de búsqueda en la URL
        $atractivos = $query->latest()->paginate(12)->withQueryString();

        // Obtenemos solo las categorías existentes para el select
        $categorias = Atractivo::distinct()->pluck('category');

        // Si es una petición AJAX, devolver solo el contenedor
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('labrujula.partials.atractivos-container', compact('atractivos'))->render();
        }

        return view('labrujula.index', compact('atractivos', 'categorias'));
    }

    public function show(Atractivo $atractivo)
    {
        return view('labrujula.show', compact('atractivo'));
    }
}