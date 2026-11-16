<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Enlaces de El Pionero hacia Pindoor (sitio hermano, guía turística de Valparaíso).
 *
 * - panoramas(): últimos panoramas del feed Atom de Pindoor, para el bloque del home.
 * - lugares():   nombre + slug de los lugares de Pindoor (`/lugares.json`).
 * - enlazar():   convierte la primera mención de cada lugar en el HTML de un artículo en un enlace
 *                a su ficha en Pindoor y devuelve los lugares encontrados (recuadro al pie).
 *
 * Pindoor nunca puede botar ni frenar El Pionero: timeout corto, caché, y si falla se usa la última
 * copia buena (o nada) durante unos minutos antes de reintentar.
 */
class Pindoor
{
    public const MAX_ENLACES_TEXTO = 5;

    public const MAX_RECUADRO = 6;

    /** Nombres que no se enlazan aunque existan en Pindoor (demasiado genéricos para el texto). */
    private const NOMBRES_EXCLUIDOS = ['valparaiso', 'viña del mar', 'chile', 'el pionero']; // en forma self::clave()

    /** Dentro de estos elementos no se crean enlaces. */
    private const SIN_ENLACES = ['a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'figcaption', 'script', 'style', 'button', 'code', 'pre', 'iframe'];

    public static function url(string $ruta = ''): string
    {
        return config('services.pindoor.url') . '/' . ltrim($ruta, '/');
    }

    public static function urlLugar(string $slug): string
    {
        return self::url('lugar/' . rawurlencode($slug));
    }

    /**
     * Últimos panoramas publicados en Pindoor: [['titulo', 'url', 'resumen'], ...].
     * Se saltan las entradas fijas del feed («hoy», «fin de semana»), que se enlazan aparte.
     */
    public static function panoramas(int $cantidad = 4): array
    {
        $entradas = self::remoto('pindoor.panoramas.v1', 'panoramas/feed.xml', 3600, function (string $xml) {
            $feed = @simplexml_load_string($xml);
            if (! $feed) {
                return null;
            }

            $entradas = [];
            foreach ($feed->entry as $entry) {
                $url = (string) ($entry->link['href'] ?? $entry->id);
                if (! str_starts_with($url, self::url()) || preg_match('#/panoramas/(hoy|manana|fin-de-semana)$#', $url)) {
                    continue;
                }
                $entradas[] = [
                    'titulo'  => self::titulo((string) $entry->title),
                    'url'     => $url,
                    'resumen' => Str::limit(trim((string) $entry->summary), 110),
                ];
            }

            return $entradas;
        });

        return array_slice($entradas, 0, $cantidad);
    }

    /** Lugares públicos de Pindoor: [['nombre' => ..., 'slug' => ...], ...]. */
    public static function lugares(): array
    {
        return self::remoto('pindoor.lugares.v1', 'lugares.json', 6 * 3600, function (string $json) {
            $datos = json_decode($json, true);
            if (! is_array($datos)) {
                return null;
            }

            return array_values(array_filter(array_map(fn ($l) => [
                'nombre' => trim((string) ($l['nombre'] ?? '')),
                'slug'   => trim((string) ($l['slug'] ?? '')),
            ], $datos), fn ($l) => $l['nombre'] !== '' && $l['slug'] !== ''));
        });
    }

    /**
     * Enlaza a Pindoor la primera mención de cada lugar en el HTML (texto corrido, no títulos ni
     * enlaces existentes). Devuelve ['html' => ..., 'lugares' => [['nombre', 'url'], ...]].
     * Si no hay nada que enlazar, el HTML vuelve tal cual (sin pasar por DOMDocument).
     */
    public static function enlazar(?string $html): array
    {
        $html = (string) $html;
        $vacio = ['html' => $html, 'lugares' => []];

        $lugares = self::lugaresEnlazables();
        if (trim($html) === '' || ! $lugares) {
            return $vacio;
        }

        $clave = 'pindoor.enlazar.v1.' . md5($html . '|' . md5(serialize($lugares)));

        return Cache::remember($clave, 86400, fn () => self::procesar($html, $lugares) ?? $vacio);
    }

    /** Lugares filtrados (sin nombres genéricos) y ordenados del nombre más largo al más corto. */
    private static function lugaresEnlazables(): array
    {
        // Un nombre repetido (dos lugares con el mismo título) es ambiguo: no se enlaza.
        $porNombre = [];
        foreach (self::lugares() as $l) {
            // «Puerta China (roja)» se busca como «Puerta China»
            $l['nombre'] = trim(preg_replace(['/\s*\([^)]*\)\s*$/u', '/\s+/u'], ['', ' '], $l['nombre']));
            $clave = self::clave($l['nombre']);
            $palabras = explode(' ', $clave);

            if ($clave === '' || in_array($clave, self::NOMBRES_EXCLUIDOS, true) || (count($palabras) === 1 && mb_strlen($clave) < 8)) {
                continue;
            }
            $porNombre[$clave][] = $l;
        }
        $unicos = array_map(fn ($grupo) => $grupo[0], array_filter($porNombre, fn ($grupo) => count($grupo) === 1));

        uasort($unicos, fn ($a, $b) => mb_strlen($b['nombre']) <=> mb_strlen($a['nombre']));

        return $unicos; // clave: self::clave(nombre)
    }

    /** Forma de comparar nombres: minúsculas, sin tildes y con espacios simples. */
    private static function clave(string $texto): string
    {
        return mb_strtolower(trim(preg_replace('/[\s\x{00A0}]+/u', ' ', strtr($texto, self::SIN_TILDE))));
    }

    private const SIN_TILDE = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
    ];

    /** Cada vocal calza con o sin tilde: «Maritimo» encuentra «Marítimo» y viceversa. */
    private const VOCALES = [
        'a' => '[aáà]', 'e' => '[eéè]', 'i' => '[iíì]', 'o' => '[oóò]', 'u' => '[uúùü]',
        'A' => '[AÁÀ]', 'E' => '[EÉÈ]', 'I' => '[IÍÌ]', 'O' => '[OÓÒ]', 'U' => '[UÚÙÜ]',
    ];

    private static function procesar(string $html, array $lugares): ?array
    {
        $regex = self::regex($lugares);

        $anterior = libxml_use_internal_errors(true);
        $doc = new DOMDocument('1.0', 'UTF-8');
        $ok = $doc->loadHTML('<?xml encoding="UTF-8"><div id="pindoor-raiz">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($anterior);
        if (! $ok) {
            return null;
        }

        $excluir = implode(' or ', array_map(fn ($tag) => "ancestor::$tag", self::SIN_ENLACES));
        $nodos = iterator_to_array((new DOMXPath($doc))->query("//text()[not($excluir)]"));

        $encontrados = [];
        $enlaces = 0;

        foreach ($nodos as $nodo) {
            $texto = $nodo->nodeValue;
            if (! preg_match_all($regex, $texto, $coincidencias, PREG_OFFSET_CAPTURE)) {
                continue;
            }

            $piezas = [];
            $cursor = 0;
            foreach ($coincidencias[0] as [$encontrado, $offset]) {
                $clave = self::clave($encontrado);
                $lugar = $lugares[$clave] ?? null;
                if (! $lugar || isset($encontrados[$clave])) {
                    continue;
                }

                $encontrados[$clave] = ['nombre' => $lugar['nombre'], 'url' => self::urlLugar($lugar['slug'])];
                if ($enlaces >= self::MAX_ENLACES_TEXTO) {
                    continue; // va al recuadro, pero no se enlaza en el texto
                }

                $piezas[] = $doc->createTextNode(substr($texto, $cursor, $offset - $cursor));
                $a = $doc->createElement('a');
                $a->setAttribute('href', self::urlLugar($lugar['slug']));
                $a->setAttribute('target', '_blank');
                $a->setAttribute('rel', 'noopener');
                $a->setAttribute('class', 'enlace-pindoor');
                $a->setAttribute('title', $lugar['nombre'] . ' en Pindoor');
                $a->appendChild($doc->createTextNode($encontrado));
                $piezas[] = $a;
                $cursor = $offset + strlen($encontrado);
                $enlaces++;
            }

            if ($piezas) {
                $piezas[] = $doc->createTextNode(substr($texto, $cursor));
                foreach ($piezas as $pieza) {
                    $nodo->parentNode->insertBefore($pieza, $nodo);
                }
                $nodo->parentNode->removeChild($nodo);
            }
        }

        if (! $encontrados) {
            return null;
        }

        $resultado = $html;
        if ($enlaces > 0) {
            $raiz = $doc->getElementById('pindoor-raiz');
            if (! $raiz instanceof DOMElement) {
                return null;
            }
            $resultado = '';
            foreach ($raiz->childNodes as $hijo) {
                $resultado .= $doc->saveHTML($hijo);
            }
        }

        return ['html' => $resultado, 'lugares' => array_slice(array_values($encontrados), 0, self::MAX_RECUADRO)];
    }

    /**
     * Una sola expresión con todos los nombres (los largos primero, para que «Ascensor Reina Victoria»
     * gane sobre «Reina Victoria»). Sin distinguir tildes ni mayúsculas, salvo los nombres de artículo + una
     * palabra («La Industria», «El Lechero»), que solo calzan escritos con mayúscula como nombre propio.
     */
    private static function regex(array $lugares): string
    {
        $alternativas = array_map(function ($l) {
            $palabras = preg_split('/\s+/u', strtr($l['nombre'], self::SIN_TILDE));
            $patron = implode('[\s\x{00A0}]+', array_map(fn ($p) => strtr(preg_quote($p, '/'), self::VOCALES), $palabras));
            $nombrePropio = preg_match('/^(el|la|los|las|lo)\s+\S+$/iu', $l['nombre']);

            return $nombrePropio ? $patron : "(?i:$patron)";
        }, array_values($lugares));

        return '/(?<![\p{L}\p{N}])(?:' . implode('|', $alternativas) . ')(?![\p{L}\p{N}])/u';
    }

    /** «NOCHE DE ROCK EN LA CASA» → «Noche de rock en la casa» (solo si viene todo en mayúsculas). */
    private static function titulo(string $titulo): string
    {
        $titulo = trim($titulo);

        return $titulo !== '' && $titulo === mb_strtoupper($titulo) && preg_match('/\p{L}{4}/u', $titulo)
            ? Str::ucfirst(mb_strtolower($titulo))
            : $titulo;
    }

    /**
     * Trae y procesa un recurso de Pindoor con caché. Si falla, reintenta en 10 minutos y mientras
     * tanto usa la última copia buena (que se guarda sin vencimiento) o una lista vacía.
     */
    private static function remoto(string $clave, string $ruta, int $ttl, callable $procesar): array
    {
        $guardado = Cache::get($clave);
        if (is_array($guardado)) {
            return $guardado;
        }

        $datos = null;
        try {
            $respuesta = Http::timeout(3)->connectTimeout(2)->get(self::url($ruta));
            if ($respuesta->successful()) {
                $datos = $procesar($respuesta->body());
            }
        } catch (\Throwable $e) {
            report($e);
        }

        if (is_array($datos)) {
            Cache::put($clave, $datos, $ttl);
            Cache::forever($clave . '.respaldo', $datos);

            return $datos;
        }

        $respaldo = Cache::get($clave . '.respaldo', []);
        Cache::put($clave, $respaldo, 600);

        return $respaldo;
    }
}
