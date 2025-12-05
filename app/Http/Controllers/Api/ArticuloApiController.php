<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticuloApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Articulo::with(['revista', 'columnista']);

        // Filtros opcionales
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('contenido', 'like', "%{$search}%");
            });
        }

        if ($request->has('revista_id')) {
            $query->where('revista_id', $request->revista_id);
        }

        if ($request->has('columnista_id')) {
            $query->where('columnista_id', $request->columnista_id);
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        $articulos = $query->latest()->paginate($perPage);

        return response()->json($articulos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'revista_id' => 'required|exists:revistas,id',
            'titulo' => 'required|string|max:255|unique:articulos',
            'contenido' => 'required|string',
            'columnista_id' => 'required|exists:columnistas,id',
        ]);

        $articulo = new Articulo($validated);
        $articulo->slug = Str::slug($validated['titulo']);
        $articulo->save();

        // Cargar las relaciones para la respuesta
        $articulo->load(['revista', 'columnista']);

        return response()->json([
            'message' => 'Artículo creado exitosamente',
            'data' => $articulo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Articulo $articulo)
    {
        $articulo->load(['revista', 'columnista']);
        return response()->json($articulo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Articulo $articulo)
    {
        $validated = $request->validate([
            'revista_id' => 'required|exists:revistas,id',
            'titulo' => 'required|string|max:255|unique:articulos,titulo,' . $articulo->id,
            'contenido' => 'required|string',
            'columnista_id' => 'required|exists:columnistas,id'
        ]);

        $articulo->fill($validated);
        $articulo->slug = Str::slug($validated['titulo']);
        $articulo->save();

        // Cargar las relaciones para la respuesta
        $articulo->load(['revista', 'columnista']);

        return response()->json([
            'message' => 'Artículo actualizado exitosamente',
            'data' => $articulo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Articulo $articulo)
    {
        $articulo->delete();

        return response()->json([
            'message' => 'Artículo eliminado exitosamente'
        ]);
    }
}
