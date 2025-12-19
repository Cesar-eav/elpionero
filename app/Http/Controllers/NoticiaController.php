<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class NoticiaController extends Controller
{
    /**
     * Muestra un listado de las noticias.
     */
    public function index()
    {
        $noticias = Noticia::latest()->paginate(10);
        return view('admin.noticias.index', compact('noticias'));
    }

    public function noticiasIndex()
    {
        $noticias = Noticia::latest()->paginate(10);
        return view('noticias', compact('noticias'));
    }

    /**
     * Muestra el formulario para crear una nueva noticia.
     */
    public function create()
    {
        return view('noticias.create');
    }

    /**
     * Guarda una noticia recién creada en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'resumen' => 'nullable|string|max:500',
            'cuerpo' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
            'fecha_publicacion' => 'required|date',
           // 'slug' => 'required|string|unique:noticias,slug|max:255'
        ]);

        $data = $request->all();

        // Generar slug único
        $slugBase = Str::slug($request->titulo);
        $slug = $slugBase;
        $count = 1;

        while (Noticia::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $count++;
        }

        $data['slug'] = $slug;

        // Manejo de imagen si existe
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('noticias', 'public');
        }

        Noticia::create($data);

        return redirect()->route('noticias.index')
                         ->with('success', 'Noticia creada correctamente.');
    }

    /**
     * Muestra una noticia específica.
     */
    public function show(Noticia $noticia)
    {
        return view('noticias.show', compact('noticia'));
    }

    /**
     * Muestra una noticia específica por slug (vista pública).
     */
    public function showBySlug($slug)
    {
        $noticia = Noticia::where('slug', $slug)->firstOrFail();
        return view('noticia-detalle', compact('noticia'));
    }

    /**
     * Muestra el formulario para editar una noticia existente.
     */
    public function edit(Noticia $noticia)
    {
        return view('noticias.edit', compact('noticia'));
    }

    /**
     * Actualiza una noticia en la base de datos.
     */
    public function update(Request $request, Noticia $noticia)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'resumen' => 'nullable|string|max:500',
            'cuerpo' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
            'fecha_publicacion' => 'required|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('noticias', 'public');
        }

        $noticia->update($data);

        return redirect()->route('noticias.index')
                         ->with('success', 'Noticia actualizada correctamente.');
    }

    /**
     * Elimina una noticia de la base de datos.
     */
    public function destroy(Noticia $noticia)
    {
        $noticia->delete();

        return redirect()->route('noticias.index')
                         ->with('success', 'Noticia eliminada correctamente.');
    }
}
