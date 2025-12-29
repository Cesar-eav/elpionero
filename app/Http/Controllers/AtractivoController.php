<?php

namespace App\Http\Controllers;

use App\Models\Atractivo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AtractivoController extends Controller
{
    /**
     * La Brújula: Listado con filtros de categoría y búsqueda libre
     */
    public function index(Request $request)
    {
        try {
            $query = Atractivo::query();

            // Filtro por Categoría
            if ($request->filled('category')) {
                $categorySlug = $request->category;
                Log::info('Filtrando por categoría: ' . $categorySlug);
                $query->whereHas('categoria', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
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
            $atractivos = $query->with('categoria')->latest()->paginate(12)->withQueryString();

            // Obtenemos todas las categorías de la tabla
            $categorias = Categoria::all();

            // Si es una petición AJAX, devolver solo el contenedor
            if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return view('labrujula.partials.atractivos-container', compact('atractivos'))->render();
            }

            return view('labrujula.index', compact('atractivos', 'categorias'));
        } catch (\Exception $e) {
            Log::error('Error en AtractivoController::index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    public function show(Atractivo $atractivo)
    {
        return view('labrujula.show', compact('atractivo'));
    }
}