<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdfTracking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfTrackingController extends Controller
{
public function track(Request $request, $pdfName, $action)
    {
        // 1. Seguridad: Limpiar el nombre del archivo para evitar "Path Traversal"
        // Esto evita que alguien pida '../../.env'
        $fileName = basename($pdfName);

        if (!in_array($action, ['download', 'view'])) {
            abort(404);
        }

        // 2. Definir la ruta real en el disco (ajusta según tu estructura)
        // Asumiendo que están en storage/app/public/Ediciones
        $relativeDiskPath = "Ediciones/{$fileName}";
        
        if (!Storage::disk('public')->exists($relativeDiskPath)) {
            abort(404, 'El archivo solicitado no existe en el servidor.');
        }

        // 3. Registrar el tracking (Silenciosamente para no afectar al usuario)
        try {
            PdfTracking::create([
                'pdf_name' => $fileName,
                'action' => $action,
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit($request->userAgent(), 255),
                'referer' => Str::limit($request->header('referer'), 255),
            ]);
        } catch (\Exception $e) {
            // Logueamos el error pero permitimos que la descarga siga
            \Log::error("Error tracking PDF: " . $e->getMessage());
        }

        // 4. Ejecutar la respuesta adecuada
        $fullPath = storage_path("app/public/{$relativeDiskPath}");

        if ($action === 'download') {
            // Forzamos la descarga con el nombre original
            return response()->download($fullPath, $fileName);
        }

        // Para 'view', redirigimos a la URL pública para que el navegador lo abra
        return redirect()->to(asset("storage/{$relativeDiskPath}"));
    }

    public function stats()
    {
        $stats = [
            'total' => PdfTracking::count(),
            'downloads' => PdfTracking::where('action', 'download')->count(),
            'views' => PdfTracking::where('action', 'view')->count(),
            'by_pdf' => PdfTracking::selectRaw('pdf_name, action, COUNT(*) as count')
                ->groupBy('pdf_name', 'action')
                ->get()
                ->groupBy('pdf_name'),
            'recent' => PdfTracking::orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'by_day' => PdfTracking::selectRaw('DATE(created_at) as date, action, COUNT(*) as count')
                ->groupBy('date', 'action')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get()
                ->groupBy('date'),
            'by_month' => PdfTracking::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date, action, COUNT(*) as count')
                ->groupBy('date', 'action')
                ->orderBy('date', 'desc')
                ->limit(12)
                ->get()
                ->groupBy('date'),
        ];

        return response()->json($stats);
    }
}
