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
            $data = $request->all();

            // Imagen Principal
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('atractivos', 'public');
            }

            // Galería Nueva
            $galeria = [];
            if ($request->hasFile('galeria')) {
                foreach ($request->file('galeria') as $file) {
                    $galeria[] = $file->store('galeria', 'public');
                }
            }
            $data['galeria'] = $galeria;

            $atractivo = Atractivo::create($data);

            return response()->json(['message' => 'Creado', 'atractivo' => $atractivo], 201);
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

            // 1. Imagen Principal
            if ($request->hasFile('image')) {
                if ($atractivo->image) Storage::disk('public')->delete($atractivo->image);
                $data['image'] = $request->file('image')->store('atractivos', 'public');
            }

            // 2. Manejar Galería
            $galeriaActual = $atractivo->galeria ?? [];
            
            // Borrar fotos seleccionadas en Vue
            if ($request->has('galeria_delete')) {
                foreach ($request->galeria_delete as $path) {
                    Storage::disk('public')->delete($path);
                    $galeriaActual = array_values(array_diff($galeriaActual, [$path]));
                }
            }

            // Agregar fotos nuevas
            if ($request->hasFile('galeria')) {
                foreach ($request->file('galeria') as $file) {
                    $galeriaActual[] = $file->store('galeria', 'public');
                }
            }
            $data['galeria'] = $galeriaActual;

            $atractivo->update($data);

            return response()->json(['message' => 'Actualizado', 'atractivo' => $atractivo]);
        } catch (\Exception $e) {
            Log::error('Error en update: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
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