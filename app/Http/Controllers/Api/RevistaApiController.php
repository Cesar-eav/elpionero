<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Revista;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RevistaApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Revista::withCount('articulos');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $revistas = $query->latest()->paginate($perPage);

        return response()->json($revistas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'             => 'required|string|max:255|unique:revistas',
            'fecha_publicacion'  => 'required|date',
            'descripcion'        => 'nullable|string',
            'portada'            => 'nullable|image|max:4096',
        ]);

        $revista = new Revista($validated);
        $revista->slug = Str::slug($validated['titulo']);

        if ($request->hasFile('portada')) {
            $revista->portada = $request->file('portada')->store('portadas', 'public');
        }

        $revista->save();
        $revista->loadCount('articulos');

        return response()->json(['message' => 'Revista creada exitosamente', 'data' => $revista], 201);
    }

    public function show(Revista $revista)
    {
        $revista->loadCount('articulos');
        return response()->json($revista);
    }

    public function update(Request $request, Revista $revista)
    {
        $validated = $request->validate([
            'titulo'             => 'required|string|max:255|unique:revistas,titulo,' . $revista->id,
            'fecha_publicacion'  => 'required|date',
            'descripcion'        => 'nullable|string',
            'portada'            => 'nullable|image|max:4096',
        ]);

        $revista->fill($validated);
        $revista->slug = Str::slug($validated['titulo']);

        if ($request->hasFile('portada')) {
            if ($revista->portada) {
                Storage::disk('public')->delete($revista->portada);
            }
            $revista->portada = $request->file('portada')->store('portadas', 'public');
        }

        $revista->save();
        $revista->loadCount('articulos');

        return response()->json(['message' => 'Revista actualizada exitosamente', 'data' => $revista]);
    }

    public function destroy(Revista $revista)
    {
        if ($revista->portada) {
            Storage::disk('public')->delete($revista->portada);
        }
        $revista->delete();

        return response()->json(['message' => 'Revista eliminada exitosamente']);
    }
}
