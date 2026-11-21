<?php

namespace Tests\Feature;

use Tests\TestCase;

class PaseoManifiestoTest extends TestCase
{
    public function test_el_manifiesto_muestra_todos_los_principios(): void
    {
        $principios = require resource_path('data/paseo-manifiesto.php');
        $respuesta = $this->get(route('paseo.manifiesto'))->assertOk();

        foreach ($principios as $p) {
            $respuesta->assertSee($p['titulo'])->assertSee('id="p-' . $p['id'] . '"', false);
        }
    }

    public function test_la_portada_enlaza_a_cada_principio(): void
    {
        $principios = require resource_path('data/paseo-manifiesto.php');
        $respuesta = $this->get(route('paseo.index'))->assertOk();

        foreach ($principios as $p) {
            $respuesta->assertSee(route('paseo.manifiesto') . '#p-' . $p['id'], false);
        }
    }
}
