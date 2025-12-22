<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriviaReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pregunta_texto' => 'required|string',
            'comentario' => 'nullable|string',
        ]);

        DB::table('trivia_reports')->insert([
            'pregunta_texto' => $validated['pregunta_texto'],
            'comentario' => $validated['comentario'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', '¡Reporte enviado! Gracias por ayudarnos a mejorar.');
    }
}
