<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaApiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'icono' => 'required|string|max:255',
        ]);
        $validated['slug'] = \Str::slug($validated['nombre']);
        $categoria = Categoria::create($validated);
        return response()->json($categoria, 201);
    }
}
