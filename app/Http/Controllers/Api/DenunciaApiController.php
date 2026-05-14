<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Denuncia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DenunciaApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Denuncia::query();

        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('descripcion', 'like', "%{$search}%")
                  ->orWhere('ubicacion', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $denuncias = $query->latest()->paginate($perPage);

        return response()->json($denuncias);
    }

    public function show(Denuncia $denuncia)
    {
        return response()->json($denuncia);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'nullable|string|max:255',
            'descripcion' => 'required|string',
            'ubicacion'   => 'required|string|max:500',
            'imagen1'     => 'required|image|mimes:jpeg,jpg,png,gif|max:5120',
            'imagen2'     => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
            'imagen3'     => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
        ]);

        $validated['imagen1'] = $request->file('imagen1')->store('denuncias', 'public');

        if ($request->hasFile('imagen2')) {
            $validated['imagen2'] = $request->file('imagen2')->store('denuncias', 'public');
        }

        if ($request->hasFile('imagen3')) {
            $validated['imagen3'] = $request->file('imagen3')->store('denuncias', 'public');
        }

        $validated['estado'] = 'pendiente';

        $denuncia = Denuncia::create($validated);

        return response()->json($denuncia, 201);
    }

    public function aprobar(Denuncia $denuncia)
    {
        $denuncia->update([
            'estado'      => 'aprobada',
            'approved_at' => now(),
        ]);

        return response()->json($denuncia);
    }

    public function rechazar(Denuncia $denuncia)
    {
        $this->deleteImages($denuncia);
        $denuncia->delete();

        return response()->json(['message' => 'Denuncia rechazada y eliminada']);
    }

    public function destroy(Denuncia $denuncia)
    {
        $this->deleteImages($denuncia);
        $denuncia->delete();

        return response()->json(['message' => 'Denuncia eliminada exitosamente']);
    }

    private function deleteImages(Denuncia $denuncia): void
    {
        foreach (['imagen1', 'imagen2', 'imagen3'] as $field) {
            if ($denuncia->$field) {
                Storage::disk('public')->delete($denuncia->$field);
            }
        }
    }
}
