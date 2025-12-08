<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entrevista;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EntrevistaApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Entrevista::query();

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('entrevistado', 'like', "%{$search}%")
                  ->orWhere('cargo', 'like', "%{$search}%")
                  ->orWhere('contenido', 'like', "%{$search}%");
            });
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        $entrevistas = $query->latest('fecha_publicacion')->paginate($perPage);

        return response()->json($entrevistas);
    }

    public function show(Entrevista $entrevista)
    {
        return response()->json($entrevista);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'entrevistado' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'imagen_desktop' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'fecha_publicacion' => 'required|date'
        ]);

        // Generar slug
        $validated['slug'] = Str::slug($validated['titulo']);

        // Manejar imagen móvil
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('entrevistas', 'public');
        }

        // Manejar imagen desktop
        if ($request->hasFile('imagen_desktop')) {
            $validated['imagen_desktop'] = $request->file('imagen_desktop')->store('entrevistas', 'public');
        }

        $entrevista = Entrevista::create($validated);

        return response()->json($entrevista, 201);
    }

    public function update(Request $request, Entrevista $entrevista)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'entrevistado' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
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
            if ($entrevista->imagen) {
                Storage::disk('public')->delete($entrevista->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('entrevistas', 'public');
        }

        // Manejar imagen desktop
        if ($request->hasFile('imagen_desktop')) {
            // Eliminar imagen desktop anterior si existe
            if ($entrevista->imagen_desktop) {
                Storage::disk('public')->delete($entrevista->imagen_desktop);
            }
            $validated['imagen_desktop'] = $request->file('imagen_desktop')->store('entrevistas', 'public');
        }

        $entrevista->update($validated);

        return response()->json($entrevista);
    }

    public function destroy(Entrevista $entrevista)
    {
        // Eliminar imagen móvil si existe
        if ($entrevista->imagen) {
            Storage::disk('public')->delete($entrevista->imagen);
        }

        // Eliminar imagen desktop si existe
        if ($entrevista->imagen_desktop) {
            Storage::disk('public')->delete($entrevista->imagen_desktop);
        }

        $entrevista->delete();

        return response()->json(['message' => 'Entrevista eliminada exitosamente']);
    }
}
