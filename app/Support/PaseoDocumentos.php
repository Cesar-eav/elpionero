<?php

namespace App\Support;

/**
 * Documentos oficiales explicados en /cuidemoselpaseo/documentos (textos en resources/data/paseo-documentos.php).
 *
 * Los PDF viven en public/cuidemos_el_paseo/documentos/, que se sube por FTP y no está en git: url() solo devuelve
 * un enlace si el archivo existe, para no publicar enlaces rotos mientras falte subir algo.
 */
class PaseoDocumentos
{
    public const CARPETA = 'cuidemos_el_paseo/documentos/';

    private static ?array $carpetas = null;

    public static function carpetas(): array
    {
        return self::$carpetas ??= require resource_path('data/paseo-documentos.php');
    }

    /** @return array<string, array> documentos por id, con la carpeta a la que pertenecen en 'carpeta' */
    public static function todos(): array
    {
        $docs = [];
        foreach (self::carpetas() as $c) {
            foreach ($c['documentos'] as $d) {
                $docs[$d['id']] = $d + ['carpeta' => $c['id']];
            }
        }

        return $docs;
    }

    public static function porId(string $id): ?array
    {
        return self::todos()[$id] ?? null;
    }

    /** Hitos de todos los documentos, en orden cronológico, para la línea de tiempo. */
    public static function hitos(): array
    {
        $hitos = [];
        foreach (self::todos() as $d) {
            foreach ($d['hitos'] ?? [] as $h) {
                $hitos[] = $h + ['doc' => $d['id']];
            }
        }
        usort($hitos, fn ($a, $b) => strcmp($a['orden'], $b['orden']));

        return $hitos;
    }

    /** URL pública del archivo (abierto en la página indicada) o null si todavía no está en el servidor. */
    public static function url(?string $archivo, ?int $pagina = null): ?string
    {
        if (! $archivo || ! is_file(public_path(self::CARPETA . $archivo))) {
            return null;
        }
        // Los nombres traen espacios, tildes y "°": se codifica cada tramo de la ruta
        $ruta = implode('/', array_map('rawurlencode', explode('/', self::CARPETA . $archivo)));

        return asset($ruta) . ($pagina ? '#page=' . $pagina : '');
    }

    /** Peso legible del archivo ("18,7 MB"), o null si no está. */
    public static function peso(?string $archivo): ?string
    {
        $ruta = $archivo ? public_path(self::CARPETA . $archivo) : null;
        if (! $ruta || ! is_file($ruta)) {
            return null;
        }
        $bytes = filesize($ruta);

        return $bytes >= 1048576
            ? number_format($bytes / 1048576, 1, ',', '.') . ' MB'
            : max(1, round($bytes / 1024)) . ' KB';
    }
}
