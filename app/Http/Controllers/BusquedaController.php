<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Editorial;
use App\Models\Noticia;
use App\Models\Entrevista;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->input('q');
        $tipo = $request->input('tipo', 'todos'); // todos, columnas, editoriales, noticias, entrevistas

        if (empty($query)) {
            return redirect()->back()->with('error', 'Por favor ingresa un término de búsqueda');
        }

        $resultados = [];

        // Buscar en columnas (artículos) con sistema de relevancia
        if ($tipo === 'todos' || $tipo === 'columnas') {
            $resultados['columnas'] = Articulo::with(['revista', 'columnista'])
                ->select('articulos.*')
                ->selectRaw('
                    CASE
                        WHEN LOWER(titulo) LIKE LOWER(?) THEN 100
                        WHEN LOWER(titulo) LIKE LOWER(?) THEN 80
                        ELSE 0
                    END +
                    CASE
                        WHEN EXISTS (
                            SELECT 1 FROM columnistas
                            WHERE columnistas.id = articulos.columnista_id
                            AND LOWER(columnistas.nombre) LIKE LOWER(?)
                        ) THEN 70
                        WHEN EXISTS (
                            SELECT 1 FROM columnistas
                            WHERE columnistas.id = articulos.columnista_id
                            AND LOWER(columnistas.nombre) LIKE LOWER(?)
                        ) THEN 50
                        ELSE 0
                    END +
                    CASE
                        WHEN LOWER(contenido) LIKE LOWER(?) THEN 30
                        ELSE 0
                    END as relevancia
                ', [
                    $query,           // Coincidencia exacta en título
                    "%{$query}%",     // Coincidencia parcial en título
                    $query,           // Coincidencia exacta en columnista
                    "%{$query}%",     // Coincidencia parcial en columnista
                    "%{$query}%"      // Coincidencia en contenido
                ])
                ->where(function($q) use ($query) {
                    $q->where('titulo', 'LIKE', "%{$query}%")
                      ->orWhere('contenido', 'LIKE', "%{$query}%")
                      ->orWhereHas('columnista', function($q) use ($query) {
                          $q->where('nombre', 'LIKE', "%{$query}%");
                      });
                })
                ->orderByDesc('relevancia')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        }

        // Buscar en editoriales con sistema de relevancia
        if ($tipo === 'todos' || $tipo === 'editoriales') {
            $resultados['editoriales'] = Editorial::with('revista')
                ->select('editorials.*')
                ->selectRaw('
                    CASE
                        WHEN LOWER(titulo) LIKE LOWER(?) THEN 100
                        WHEN LOWER(titulo) LIKE LOWER(?) THEN 80
                        ELSE 0
                    END +
                    CASE
                        WHEN LOWER(contenido) LIKE LOWER(?) THEN 30
                        ELSE 0
                    END as relevancia
                ', [
                    $query,           // Coincidencia exacta en título
                    "%{$query}%",     // Coincidencia parcial en título
                    "%{$query}%"      // Coincidencia en contenido
                ])
                ->where(function($q) use ($query) {
                    $q->where('titulo', 'LIKE', "%{$query}%")
                      ->orWhere('contenido', 'LIKE', "%{$query}%");
                })
                ->orderByDesc('relevancia')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        }

        // Buscar en noticias con sistema de relevancia
        if ($tipo === 'todos' || $tipo === 'noticias') {
            $resultados['noticias'] = Noticia::select('noticias.*')
                ->selectRaw('
                    CASE
                        WHEN LOWER(titulo) LIKE LOWER(?) THEN 100
                        WHEN LOWER(titulo) LIKE LOWER(?) THEN 80
                        ELSE 0
                    END +
                    CASE
                        WHEN LOWER(resumen) LIKE LOWER(?) THEN 50
                        ELSE 0
                    END +
                    CASE
                        WHEN LOWER(cuerpo) LIKE LOWER(?) THEN 30
                        ELSE 0
                    END as relevancia
                ', [
                    $query,           // Coincidencia exacta en título
                    "%{$query}%",     // Coincidencia parcial en título
                    "%{$query}%",     // Coincidencia en resumen
                    "%{$query}%"      // Coincidencia en cuerpo
                ])
                ->where(function($q) use ($query) {
                    $q->where('titulo', 'LIKE', "%{$query}%")
                      ->orWhere('resumen', 'LIKE', "%{$query}%")
                      ->orWhere('cuerpo', 'LIKE', "%{$query}%");
                })
                ->orderByDesc('relevancia')
                ->orderByDesc('fecha_publicacion')
                ->limit(10)
                ->get();
        }

        // Buscar en entrevistas con sistema de relevancia
        if ($tipo === 'todos' || $tipo === 'entrevistas') {
            $resultados['entrevistas'] = Entrevista::select('entrevistas.*')
                ->selectRaw('
                    CASE
                        WHEN LOWER(titulo) LIKE LOWER(?) THEN 100
                        WHEN LOWER(titulo) LIKE LOWER(?) THEN 80
                        ELSE 0
                    END +
                    CASE
                        WHEN LOWER(entrevistado) LIKE LOWER(?) THEN 70
                        WHEN LOWER(entrevistado) LIKE LOWER(?) THEN 50
                        ELSE 0
                    END +
                    CASE
                        WHEN LOWER(cargo) LIKE LOWER(?) THEN 40
                        ELSE 0
                    END +
                    CASE
                        WHEN LOWER(contenido) LIKE LOWER(?) THEN 30
                        ELSE 0
                    END as relevancia
                ', [
                    $query,           // Coincidencia exacta en título
                    "%{$query}%",     // Coincidencia parcial en título
                    $query,           // Coincidencia exacta en entrevistado
                    "%{$query}%",     // Coincidencia parcial en entrevistado
                    "%{$query}%",     // Coincidencia en cargo
                    "%{$query}%"      // Coincidencia en contenido
                ])
                ->where(function($q) use ($query) {
                    $q->where('titulo', 'LIKE', "%{$query}%")
                      ->orWhere('entrevistado', 'LIKE', "%{$query}%")
                      ->orWhere('cargo', 'LIKE', "%{$query}%")
                      ->orWhere('contenido', 'LIKE', "%{$query}%");
                })
                ->orderByDesc('relevancia')
                ->orderByDesc('fecha_publicacion')
                ->limit(10)
                ->get();
        }

        // Contar total de resultados
        $totalResultados = collect($resultados)->sum(function($items) {
            return $items->count();
        });

        return view('inicio.busqueda', compact('resultados', 'query', 'tipo', 'totalResultados'));
    }
}
