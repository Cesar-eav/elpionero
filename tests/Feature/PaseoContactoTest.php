<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaseoContactoTest extends TestCase
{
    private int $estadoTelegram = 200;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.telegram.token' => 'TOKEN', 'services.telegram.chat_id' => '123']);
        Http::fake(['api.telegram.org/*' => fn () => Http::response(['ok' => $this->estadoTelegram === 200], $this->estadoTelegram)]);
    }

    private function enviar(array $extra = [])
    {
        return $this->postJson(route('paseo.contacto'), $extra + ['nombre' => 'Ana', 'motivo' => 'unirme']);
    }

    public function test_solo_whatsapp_envia_a_telegram(): void
    {
        $this->enviar(['whatsapp' => '+56911112222'])->assertOk()->assertJson(['ok' => true]);

        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'botTOKEN/sendMessage')
            && $r['chat_id'] === '123'
            && str_contains($r['text'], 'Quiere unirse')
            && str_contains($r['text'], '+56911112222')
            && ! str_contains($r['text'], 'Correo:'));
    }

    public function test_solo_correo_es_valido(): void
    {
        $this->enviar(['correo' => 'ana@example.com'])->assertOk();
        Http::assertSentCount(1);
    }

    public function test_sin_whatsapp_ni_correo_falla(): void
    {
        $this->enviar()->assertStatus(422)->assertJsonValidationErrors(['correo', 'whatsapp']);
        Http::assertNothingSent();
    }

    public function test_honeypot_no_envia(): void
    {
        $this->enviar(['whatsapp' => '1', 'web' => 'spam'])->assertOk();
        Http::assertNothingSent();
    }

    public function test_el_html_se_escapa(): void
    {
        $this->enviar(['whatsapp' => '1', 'nombre' => '<b>Ana</b>']);
        Http::assertSent(fn (Request $r) => str_contains($r['text'], '&lt;b&gt;Ana&lt;/b&gt;'));
    }

    public function test_si_telegram_falla_se_avisa(): void
    {
        $this->estadoTelegram = 400;
        $this->enviar(['whatsapp' => '1'])->assertStatus(502)->assertJson(['ok' => false]);
    }
}
