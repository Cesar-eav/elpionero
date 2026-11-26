<?php

namespace Tests\Feature;

use App\Support\PaseoDocumentos;
use App\Support\PaseoGlosario;
use InvalidArgumentException;
use Tests\TestCase;

class PaseoDocumentosTest extends TestCase
{
    public function test_la_pagina_de_documentos_muestra_cada_ficha(): void
    {
        $respuesta = $this->get(route('paseo.documentos'))->assertOk();

        foreach (PaseoDocumentos::todos() as $d) {
            $respuesta->assertSee('id="doc-' . $d['id'] . '"', false)->assertSee($d['titulo']);
        }
    }

    public function test_la_propuesta_muestra_cada_razon_con_su_principio(): void
    {
        $puntos = require resource_path('data/paseo-propuesta.php');
        $respuesta = $this->get(route('paseo.propuesta'))->assertOk();

        foreach ($puntos as $p) {
            $respuesta->assertSee('id="r-' . $p['id'] . '"', false)
                ->assertSee(route('paseo.manifiesto') . '#p-' . $p['principio'], false);
        }
    }

    public function test_cada_termino_de_las_paginas_tiene_su_entrada_en_el_glosario(): void
    {
        foreach (['paseo.documentos', 'paseo.propuesta'] as $ruta) {
            $html = $this->get(route($ruta))->assertOk()->getContent();
            preg_match_all('/data-term="([^"]+)"/', $html, $m);

            $this->assertNotEmpty($m[1], "La página {$ruta} no tiene términos del glosario");
            foreach (array_unique($m[1]) as $id) {
                $this->assertStringContainsString('id="g-' . $id . '"', $html, "Falta la entrada «{$id}» en el glosario de {$ruta}");
            }
        }
    }

    public function test_las_marcas_de_los_archivos_de_datos_existen_en_el_glosario(): void
    {
        $datos = [
            require resource_path('data/paseo-documentos.php'),
            require resource_path('data/paseo-propuesta.php'),
        ];
        array_walk_recursive($datos, function ($valor) {
            if (! is_string($valor)) {
                return;
            }
            foreach (PaseoGlosario::marcas($valor) as $id) {
                $this->assertTrue(PaseoGlosario::existe($id), "La marca [[{$id}]] no está en paseo-glosario.php");
            }
        });
    }

    public function test_el_glosario_tiene_categorias_validas_y_sin_ids_repetidos(): void
    {
        $terminos = require resource_path('data/paseo-glosario.php');
        $ids = array_column($terminos, 'id');

        $this->assertSame(count($ids), count(array_unique($ids)), 'Hay ids repetidos en paseo-glosario.php');
        foreach ($terminos as $t) {
            $this->assertArrayHasKey($t['categoria'], PaseoGlosario::CATEGORIAS, "Categoría inválida en «{$t['id']}»");
            $this->assertNotEmpty($t['corto'], "«{$t['id']}» no tiene definición corta");
        }
    }

    public function test_las_citas_de_la_propuesta_apuntan_a_documentos_con_archivo(): void
    {
        foreach (require resource_path('data/paseo-propuesta.php') as $p) {
            foreach ($p['citas'] as $c) {
                $doc = PaseoDocumentos::porId($c['doc']);
                $this->assertNotNull($doc, "La razón «{$p['id']}» cita un documento que no existe: {$c['doc']}");
                $this->assertNotEmpty($doc['archivo'], "La razón «{$p['id']}» cita un documento sin PDF: {$c['doc']}");
                $this->assertIsInt($c['pagina']);
            }
        }
    }

    public function test_la_solicitud_con_datos_personales_no_se_enlaza(): void
    {
        $this->get(route('paseo.documentos'))->assertOk()
            ->assertDontSee('Solicitud_AP024T0001549')
            // El Ord. 4614 original trae el correo del solicitante: solo se enlaza la copia con el correo tapado
            ->assertDontSee('ORD_4614.pdf');
        $this->assertSame('ORD_4614-publico.pdf', PaseoDocumentos::porId('ord-4614')['archivo']);
    }

    public function test_la_portada_y_el_manifiesto_enlazan_a_las_paginas_nuevas(): void
    {
        $this->get(route('paseo.index'))->assertOk()
            ->assertSee(route('paseo.documentos'), false)
            ->assertSee(route('paseo.propuesta'), false);

        $this->get(route('paseo.manifiesto'))->assertOk()->assertSee(route('paseo.propuesta'), false);
    }

    public function test_marcar_escapa_el_texto_y_convierte_las_marcas(): void
    {
        $html = PaseoGlosario::marcar('<script>x</script> lo dice la [[epv|Portuaria]] y **no** el [[serviu]]')->toHtml();

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('data-term="epv"', $html);
        $this->assertStringContainsString('>Portuaria</span>', $html);
        $this->assertStringContainsString('<b>no</b>', $html);
        $this->assertStringContainsString('>SERVIU (Servicio de Vivienda y Urbanización)</span>', $html);
    }

    public function test_un_termino_desconocido_falla_en_desarrollo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        PaseoGlosario::boton('no-existe');
    }

    public function test_los_enlaces_a_pdf_codifican_la_ruta_y_omiten_archivos_que_faltan(): void
    {
        $this->assertNull(PaseoDocumentos::url('no-existe.pdf'));
        $this->assertNull(PaseoDocumentos::url(null));

        $archivo = PaseoDocumentos::porId('oficio-236')['archivo'];
        if (is_file(public_path(PaseoDocumentos::CARPETA . $archivo))) {
            $url = PaseoDocumentos::url($archivo, 186);
            $this->assertStringEndsWith('Oficio%20N%C2%B0%20236-1.pdf#page=186', $url);
        }
    }
}
