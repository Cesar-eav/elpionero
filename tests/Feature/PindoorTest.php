<?php

namespace Tests\Feature;

use App\Support\Pindoor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PindoorTest extends TestCase
{
    private const LUGARES = [
        ['nombre' => 'Ascensor Reina Victoria', 'slug' => 'ascensor-reina-victoria-12'],
        ['nombre' => 'Reina Victoria', 'slug' => 'reina-victoria-13'],
        ['nombre' => 'Cerro Alegre', 'slug' => 'cerro-alegre-1'],
        ['nombre' => 'Plaza Sotomayor', 'slug' => 'plaza-sotomayor-2'],
        ['nombre' => 'Museo Maritimo Nacional', 'slug' => 'museo-maritimo-nacional-397'], // sin tilde, como en Pindoor
        ['nombre' => 'Puerta China (roja)', 'slug' => 'puerta-china-9'],
        ['nombre' => 'La Industria', 'slug' => 'la-industria-5'],
        ['nombre' => 'Cinzano', 'slug' => 'cinzano-7'],
        ['nombre' => 'Valparaíso', 'slug' => 'valparaiso-8'],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.pindoor.url' => 'https://pindoor.cl']);
        Cache::flush();
    }

    private function conLugares(array $lugares = self::LUGARES): void
    {
        Http::fake(['pindoor.cl/lugares.json' => Http::response($lugares)]);
    }

    public function test_la_brujula_vieja_redirige_301_a_pindoor(): void
    {
        $this->get('/labrujula')->assertStatus(301)->assertRedirect('https://pindoor.cl/');
        $this->get('/atractivos/museo-maritimo-nacional')->assertStatus(301)->assertRedirect('https://pindoor.cl/lugar/museo-maritimo-nacional');
        $this->get('/atractivos/categoria/miradores')->assertStatus(301)->assertRedirect('https://pindoor.cl/atractivos/categoria/miradores');
        $this->get('/atractivos/ciudad/valparaiso')->assertStatus(301)->assertRedirect('https://pindoor.cl/atractivos/ciudad/valparaiso');
        $this->get('/panoramas')->assertStatus(301)->assertRedirect('https://pindoor.cl/panoramas');
    }

    public function test_enlaza_la_primera_mencion_y_prefiere_el_nombre_mas_largo(): void
    {
        $this->conLugares();

        $r = Pindoor::enlazar('<p>Subimos por el ascensor Reina Victoria al cerro Alegre. Después, otra vez el Cerro Alegre.</p>');

        $this->assertStringContainsString('<a href="https://pindoor.cl/lugar/ascensor-reina-victoria-12" target="_blank" rel="noopener" class="enlace-pindoor" title="Ascensor Reina Victoria en Pindoor">ascensor Reina Victoria</a>', $r['html']);
        $this->assertStringContainsString('<a href="https://pindoor.cl/lugar/cerro-alegre-1"', $r['html']);
        $this->assertSame(1, substr_count($r['html'], 'cerro-alegre-1'));
        $this->assertStringNotContainsString('reina-victoria-13', $r['html']);
        $this->assertSame(['Ascensor Reina Victoria', 'Cerro Alegre'], array_column($r['lugares'], 'nombre'));
    }

    public function test_no_toca_enlaces_titulos_ni_atributos_y_respeta_acentos(): void
    {
        $this->conLugares();

        $html = '<h2>Plaza Sotomayor</h2><p><a href="/x">Plaza Sotomayor</a> <img src="cerro-alegre.jpg" alt="Cerro Alegre"> '
            . 'El Museo Marítimo Nacional abre hoy. Alegremente caminamos.</p>';
        $r = Pindoor::enlazar($html);

        $this->assertStringContainsString('<h2>Plaza Sotomayor</h2>', $r['html']);
        $this->assertStringContainsString('<a href="/x">Plaza Sotomayor</a>', $r['html']);
        $this->assertStringContainsString('alt="Cerro Alegre"', $r['html']);
        $this->assertStringNotContainsString('lugar/plaza-sotomayor', $r['html']);
        $this->assertStringNotContainsString('lugar/cerro-alegre', $r['html']);
        $this->assertStringContainsString('>Museo Marítimo Nacional</a> abre hoy', $r['html']);
        $this->assertSame(['Museo Maritimo Nacional'], array_column($r['lugares'], 'nombre'));

        $r = Pindoor::enlazar('<p>Frente a la puerta china&nbsp;del barrio.</p>');
        $this->assertStringContainsString('<a href="https://pindoor.cl/lugar/puerta-china-9"', $r['html']);
        $this->assertStringContainsString(">puerta china</a>\u{00A0}del barrio", $r['html']); // el &nbsp; vuelve como carácter
    }

    public function test_no_enlaza_nombres_genericos_ni_palabras_parciales(): void
    {
        $this->conLugares();

        $html = '<p>En Valparaíso la industria pesquera y el cinzano. Cerro Alegretto no existe.</p>';
        $r = Pindoor::enlazar($html);

        $this->assertSame($html, $r['html']);
        $this->assertSame([], $r['lugares']);

        $r = Pindoor::enlazar('<p>Una noche en La Industria.</p>');
        $this->assertStringContainsString('lugar/la-industria-5', $r['html']);
    }

    public function test_limita_los_enlaces_en_el_texto_pero_los_suma_al_recuadro(): void
    {
        $lugares = array_map(fn ($i) => ['nombre' => "Mirador Número $i", 'slug' => "mirador-$i"], range(1, 8));
        $this->conLugares($lugares);

        $texto = implode(', ', array_map(fn ($i) => "Mirador Número $i", range(1, 8)));
        $r = Pindoor::enlazar("<p>$texto</p>");

        $this->assertSame(Pindoor::MAX_ENLACES_TEXTO, substr_count($r['html'], 'class="enlace-pindoor"'));
        $this->assertCount(Pindoor::MAX_RECUADRO, $r['lugares']);
    }

    public function test_si_pindoor_no_responde_el_html_queda_intacto(): void
    {
        Http::fake(['pindoor.cl/*' => Http::response('error', 500)]);

        $html = '<p>Cerro Alegre</p>';
        $this->assertSame(['html' => $html, 'lugares' => []], Pindoor::enlazar($html));
        $this->assertSame([], Pindoor::panoramas());
    }

    public function test_si_pindoor_falla_usa_la_ultima_copia_buena(): void
    {
        $this->conLugares();
        Pindoor::lugares();

        Cache::forget('pindoor.lugares.v1');
        Http::fake(['pindoor.cl/*' => Http::response('error', 500)]);

        $this->assertCount(count(self::LUGARES), Pindoor::lugares());
    }

    public function test_panoramas_salta_las_entradas_fijas_y_normaliza_mayusculas(): void
    {
        $atom = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <entry><title>Panoramas hoy</title><link rel="alternate" href="https://pindoor.cl/panoramas/hoy"/><summary>x</summary></entry>
  <entry><title>NOCHE DE ROCK EN VALPARAÍSO</title><link rel="alternate" href="https://pindoor.cl/panoramas/noche-de-rock-1079"/><summary>Rock en vivo</summary></entry>
  <entry><title>Feria del libro</title><link rel="alternate" href="https://pindoor.cl/panoramas/feria-del-libro-1080"/></entry>
</feed>
XML;
        Http::fake(['pindoor.cl/panoramas/feed.xml' => Http::response($atom)]);

        $panoramas = Pindoor::panoramas();

        $this->assertCount(2, $panoramas);
        $this->assertSame('Noche de rock en valparaíso', $panoramas[0]['titulo']);
        $this->assertSame('https://pindoor.cl/panoramas/noche-de-rock-1079', $panoramas[0]['url']);
        $this->assertSame('Feria del libro', $panoramas[1]['titulo']);
    }
}
