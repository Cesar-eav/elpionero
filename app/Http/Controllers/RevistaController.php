<?php

namespace App\Http\Controllers;

use App\Models\Revista;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Para generar slugs
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\View; // 

class RevistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $revistas = Revista::latest()->paginate(10); // Obtener las últimas revistas paginadas
        return view('revistas.index', compact('revistas')); // Crear vista 'revistas/index.blade.php'
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('revistas.create'); // Crear vista 'revistas/create.blade.php'
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255|unique:revistas',
            'fecha_publicacion' => 'nullable|date',
            'descripcion' => 'nullable|string',
        ]);

        $revista = Revista::create([
            'titulo' => $request->titulo,
            'slug' => Str::slug($request->titulo),
            'fecha_publicacion' => $request->fecha_publicacion,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('revistas.show', $revista)->with('success', 'Revista creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Revista $revista)
    {
        return view('revistas.show', compact('revista')); // Crear vista 'revistas/show.blade.php'
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Revista $revista)
    {
        return view('revistas.edit', compact('revista')); // Crear vista 'revistas/edit.blade.php'
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Revista $revista)
    {
        $request->validate([
            'titulo' => 'required|string|max:255|unique:revistas,titulo,' . $revista->id,
            'fecha_publicacion' => 'nullable|date',
            'descripcion' => 'nullable|string',
        ]);

        $revista->update([
            'titulo' => $request->titulo,
            'slug' => Str::slug($request->titulo),
            'fecha_publicacion' => $request->fecha_publicacion,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('revistas.show', $revista)->with('success', 'Revista actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Revista $revista)
    {
        $revista->delete();
        return redirect()->route('revistas.index')->with('success', 'Revista eliminada exitosamente.');
    }

    public function generarPDF(Revista $revista)
    {
        // Cargar los artículos relacionados con la revista
        $articulos = $revista->articulos;

        // Compartir la ubicación actual para mostrarla en el PDF
        $ubicacion = 'Valparaíso, Valparaíso, Chile';
        $fechaGeneracion = now()->format('Y-m-d H:i:s');

        // Renderizar una vista Blade específica para el PDF
        $pdf = Pdf::loadView('pdfs.revista', compact('revista', 'articulos', 'ubicacion', 'fechaGeneracion'));

        // Descargar el PDF con un nombre específico
        $nombreArchivo = Str::slug($revista->titulo) . '-' . now()->format('YmdHis') . '.pdf';
        return $pdf->download($nombreArchivo);
    }

    public function previsualizarPDF(Revista $revista)
    {
        // Cargar los artículos relacionados con la revista
        $articulos = $revista->articulos;

        // Compartir la ubicación actual para mostrarla en el PDF
        $ubicacion = 'Valparaíso, Valparaíso, Chile';
        $fechaGeneracion = now()->format('Y-m-d H:i:s');

        // Renderizar la vista Blade del PDF
        $pdfView = View::make('pdfs.revista', compact('revista', 'articulos', 'ubicacion', 'fechaGeneracion'));
        $html = $pdfView->render();

        // Mostrar la vista de previsualización
        return view('mostrar-pdf', ['pdfHtml' => $html, 'revista' => $revista]);
    }
}