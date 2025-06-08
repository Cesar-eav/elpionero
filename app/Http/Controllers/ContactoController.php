<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Contacto;


class ContactoController extends Controller
{
    /**
     * Muestra el formulario de contacto.
     *
     * @return \Illuminate\View\View
     */
    public function mostrarFormulario()
    {
        return view('contacto.formulario');
    }

    public function listarContactos()
    {
        $contactos = Contacto::orderBy('created_at', 'desc')->get(); 

        return view('contacto.lista', compact('contactos'));
    }

    /**
     * Procesa el envío del formulario de contacto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enviarFormulario(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'motivo' => 'required|string',
        ]);

        // Guardar los datos en la base de datos
        Contacto::create($validatedData);

        return redirect()->route('contacto.formulario')->with('success', 'Tu mensaje ha sido enviado correctamente.');
    }
}