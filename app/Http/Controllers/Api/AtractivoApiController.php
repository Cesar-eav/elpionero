<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atractivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AtractivoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Atractivo::with(['user']);

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
            'category' => 'required|string',
            'tags' => 'nullable|array',
            'ciudad' => 'nullable|string',
            'enlace' => 'nullable|url',
            'autor' => 'nullable|string',
            'lng' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'horario' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Manejar la imagen
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('atractivos', 'public');
        }

        $validated['user_id'] = auth()->id() ?? 99; // Usuario autenticado o por defecto 99

        $atractivo = Atractivo::create($validated);

        return response()->json($atractivo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Atractivo $atractivo)
    {
        return response()->json($atractivo->load(['user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Atractivo $atractivo)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'category' => 'string',
            'tags' => 'nullable|array',
            'ciudad' => 'nullable|string',
            'enlace' => 'nullable|url',
            'autor' => 'nullable|string',
            'lng' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'horario' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Manejar imagen nueva
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior
            if ($atractivo->image) {
                Storage::disk('public')->delete($atractivo->image);
            }
            $validated['image'] = $request->file('image')->store('atractivos', 'public');
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

