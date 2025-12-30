<?php

namespace App\Http\Controllers;

use App\Models\Atractivo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AtractivoController extends Controller
{
    /**
     * La Brújula: Listado con filtros (Público)
     */
    public function index(Request $request)
    {
        try {
            $query = Atractivo::query();

            if ($request->filled('category')) {
                $categorySlug = $request->category;
                $query->whereHas('categoria', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $atractivos = $query->with('categoria')->latest()->paginate(12)->withQueryString();
            $categorias = Categoria::all();

            if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return view('labrujula.partials.atractivos-container', compact('atractivos'))->render();
            }

            return view('labrujula.index', compact('atractivos', 'categorias'));
        } catch (\Exception $e) {
            Log::error('Error en index: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Ver detalle de un atractivo (Público)
     */
    public function show(Atractivo $atractivo)
    {
        return view('labrujula.show', compact('atractivo'));
    }

    /**
     * API: Guardar nuevo (Admin)
     */
    public function store(Request $request)
    {
        try {
            // Validar solo los campos obligatorios
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'categoria_id' => 'required|exists:categorias,id',
                'ciudad' => 'required|string',
                'lng' => 'required|numeric',
                'lat' => 'required|numeric',
                'tags' => 'required|array',
                'image' => 'required|image|max:2048',
                // Opcionales:
                'autor' => 'nullable|string',
                'enlace' => 'nullable|string',
                'horario' => 'nullable|string',
                'show_horario' => 'nullable|boolean',
                'show_enlace' => 'nullable|boolean',
                'show_galeria' => 'nullable|boolean',
                'galeria' => 'nullable|array|max:10',
                'galeria.*' => 'image|max:2048',
            ]);

            // Forzar booleanos a 0 o 1
            $validated['show_horario'] = $request->input('show_horario', 0) ? 1 : 0;
            $validated['show_enlace'] = $request->input('show_enlace', 0) ? 1 : 0;
            $validated['show_galeria'] = $request->input('show_galeria', 0) ? 1 : 0;

            // 1. Procesar Imagen Principal
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('atractivos', 'public');
            }

            // 2. Procesar Galería (si existe)
            $galeria = [];
            if ($request->hasFile('galeria')) {
                foreach ($request->file('galeria') as $file) {
                    $galeria[] = $file->store('galeria', 'public');
                }
            }
            $validated['galeria'] = $galeria;

            // 3. Crear el registro
            $atractivo = Atractivo::create($validated);

            return response()->json([
                'message' => 'Creado con éxito',
                'atractivo' => $atractivo
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error en store: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear'], 500);
        }
    }

    /**
     * API: Actualizar (Admin)
     */
    public function update(Request $request, $id)
    {
        try {
            $atractivo = Atractivo::findOrFail($id);
            $data = $request->all();

            // Forzar booleanos a 0 o 1
            $data['show_horario'] = $request->input('show_horario', 0) ? 1 : 0;
            $data['show_enlace'] = $request->input('show_enlace', 0) ? 1 : 0;
            $data['show_galeria'] = $request->input('show_galeria', 0) ? 1 : 0;

            // 1. Imagen Principal: Si sube una nueva, borramos la vieja
            if ($request->hasFile('image')) {
                if ($atractivo->image) {
                    Storage::disk('public')->delete($atractivo->image);
                }
                $data['image'] = $request->file('image')->store('atractivos', 'public');
            }

            // 2. Galería: Manejar borrados específicos
            $galeriaActual = $atractivo->galeria ?? [];
            if ($request->has('galeria_delete')) {
                foreach ($request->galeria_delete as $pathABorrar) {
                    Storage::disk('public')->delete($pathABorrar);
                    $galeriaActual = array_values(array_diff($galeriaActual, [$pathABorrar]));
                }
            }
            if ($request->hasFile('galeria')) {
                foreach ($request->file('galeria') as $file) {
                    $galeriaActual[] = $file->store('galeria', 'public');
                }
            }
            $data['galeria'] = $galeriaActual;

            // 4. Actualizar registro
            $atractivo->update($data);

            return response()->json([
                'message' => 'Actualizado con éxito',
                'atractivo' => $atractivo
            ]);

        } catch (\Exception $e) {
            Log::error('Error en update: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Eliminar atractivo y sus archivos (Admin)
     */
    public function destroy($id)
    {
        try {
            $atractivo = Atractivo::findOrFail($id);
            
            // Borrar imagen principal
            if ($atractivo->image) Storage::disk('public')->delete($atractivo->image);
            
            // Borrar toda la galería
            if ($atractivo->galeria) {
                foreach ($atractivo->galeria as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            
            $atractivo->delete();
            return response()->json(['message' => 'Eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar'], 500);
        }
    }
}