<?php

namespace App\Http\Controllers;

use App\Models\Atractivo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AtractivoController extends Controller
{
       public function gpsBuscar (Request $request){

        $lng = $request->get('lng');
        $lat = $request->get('lat');
        $rango = $request->input('rango');
        
        $radioTierra = 6371; // En kilómetros (usa 6371000 para metros)    
        return Atractivo::whereRaw(
                "ST_Distance_Sphere(POINT(lng, lat), POINT(?, ?)) < ?", 
                [$lng, $lat, $rango]
            )->get();
    }




    /**
     * La Brújula: Listado con filtros (Público)
     */
  public function index(Request $request)
{
    try {
        $query = Atractivo::query();

        // --- Filtros existentes ---
        if ($request->filled('category')) {
            $query->whereHas('categoria', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // --- NUEVO: Filtro por GPS ---
        if ($request->filled(['lat', 'lng', 'rango'])) {
            $lat = $request->lat;
            $lng = $request->lng;
            $rango = $request->rango;

            // 1. Calculamos la distancia y la filtramos
            $query->whereRaw(
                "ST_Distance_Sphere(POINT(lng, lat), POINT(?, ?)) <= ?", 
                [$lng, $lat, $rango]
            );

            // 2. Agregamos la distancia como un campo para mostrarla en la vista
            $query->selectRaw("*, ST_Distance_Sphere(POINT(lng, lat), POINT(?, ?)) as distancia", [$lng, $lat]);
            
            // 3. Ordenamos por el más cercano (en lugar de Random)
            $query->orderBy('distancia', 'asc');
        } else {
            $query->inRandomOrder();
        }

        $atractivos = $query->with('categoria')->paginate(40)->withQueryString();
        $categorias = Categoria::all();

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('labrujula.partials.atractivos-container', compact('atractivos'))->render();
        }

        return view('labrujula.index', compact('atractivos', 'categorias'));
        
    } catch (\Exception $e) {
        Log::error('Error en index: ' . $e->getMessage());
        return back()->with('error', 'Ocurrió un error al buscar.');
    }
}

    /**
     * Ver detalle de un atractivo (Público)
     */
    public function show($slug)
    {
        $atractivo = Atractivo::where('slug', $slug)->first();
        return view('labrujula.show', compact('atractivo'));
    }

    public function panoramas(){
        return view('labrujula.panoramas');
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
            $slug = Str::slug($validated['title']);  
            return $slug;

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