<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CableATierra;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CableATierraApiController extends Controller
{
    public function index(Request $request)
    {
        $query = CableATierra::query();

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%")
                  ->orWhere('resumen', 'like', "%{$search}%")
                  ->orWhere('contenido', 'like', "%{$search}%");
            });
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        $cableATierra = $query->latest('fecha_publicacion')->paginate($perPage);

        return response()->json($cableATierra);
    }

    public function show(CableATierra $cableATierra)
    {
        return response()->json($cableATierra);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'nullable|string|max:255',
            'resumen' => 'required|string',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'imagen_desktop' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'fecha_publicacion' => 'required|date'
        ]);

        // Generar slug
        $validated['slug'] = Str::slug($validated['titulo']);

        // Manejar imagen móvil
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('cable-a-tierra', 'public');
        }

        // Manejar imagen desktop
        if ($request->hasFile('imagen_desktop')) {
            $validated['imagen_desktop'] = $request->file('imagen_desktop')->store('cable-a-tierra', 'public');
        }

        $cableATierra = CableATierra::create($validated);

        return response()->json($cableATierra, 201);
    }

    public function update(Request $request, CableATierra $cableATierra)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'nullable|string|max:255',
            'resumen' => 'required|string',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'imagen_desktop' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'fecha_publicacion' => 'required|date'
        ]);

        // Actualizar slug si cambió el título
        $validated['slug'] = Str::slug($validated['titulo']);

        // Manejar imagen móvil
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($cableATierra->imagen) {
                Storage::disk('public')->delete($cableATierra->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('cable-a-tierra', 'public');
        }

        // Manejar imagen desktop
        if ($request->hasFile('imagen_desktop')) {
            // Eliminar imagen desktop anterior si existe
            if ($cableATierra->imagen_desktop) {
                Storage::disk('public')->delete($cableATierra->imagen_desktop);
            }
            $validated['imagen_desktop'] = $request->file('imagen_desktop')->store('cable-a-tierra', 'public');
        }

        $cableATierra->update($validated);

        return response()->json($cableATierra);
    }

    public function destroy(CableATierra $cableATierra)
    {
        // Eliminar imagen móvil si existe
        if ($cableATierra->imagen) {
            Storage::disk('public')->delete($cableATierra->imagen);
        }

        // Eliminar imagen desktop si existe
        if ($cableATierra->imagen_desktop) {
            Storage::disk('public')->delete($cableATierra->imagen_desktop);
        }

        $cableATierra->delete();

        return response()->json(['message' => 'Cable a Tierra eliminado exitosamente']);
    }
}
