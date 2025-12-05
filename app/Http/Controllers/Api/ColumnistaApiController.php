<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Columnista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ColumnistaApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Columnista::query();

        // Filtros opcionales
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        $columnistas = $query->latest()->paginate($perPage);

        return response()->json($columnistas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|max:2048',
            'foto_existente' => 'nullable|string',
            'bio' => 'nullable|string',
            'participa_proximo_numero' => 'boolean',
        ]);

        $columnista = new Columnista($validated);

        // Si se envía una imagen existente, usar esa ruta
        if ($request->has('foto_existente') && $request->foto_existente) {
            $columnista->foto = $request->foto_existente;
        }
        // Si se sube un archivo nuevo, guardarlo
        elseif ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/columnistas');
            $columnista->foto = str_replace('public/', '', $path);
        }

        $columnista->save();

        return response()->json([
            'message' => 'Columnista creado exitosamente',
            'data' => $columnista
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Columnista $columnista)
    {
        return response()->json($columnista);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Columnista $columnista)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|max:2048',
            'foto_existente' => 'nullable|string',
            'bio' => 'nullable|string',
            'participa_proximo_numero' => 'boolean',
        ]);

        $columnista->fill($validated);

        // Si se envía una imagen existente, usar esa ruta
        if ($request->has('foto_existente') && $request->foto_existente) {
            $columnista->foto = $request->foto_existente;
        }
        // Si se sube un archivo nuevo, guardarlo
        elseif ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($columnista->foto) {
                Storage::delete('public/' . $columnista->foto);
            }
            $path = $request->file('foto')->store('public/columnistas');
            $columnista->foto = str_replace('public/', '', $path);
        }

        $columnista->save();

        return response()->json([
            'message' => 'Columnista actualizado exitosamente',
            'data' => $columnista
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Columnista $columnista)
    {
        // Eliminar la foto si existe
        if ($columnista->foto) {
            Storage::delete(str_replace('storage/', 'public/', $columnista->foto));
        }

        $columnista->delete();

        return response()->json([
            'message' => 'Columnista eliminado exitosamente'
        ]);
    }

    /**
     * List available images in columnistas directories
     */
    public function getAvailableImages()
    {
        $images = [];

        // Buscar en columnistas/
        $columnistasImages = Storage::files('public/columnistas');
        foreach ($columnistasImages as $image) {
            if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $image)) {
                $images[] = str_replace('public/', '', $image);
            }
        }

        // Buscar en columnistas/imagenes_autores/
        $imagenesAutoresImages = Storage::files('public/columnistas/imagenes_autores');
        foreach ($imagenesAutoresImages as $image) {
            if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $image)) {
                $images[] = str_replace('public/', '', $image);
            }
        }

        return response()->json($images);
    }
}
