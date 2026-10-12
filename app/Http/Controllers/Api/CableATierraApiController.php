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
        $validated = $request->validate($this->rules());

        $validated['slug'] = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($validated['titulo']);
        $validated['publicado'] = $request->boolean('publicado', true);

        // Manejar imagen móvil
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('cable-a-tierra', 'public');
        }

        // Manejar imagen desktop
        if ($request->hasFile('imagen_desktop')) {
            $validated['imagen_desktop'] = $request->file('imagen_desktop')->store('cable-a-tierra', 'public');
        }

        $this->procesarAutores($request, $validated);

        $cableATierra = CableATierra::create($validated);

        return response()->json($cableATierra, 201);
    }

    public function update(Request $request, CableATierra $cableATierra)
    {
        $validated = $request->validate($this->rules());

        $validated['slug'] = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($validated['titulo']);
        $validated['publicado'] = $request->boolean('publicado', true);

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

        $this->procesarAutores($request, $validated, $cableATierra);

        $cableATierra->update($validated);

        return response()->json($cableATierra);
    }

    public function uploadImagen(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,jpg,png,gif|max:5120'
        ]);

        $path = $request->file('imagen')->store('cable-a-tierra/contenido', 'public');

        return response()->json([
            'url' => asset('storage/' . $path)
        ]);
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

        foreach ($cableATierra->autores ?? [] as $autor) {
            if (!empty($autor['foto'])) {
                Storage::disk('public')->delete($autor['foto']);
            }
        }

        $cableATierra->delete();

        return response()->json(['message' => 'Cable a Tierra eliminado exitosamente']);
    }

    private function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'autores' => 'nullable|array|max:10',
            'autores.*.nombre' => 'required|string|max:255',
            'autores.*.foto' => 'nullable|string|max:255',
            'autores.*.foto_file' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'resumen' => 'required|string',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'imagen_desktop' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'video_youtube' => 'nullable|string|max:500',
            'fecha_publicacion' => 'required|date',
            'publicado' => 'nullable|boolean',
        ];
    }

    /**
     * Arma `autores` [{nombre, foto}] desde el request, sube fotos nuevas y borra las que ya no se usan.
     * También rellena `autor` con los nombres unidos ("A, B y C") para búsqueda y listados.
     */
    private function procesarAutores(Request $request, array &$validated, ?CableATierra $actual = null): void
    {
        $fotosAnteriores = collect($actual?->autores ?? [])->pluck('foto')->filter()->values()->all();

        $autores = [];
        foreach ($request->input('autores', []) as $i => $autor) {
            $foto = null;
            if ($request->hasFile("autores.$i.foto_file")) {
                $foto = $request->file("autores.$i.foto_file")->store('cable-a-tierra/autores', 'public');
            } elseif (!empty($autor['foto']) && in_array($autor['foto'], $fotosAnteriores, true)) {
                // Solo se conserva una foto que ya pertenecía a este artículo
                $foto = $autor['foto'];
            }
            $autores[] = ['nombre' => trim($autor['nombre']), 'foto' => $foto];
        }

        $fotosEnUso = array_filter(array_column($autores, 'foto'));
        foreach (array_diff($fotosAnteriores, $fotosEnUso) as $huerfana) {
            Storage::disk('public')->delete($huerfana);
        }

        $nombres = array_column($autores, 'nombre');
        $validated['autores'] = $autores ?: null;
        $validated['autor'] = $nombres
            ? (count($nombres) > 1
                ? implode(', ', array_slice($nombres, 0, -1)) . ' y ' . end($nombres)
                : $nombres[0])
            : null;
    }
}
