<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdfTracking;
use Illuminate\Support\Facades\Storage;

class PdfTrackingController extends Controller
{
    public function track(Request $request, $pdfName, $action)
    {
        // Validar acción
        if (!in_array($action, ['download', 'view'])) {
            abort(404);
        }

        // Registrar el tracking
        PdfTracking::create([
            'pdf_name' => $pdfName,
            'action' => $action,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
        ]);

        // Redirigir al PDF
        $pdfPath = "storage/Ediciones/{$pdfName}";

        if ($action === 'download') {
            return response()->download(public_path($pdfPath));
        } else {
            return redirect(asset($pdfPath));
        }
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
        ];

        return response()->json($stats);
    }
}
