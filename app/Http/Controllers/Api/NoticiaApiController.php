<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NoticiaApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Noticia::query();

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('resumen', 'like', "%{$search}%")
                  ->orWhere('cuerpo', 'like', "%{$search}%");
            });
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        $noticias = $query->latest('fecha_publicacion')->paginate($perPage);

        return response()->json($noticias);
    }

    public function show(Noticia $noticia)
    {
        return response()->json($noticia);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'resumen' => 'nullable|string|max:500',
            'cuerpo' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'fecha_publicacion' => 'required|date'
        ]);

        // Generar slug
        $validated['slug'] = Str::slug($validated['titulo']);

        // Manejar imagen
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('noticias', 'public');
        }

        $noticia = Noticia::create($validated);

        return response()->json($noticia, 201);
    }

    public function update(Request $request, Noticia $noticia)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'resumen' => 'nullable|string|max:500',
            'cuerpo' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'fecha_publicacion' => 'required|date'
        ]);

        // Actualizar slug si cambió el título
        $validated['slug'] = Str::slug($validated['titulo']);

        // Manejar imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($noticia->imagen) {
                Storage::disk('public')->delete($noticia->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('noticias', 'public');
        }

        $noticia->update($validated);

        return response()->json($noticia);
    }

    public function destroy(Noticia $noticia)
    {
        // Eliminar imagen si existe
        if ($noticia->imagen) {
            Storage::disk('public')->delete($noticia->imagen);
        }

        $noticia->delete();

        return response()->json(['message' => 'Noticia eliminada exitosamente']);
    }
}
