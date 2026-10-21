<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaseoContactoController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Trampa para bots: un humano nunca ve ni llena este campo
        if ($request->filled('web')) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'motivo' => 'required|in:unirme,informacion',
            'mensaje' => 'nullable|string|max:1000',
            'correo' => 'nullable|email|max:150|required_without:whatsapp',
            'whatsapp' => 'nullable|string|max:30|required_without:correo',
        ], [
            'nombre.required' => 'Escribe tu nombre.',
            'motivo.required' => 'Elige una opción.',
            'correo.required_without' => 'Indica un WhatsApp o un correo.',
            'whatsapp.required_without' => 'Indica un WhatsApp o un correo.',
            'correo.email' => 'El correo no es válido.',
        ]);

        $token = config('services.telegram.token');
        $chatId = config('services.telegram.chat_id');

        if (! $token || ! $chatId) {
            Log::warning('[paseo-contacto] faltan TELEGRAM_BOT_TOKEN o TELEGRAM_CHAT_ID');
            return $this->falla();
        }

        $motivo = $data['motivo'] === 'unirme' ? 'Quiere unirse' : 'Pide más información';

        // parse_mode HTML: todo lo que escribe la persona se escapa
        $texto = "🙋 <b>Nuevo contacto – Cuidemos el Paseo</b>\n"
            . "Motivo: {$motivo}\n"
            . 'Nombre: ' . e($data['nombre']) . "\n"
            . (! empty($data['whatsapp']) ? 'WhatsApp: ' . e($data['whatsapp']) . "\n" : '')
            . (! empty($data['correo']) ? 'Correo: ' . e($data['correo']) . "\n" : '')
            . (! empty($data['mensaje']) ? 'Mensaje: ' . e($data['mensaje']) : '');

        try {
            $response = Http::timeout(8)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => trim($texto),
                'parse_mode' => 'HTML',
            ]);
        } catch (\Throwable $e) {
            Log::warning('[paseo-contacto] Telegram no respondió', ['error' => $e->getMessage()]);
            return $this->falla();
        }

        if ($response->failed()) {
            Log::warning('[paseo-contacto] envío falló', ['response' => $response->body()]);
            return $this->falla();
        }

        return response()->json(['ok' => true]);
    }

    private function falla(): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'message' => 'No pudimos enviar tu mensaje. Inténtalo de nuevo o escríbenos por Instagram.',
        ], 502);
    }
}
