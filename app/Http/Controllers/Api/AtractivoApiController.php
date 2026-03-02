<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atractivo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class AtractivoApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Atractivo::with(['user', 'categoria']);

        // Buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // FILTRO CORREGIDO: Ahora usa la relación, no la columna 'category'
        if ($request->filled('category')) {
            $query->whereHas('categoria', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $perPage = $request->get('per_page', 15);
        return response()->json($query->latest()->paginate($perPage));
    }

       public function store2(Request $request)
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
            $slug = Str::slug($validated['title']);  
            Log::info($slug);

            // 3. Crear el registro
            $atractivo = new Atractivo;
            $atractivo->title = $validated['title'];
            $atractivo->description = $validated['description'];
            $atractivo->categoria_id = $validated['categoria_id'];
            $atractivo->ciudad = $validated['ciudad'];
            $atractivo->lng = $validated['lng'];
            $atractivo->lat = $validated['lat'];
            $atractivo->tags = $validated['tags'];
            $atractivo->image = $validated['image'];
            $atractivo->galeria = $validated['galeria'];
            $atractivo->show_galeria = $validated['show_galeria'];
            $atractivo->show_horario = $validated['show_horario'];
            $atractivo->show_enlace = $validated['show_enlace'];    
            $atractivo->autor = $validated['autor'];
            $atractivo->enlace = $validated['enlace'];
            $atractivo->horario = $validated['horario'];
            $atractivo->user_id = auth()->user()->id;
            $atractivo->slug = $slug; 
            $atractivo->save();

            return response()->json([
                'message' => 'Creado con éxito',
                'atractivo' => $atractivo
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error en store: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear'], 500);
        }
    }

    public function store(Request $request)
    {
        // 1. Pre-procesar tags si vienen de FormData (Vue)
        if ($request->has('tags') && is_string($request->tags)) {
            $request->merge(['tags' => json_decode($request->tags, true)]);
        }


        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'tags' => 'nullable|array',
            'ciudad' => 'nullable|string',
            'enlace' => 'nullable|string',
            'autor' => 'nullable|string',
            'lng' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'horario' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'galeria' => 'nullable|array|max:10',
            'galeria.*' => 'image|max:2048',
            'show_horario' => 'nullable|boolean',
            'show_enlace' => 'nullable|boolean',
            'show_galeria' => 'nullable|boolean',
        ]);

        // Forzar booleanos a 0 o 1
        $validated['show_horario'] = $request->input('show_horario', 0) ? 1 : 0;
        $validated['show_enlace'] = $request->input('show_enlace', 0) ? 1 : 0;
        $validated['show_galeria'] = $request->input('show_galeria', 0) ? 1 : 0;

        // 2. SOLUCIÓN AL ERROR 1452: Usar ID 1 en lugar de 99 para local
        $validated['user_id'] = auth()->id() ?? 1;


        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('atractivos', 'public');
        }

        // Guardar galería de imágenes
        $galeriaPaths = [];
        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                $galeriaPaths[] = $img->store('atractivos/galeria', 'public');
            }
            $validated['galeria'] = $galeriaPaths;
        }

            $slug = Str::slug($validated['title']);  

            
            $atractivo = new Atractivo;
            $atractivo->title = $validated['title'];
            $atractivo->description = $validated['description'];
            $atractivo->categoria_id = $validated['categoria_id'];
            $atractivo->ciudad = $validated['ciudad'];
            $atractivo->lng = $validated['lng'];
            $atractivo->lat = $validated['lat'];
            $atractivo->tags = $validated['tags'];
            $atractivo->image = $validated['image'];
            $atractivo->galeria = $galeriaPaths;
            $atractivo->show_galeria = $validated['show_galeria'];
            $atractivo->show_horario = $validated['show_horario'];
            $atractivo->show_enlace = $validated['show_enlace'];    
            $atractivo->autor = $validated['autor'];
            $atractivo->enlace = $validated['enlace'];
            $atractivo->horario = $validated['horario'];
            $atractivo->user_id = $validated['user_id'];
            $atractivo->slug = $slug; 
            $atractivo->save();

            return response()->json([
                'message' => 'Creado con éxito',
                'atractivo' => $atractivo
            ], 201);

    }

    public function update(Request $request, Atractivo $atractivo)
    {
        // 1. Pre-procesar tags para FormData
        if ($request->has('tags') && is_string($request->tags)) {
            $request->merge(['tags' => json_decode($request->tags, true)]);
        }



        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'categoria_id' => 'sometimes|exists:categorias,id',
            'tags' => 'nullable|array',
            'ciudad' => 'nullable|string',
            'enlace' => 'nullable|string',
            'autor' => 'nullable|string',
            'lng' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'horario' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'galeria' => 'nullable|array|max:10',
            'galeria.*' => 'image|max:2048',
            'show_horario' => 'nullable|boolean',
            'show_enlace' => 'nullable|boolean',
            'show_galeria' => 'nullable|boolean',
        ]);

        // Forzar booleanos a 0 o 1
        $validated['show_horario'] = $request->input('show_horario', 0) ? 1 : 0;
        $validated['show_enlace'] = $request->input('show_enlace', 0) ? 1 : 0;
        $validated['show_galeria'] = $request->input('show_galeria', 0) ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($atractivo->image) {
                Storage::disk('public')->delete($atractivo->image);
            }
            $validated['image'] = $request->file('image')->store('atractivos', 'public');
        }

        // Galería: eliminar imágenes marcadas para borrar
        $galeriaPaths = $atractivo->galeria ?? [];
        $galeriaToDelete = $request->input('galeria_delete', []);
        if (!is_array($galeriaToDelete)) {
            $galeriaToDelete = [];
        }
        // Eliminar físicamente y del array
        $galeriaPaths = array_values(array_filter($galeriaPaths, function($img) use ($galeriaToDelete) {
            if (in_array($img, $galeriaToDelete)) {
                Storage::disk('public')->delete($img);
                return false;
            }
            return true;
        }));

        // Agregar nuevas imágenes
        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                $galeriaPaths[] = $img->store('atractivos/galeria', 'public');
            }
        }
        // Limitar a 10
        $galeriaPaths = array_slice($galeriaPaths, 0, 10);
        $validated['galeria'] = $galeriaPaths;

        // ELIMINADO: La lógica de actualizar el campo 'category' string
        
        $atractivo->update($validated);
        return response()->json($atractivo->load('categoria'));
    }

    public function destroy(Atractivo $atractivo)
    {
        if ($atractivo->image) {
            Storage::disk('public')->delete($atractivo->image);
        }
        $atractivo->delete();
        return response()->json(null, 204);
    }
}