<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Revista;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RevistaApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Revista::withCount('articulos');

        // Filtros opcionales
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        $revistas = $query->latest()->paginate($perPage);

        return response()->json($revistas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255|unique:revistas',
            'fecha_publicacion' => 'required|date',
            'descripcion' => 'nullable|string',
        ]);

        $revista = new Revista($validated);
        $revista->slug = Str::slug($validated['titulo']);
        $revista->save();

        $revista->loadCount('articulos');

        return response()->json([
            'message' => 'Revista creada exitosamente',
            'data' => $revista
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Revista $revista)
    {
        $revista->loadCount('articulos');
        return response()->json($revista);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Revista $revista)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255|unique:revistas,titulo,' . $revista->id,
            'fecha_publicacion' => 'required|date',
            'descripcion' => 'nullable|string',
        ]);

        $revista->fill($validated);
        $revista->slug = Str::slug($validated['titulo']);
        $revista->save();

        $revista->loadCount('articulos');

        return response()->json([
            'message' => 'Revista actualizada exitosamente',
            'data' => $revista
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Revista $revista)
    {
        $revista->delete();

        return response()->json([
            'message' => 'Revista eliminada exitosamente'
        ]);
    }
}
