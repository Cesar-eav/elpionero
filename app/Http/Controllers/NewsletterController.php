<?php

namespace App\Http\Controllers;
use App\Models\Suscriptor;

use Illuminate\Http\Request;

class NewsletterController extends Controller
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
