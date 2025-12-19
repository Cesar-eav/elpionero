<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function noticias()
    {
        $noticias = Noticia::orderBy('fecha_publicacion', 'desc')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';

        foreach ($noticias as $noticia) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('noticia/' . $noticia->slug) . '</loc>';
            $xml .= '<news:news>';
            $xml .= '<news:publication>';
            $xml .= '<news:name>El Pionero de Valparaíso</news:name>';
            $xml .= '<news:language>es</news:language>';
            $xml .= '</news:publication>';
            $xml .= '<news:publication_date>' . $noticia->fecha_publicacion->toIso8601String() . '</news:publication_date>';
            $xml .= '<news:title>' . htmlspecialchars($noticia->titulo) . '</news:title>';
            $xml .= '</news:news>';
            $xml .= '<lastmod>' . $noticia->updated_at->toIso8601String() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
