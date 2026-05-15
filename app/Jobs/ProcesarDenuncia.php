<?php

namespace App\Jobs;

use App\Models\Denuncia;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcesarDenuncia implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        private array $datos,
        private array $archivosTemp
    ) {}

    public function handle(): void
    {
        Log::info('[Job] ProcesarDenuncia iniciado', [
            'titulo'       => $this->datos['titulo'] ?? '—',
            'archivos_tmp' => $this->archivosTemp,
        ]);

        $datos = $this->datos;

        foreach (['imagen1', 'imagen2', 'imagen3', 'imagen4'] as $campo) {
            if (!isset($this->archivosTemp[$campo])) {
                continue;
            }

            $tempPath = $this->archivosTemp[$campo];

            if (!Storage::disk('local')->exists($tempPath)) {
                Log::warning("[Job] archivo temp no existe: {$tempPath}");
                continue;
            }

            $destino = 'denuncias/' . basename($tempPath);
            Storage::disk('public')->put($destino, Storage::disk('local')->get($tempPath));
            Storage::disk('local')->delete($tempPath);
            $datos[$campo] = $destino;

            Log::info("[Job] {$campo} movida → {$destino}");
        }

        $datos['estado'] = 'pendiente';
        $denuncia = Denuncia::create($datos);

        Log::info('[Job] ProcesarDenuncia completado', [
            'denuncia_id' => $denuncia->id,
            'slug'        => $denuncia->slug,
        ]);
    }

    public function failed(\Throwable $e): void
    {
        Log::error('[Job] ProcesarDenuncia FALLÓ', [
            'titulo' => $this->datos['titulo'] ?? '—',
            'error'  => $e->getMessage(),
            'trace'  => $e->getTraceAsString(),
        ]);

        foreach ($this->archivosTemp as $tempPath) {
            Storage::disk('local')->delete($tempPath);
        }
    }
}
