<?php

namespace App\Support;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;

/**
 * Glosario de las páginas pedagógicas del Paseo (documentos y propuesta).
 *
 * Los términos están en resources/data/paseo-glosario.php. Se marcan así:
 * - en los archivos de datos: [[id]] o [[id|texto visible]] (y **texto** para negrita), pasando el texto por marcar();
 * - en las vistas: <x-paseo.termino id="...">texto</x-paseo.termino>.
 *
 * Cada término se vuelve un enlace a su entrada del glosario (#g-id). El JS de paseo/partials/glosario lo convierte
 * en un pop-up; sin JS, el enlace lleva al glosario al pie de la página.
 */
class PaseoGlosario
{
    public const CATEGORIAS = [
        'instituciones' => 'Instituciones',
        'tramites' => 'Documentos y trámites',
        'gestion' => 'Gestión, dinero y normas',
        'obras' => 'Obras y mar',
    ];

    private const MARCA = '/\[\[([a-z0-9-]+)(?:\|([^\]]+))?\]\]/';

    private static ?array $terminos = null;

    /** Ids marcados durante el render de la página actual (el glosario al pie los muestra primero). */
    private static array $usados = [];

    /** @return array<string, array{id: string, termino: string, categoria: string, corto: string, largo: string, ejemplo: string}> */
    public static function todos(): array
    {
        if (self::$terminos === null) {
            self::$terminos = [];
            foreach (require resource_path('data/paseo-glosario.php') as $t) {
                self::$terminos[$t['id']] = $t;
            }
        }

        return self::$terminos;
    }

    public static function existe(string $id): bool
    {
        return isset(self::todos()[$id]);
    }

    public static function porId(string $id): ?array
    {
        return self::todos()[$id] ?? null;
    }

    /**
     * Ids de los términos marcados en lo que va del render, y vacía el registro para la próxima página.
     * El glosario se incluye al final de la vista, así que para entonces ya se marcaron todos.
     */
    public static function usadosYReiniciar(): array
    {
        $ids = array_keys(self::$usados);
        self::$usados = [];

        return $ids;
    }

    /**
     * Términos agrupados por categoría (en el orden de CATEGORIAS) y ordenados alfabéticamente dentro de cada una.
     * Con $ids, solo esos términos; con $excluir = true, todos menos esos.
     */
    public static function porCategoria(?array $ids = null, bool $excluir = false): array
    {
        $grupos = array_fill_keys(array_keys(self::CATEGORIAS), []);
        foreach (self::todos() as $t) {
            if ($ids !== null && in_array($t['id'], $ids, true) === $excluir) {
                continue;
            }
            $grupos[$t['categoria']][] = $t;
        }
        foreach ($grupos as &$g) {
            usort($g, fn ($a, $b) => strcoll(self::sinTildes($a['termino']), self::sinTildes($b['termino'])));
        }

        return array_filter($grupos);
    }

    /** Enlace-término que abre el pop-up. Sin texto, muestra el nombre del término. */
    public static function boton(string $id, string|Htmlable|null $texto = null): HtmlString
    {
        $t = self::porId($id);
        $html = $texto instanceof Htmlable ? trim($texto->toHtml()) : e(trim((string) $texto));

        if ($t === null) {
            if (app()->environment('local', 'testing')) {
                throw new InvalidArgumentException("El término «{$id}» no está en resources/data/paseo-glosario.php");
            }

            return new HtmlString($html !== '' ? $html : e($id));
        }

        if ($html === '') {
            $html = e($t['termino']);
        }
        self::$usados[$id] = true;

        // El "?" es un elemento en línea pegado a la última letra: así nunca queda solo al comienzo de otra línea
        return new HtmlString(
            '<a class="g-term" href="#g-' . e($id) . '" data-term="' . e($id) . '" aria-haspopup="dialog" aria-expanded="false">'
            . '<span class="g-t">' . $html . '</span><span class="g-q" aria-hidden="true">?</span></a>'
        );
    }

    /** Escapa el texto y convierte las marcas [[id|texto]] en términos y **texto** en negrita. */
    public static function marcar(?string $texto): HtmlString
    {
        $html = e($texto ?? '');
        $html = preg_replace('/\*\*(.+?)\*\*/u', '<b>$1</b>', $html);
        $html = preg_replace_callback(
            self::MARCA,
            fn ($m) => self::boton($m[1], isset($m[2]) ? new HtmlString($m[2]) : null)->toHtml(),
            $html
        );

        return new HtmlString($html);
    }

    /** Ids marcados con [[...]] en un texto. */
    public static function marcas(string $texto): array
    {
        preg_match_all(self::MARCA, $texto, $m);

        return $m[1];
    }

    /** Datos mínimos para el pop-up (id => término, definición corta, ejemplo). */
    public static function paraPopup(): array
    {
        return array_map(fn ($t) => ['t' => $t['termino'], 'c' => $t['corto'], 'e' => $t['ejemplo'] ?? ''], self::todos());
    }

    private static function sinTildes(string $s): string
    {
        return strtr(mb_strtolower($s), ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n~']);
    }
}
