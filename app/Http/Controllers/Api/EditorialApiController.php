<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Editorial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorialApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Editorial::with(['revista']);

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

        // Paginación
        $perPage = $request->get('per_page', 15);
        $editoriales = $query->latest()->paginate($perPage);

        return response()->json($editoriales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'revista_id' => 'required|exists:revistas,id',
            'titulo' => 'required|string|max:255|unique:editorials',
            'contenido' => 'required|string'
        ]);

        $editorial = new Editorial($validated);
        $editorial->slug = Str::slug($validated['titulo']);
        $editorial->save();

        // Cargar las relaciones para la respuesta
        $editorial->load(['revista']);

        return response()->json([
            'message' => 'Editorial creada exitosamente',
            'data' => $editorial
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Editorial $editorial)
    {
        $editorial->load(['revista']);
        return response()->json($editorial);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Editorial $editorial)
    {
        $validated = $request->validate([
            'revista_id' => 'required|exists:revistas,id',
            'titulo' => 'required|string|max:255|unique:editorials,titulo,' . $editorial->id,
            'contenido' => 'required|string'
        ]);

        $editorial->fill($validated);
        $editorial->slug = Str::slug($validated['titulo']);
        $editorial->save();

        // Cargar las relaciones para la respuesta
        $editorial->load(['revista']);

        return response()->json([
            'message' => 'Editorial actualizada exitosamente',
            'data' => $editorial
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Editorial $editorial)
    {
        $editorial->delete();

        return response()->json([
            'message' => 'Editorial eliminada exitosamente'
        ]);
    }
}
