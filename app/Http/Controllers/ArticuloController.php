<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Revista;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articulos = Articulo::latest()->paginate(15);
        return view('articulos.index', compact('articulos'));
    }

    public function showArticulo($id)
    {
        
        $articulo = Articulo::where('id', $id)->first();
        $articulos = Articulo::inRandomOrder()->limit(8)->get();


        return view('pdfs.articulo', compact('articulo', 'articulos'));
    }

    public function showColumnas()
    {
        $columnas = Articulo::get();


        return view('pdfs.columnas', compact('columnas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $revistas = Revista::all(); // Obtener todas las revistas para el formulario
        return view('articulos.create', compact('revistas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'revista_id' => 'required|exists:revistas,id',
            'titulo' => 'required|string|max:255|unique:articulos',
            'contenido' => 'required|string',
            'autor' => 'nullable|string|max:255',
            'imagen_autor' => 'nullable|image|max:2048', // Validación para la imagen (opcional, debe ser una imagen, máximo 2MB)
            'seccion' => 'nullable|string|max:255',
        ]);

        $articulo = new Articulo($request->all());
        $articulo->slug = Str::slug($request->titulo);

        if ($request->hasFile('imagen_autor')) {
            $path = $request->file('imagen_autor')->store('public/imagenes_autores'); // Guarda la imagen en storage/app/public/imagenes_autores
            $articulo->imagen_autor = str_replace('public/', 'storage/', $path); // Guarda la ruta relativa para acceder desde la web
        }

        $articulo->save();

        return redirect()->route('articulos.show', $articulo)->with('success', 'Artículo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Articulo $articulo)
    {
        return view('articulos.show', compact('articulo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Articulo $articulo)
    {
        $revistas = Revista::all();
        return view('articulos.edit', compact('articulo', 'revistas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Articulo $articulo)
    {
        $request->validate([
            'revista_id' => 'required|exists:revistas,id',
            'titulo' => 'required|string|max:255|unique:articulos,titulo,' . $articulo->id,
            'contenido' => 'required|string',
            'autor' => 'nullable|string|max:255',
            'imagen_autor' => 'nullable|image|max:2048',
            'seccion' => 'nullable|string|max:255',
        ]);

        $articulo->fill($request->all());
        $articulo->slug = Str::slug($request->titulo);

        if ($request->hasFile('imagen_autor')) {
            // Eliminar la imagen anterior si existe
            if ($articulo->imagen_autor) {
                Storage::delete(str_replace('storage/', 'public/', $articulo->imagen_autor));
            }
            $path = $request->file('imagen_autor')->store('public/imagenes_autores');
            $articulo->imagen_autor = str_replace('public/', 'storage/', $path);
        }

        $articulo->save();

        return redirect()->route('articulos.show', $articulo)->with('success', 'Artículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Articulo $articulo)
    {

        // Eliminar la imagen del autor si existe antes de eliminar el artículo

        $articulo->delete();
        return redirect()->route('articulos.index')->with('success', 'Artículo eliminado exitosamente.');
    }
}