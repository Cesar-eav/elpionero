<?php

namespace App\Http\Controllers;

use App\Models\Columnista;
use App\Models\Revista;
use Illuminate\Support\Str;


use Illuminate\Http\Request;

class ColumnistaController extends Controller
{
    public function index()
    {
        $columnistas = Columnista::with('revista')
            ->orderBy('nombre')
            ->paginate(10); 

        $revistas = Revista::orderBy('fecha_publicacion', 'desc')->get();

        return view('admin.columnistas', compact('columnistas', 'revistas'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:columnistas,email',
            'bio' => 'nullable|string',
            'participa_proximo_numero' => 'nullable|boolean',
            'revista_id' => 'nullable|exists:revistas,id',
            //'foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // <-- validar imagen

        ]);

        $columnista = new Columnista($request->all());

        // Convertir a booleano si viene del checkbox
        $columnista->participa_proximo_numero = (bool)($request->participa_proximo_numero ?? false);

        // Guardar imagen si existe
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('/public/columnistas/imagenes_autores');
            //$columnista->foto = Storage::url($path);
            $columnista->foto = str_replace('public/', 'storage/', $path); // Guarda la ruta relativa para acceder desde la web

        }

        $columnista->save();

        return back()->with('success', 'Columnista creado correctamente.');
    }


    public function update(Request $request, Columnista $columnista)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:columnistas,email,'.$columnista->id,
            'bio' => 'nullable|string',
            'participa_proximo_numero' => 'nullable|boolean',
            'revista_id' => 'nullable|exists:revistas,id',
        ]);
        $data['participa_proximo_numero'] = (bool)($data['participa_proximo_numero'] ?? false);
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('columnistas', 'public');
        }
        $columnista->update($data);
        return back()->with('success', 'Columnista actualizado correctamente.');
    }

    public function destroy(Columnista $columnista)
    {
        $columnista->delete();
        return back()->with('success', 'Columnista eliminado.');
    }
}