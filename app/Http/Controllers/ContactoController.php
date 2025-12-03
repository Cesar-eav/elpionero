<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:suscriptores,email',
        ]);

        Suscriptor::create([
            'email' => $request->email,
        ]);

        return back()->with('success', '¡Gracias por suscribirte al newsletter!');
    }
}

