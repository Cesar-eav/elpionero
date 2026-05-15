<?php

namespace App\Http\Controllers;

use App\Jobs\ProcesarDenuncia;
use App\Models\Denuncia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DenunciaController extends Controller
{
    public function index()
    {
        $denuncias = Denuncia::where('estado', 'aprobada')
            ->latest('approved_at')
            ->paginate(12);

        return view('denuncia.index', compact('denuncias'));
    }

    public function formulario()
    {
        return view('denuncia.formulario');
    }

    public function show(Denuncia $denuncia)
    {
        if ($denuncia->estado !== 'aprobada') {
            abort(404);
        }

        return view('denuncia.show', compact('denuncia'));
    }

    public function store(Request $request)
    {
        $t0 = microtime(true);
        $ms = fn() => round((microtime(true) - $t0) * 1000) . 'ms';
        \Log::info('[FORM] request recibido');

        session()->save();
        \Log::info('[FORM] session liberada — ' . $ms());

        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'nombre'      => 'nullable|string|max:255',
            'descripcion' => 'required|string|min:20',
            'ubicacion'   => 'required|string|max:500',
            'imagen1'     => 'required|image|max:5120',
            'imagen2'     => 'nullable|image|max:5120',
            'imagen3'     => 'nullable|image|max:5120',
        ], [
            'titulo.required'  => 'El título es obligatorio.',
            'imagen1.required' => 'Debes subir al menos una imagen.',
            'descripcion.min'  => 'La descripción debe tener al menos 20 caracteres.',
        ]);

        \Log::info('[FORM] validación OK — ' . $ms());

        // Mover imágenes a temp (rápido, disco local) y despachar el job
        $archivosTemp = [];
        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($request->hasFile($campo)) {
                $nombre = Str::uuid() . '.' . $request->file($campo)->getClientOriginalExtension();
                $archivosTemp[$campo] = $request->file($campo)->storeAs('temp/denuncias', $nombre, 'local');
                \Log::info('[FORM] ' . $campo . ' → temp — ' . $ms() . ' | ' . round($request->file($campo)->getSize() / 1024) . 'KB');
                unset($validated[$campo]);
            }
        }

        ProcesarDenuncia::dispatch($validated, $archivosTemp);
        \Log::info('[FORM] job despachado — ' . $ms());

        \Log::info('[FORM] TOTAL respuesta — ' . $ms());
        return view('denuncia.gracias');
    }

    public function gracias()
    {
        return view('denuncia.gracias');
    }
}
