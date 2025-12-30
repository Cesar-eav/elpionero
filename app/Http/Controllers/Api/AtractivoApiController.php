<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atractivo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AtractivoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Atractivo::with(['user', 'categoria']);

        // Filtros opcionales
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ciudad', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('ciudad')) {
            $query->where('ciudad', $request->ciudad);
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        $atractivos = $query->latest()->paginate($perPage);

        return response()->json($atractivos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'tags' => 'nullable|array',
            'ciudad' => 'nullable|string',
            'enlace' => 'nullable|url',
            'autor' => 'nullable|string',
            'lng' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'horario' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('atractivos', 'public');
        }

        $validated['user_id'] = auth()->id() ?? 99;

        // Rellenar el campo `category` (slug) usando la categoría seleccionada
        if (!empty($validated['categoria_id'])) {
            $categoria = Categoria::find($validated['categoria_id']);
            $validated['category'] = $categoria ? $categoria->slug : '';
        }

        $atractivo = Atractivo::create($validated);

        return response()->json($atractivo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Atractivo $atractivo)
    {
        return response()->json($atractivo->load(['user', 'categoria']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Atractivo $atractivo)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'categoria_id' => 'exists:categorias,id',
            'tags' => 'nullable|array',
            'ciudad' => 'nullable|string',
            'enlace' => 'nullable|url',
            'autor' => 'nullable|string',
            'lng' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'horario' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($atractivo->image) {
                Storage::disk('public')->delete($atractivo->image);
            }
            $validated['image'] = $request->file('image')->store('atractivos', 'public');
        }

        // Si se actualiza la categoría, también actualizar el campo `category`
        if (isset($validated['categoria_id'])) {
            $categoria = Categoria::find($validated['categoria_id']);
            $validated['category'] = $categoria ? $categoria->slug : $atractivo->category;
        }

        $atractivo->update($validated);

        return response()->json($atractivo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Atractivo $atractivo)
    {
        // Eliminar imagen
        if ($atractivo->image) {
            Storage::disk('public')->delete($atractivo->image);
        }

        $atractivo->delete();

        return response()->json(null, 204);
    }
}

