<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'nombre'      => 'nullable|string|max:255',
            'descripcion' => 'required|string|min:20',
            'ubicacion'   => 'required|string|max:500',
            'imagen1'     => 'required|image|mimes:jpeg,jpg,png,gif|max:5120',
            'imagen2'     => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
            'imagen3'     => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
        ], [
            'titulo.required'  => 'El título es obligatorio.',
            'imagen1.required' => 'Debes subir al menos una imagen.',
            'descripcion.min'  => 'La descripción debe tener al menos 20 caracteres.',
        ]);

        $validated['imagen1'] = $request->file('imagen1')->store('denuncias', 'public');

        if ($request->hasFile('imagen2')) {
            $validated['imagen2'] = $request->file('imagen2')->store('denuncias', 'public');
        }

        if ($request->hasFile('imagen3')) {
            $validated['imagen3'] = $request->file('imagen3')->store('denuncias', 'public');
        }

        $validated['estado'] = 'pendiente';

        Denuncia::create($validated);

        return redirect()->route('denuncia.gracias');
    }

    public function gracias()
    {
        return view('denuncia.gracias');
    }
}
