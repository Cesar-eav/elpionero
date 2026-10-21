@use('Carbon\Carbon')
@php
    // Fecha corta para las notas: "12 oct 2025 · 16:42"
    $corta = fn (Carbon $f) => str_replace('.', '', $f->isoFormat('D MMM YYYY')) . ' · ' . $f->format('H:i');
    $color = ['01' => 'var(--teal)', '02' => 'var(--blue)', '03' => 'var(--yellow)', '04' => 'var(--rust)', '05' => 'var(--ink-2)'];

    // Regla de tiempo: vista completa 2023–2026 y ampliación de oct 2025 a ene 2026
    $escala = function (string $desde, string $hasta) {
        $a = Carbon::parse($desde);
        $total = $a->diffInDays(Carbon::parse($hasta));
        return fn ($f) => round($a->diffInDays(Carbon::parse($f)) / $total * 100, 2);
    };
    $pos = $escala('2023-01-01', '2027-01-01');
    $zoom = $escala('2025-10-01', '2026-02-01');
    $dias = $todas->groupBy(fn ($f) => $f['fecha']->toDateString())
        ->map(fn ($g, $d) => ['d' => $d, 'n' => $g->count(), 'sn' => $g[0]['sn']]);
    $rangos = collect($salas)->map(fn ($s) => [
        'id' => $s['id'], 'num' => $s['num'],
        'desde' => collect($s['fotos'])->min('fecha')->toDateString(),
        'hasta' => collect($s['fotos'])->max('fecha')->toDateString(),
    ]);
    // Hitos tomados del cronograma de la página principal; 'fila' alterna las etiquetas para que no choquen
    $hitos = [
        ['d' => '2023-02-01', 't' => 'Conservación MOP', 'fila' => 1],
        ['d' => '2024-12-15', 'h' => '2025-01-06', 't' => 'Marejadas anormales', 'fila' => 2],
        ['d' => '2025-11-15', 't' => 'Intervención de 580 m', 'fila' => 1],
        ['d' => '2026-01-10', 't' => 'Especial El Pionero', 'fila' => 2],
        ['d' => '2026-07-26', 't' => 'Cierre del paseo', 'fila' => 1],
        ['d' => '2026-10-21', 't' => 'Hoy', 'fila' => 2, 'fin' => true],
    ];
    // Datos que recorre el visor, en orden cronológico
    $visor = $todas->map(fn ($f) => ['src' => $f['src2'], 't' => $f['t'], 'd' => $f['d'], 'f' => $corta($f['fecha']), 's' => $f['sala']])->values();
    $portada = $todas->firstWhere('f', 'Antes de la intervencion de noviembre 2025/IMG_20251005_134449258_HDR.jpg');
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>Galería · Cuidemos el Paseo Wheelwright</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo_cuidemoselpaseo.jpeg') }}">
    <meta name="description" content="{{ $todas->count() }} fotografías del Paseo Wheelwright de Valparaíso entre marzo de 2023 y octubre de 2026, ordenadas por etapas: antes, durante y después de la intervención de noviembre de 2025, y con el paseo cerrado.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="El paseo, foto a foto · Cuidemos el Paseo Wheelwright">
    <meta property="og:description" content="Bitácora fotográfica del Paseo Wheelwright, de 2023 a 2026.">
    <meta property="og:url" content="{{ route('paseo.galeria') }}">
    <meta property="og:site_name" content="Cuidemos el Paseo Wheelwright">
    <meta property="og:image" content="{{ $portada['src2'] }}">
    <meta name="twitter:card" content="summary_large_image">

@include('paseo.partials.base-head')
<style>
/* Concept: una bitácora de recorrido. Papel claro, etapas con nota de cuaderno y la fecha como protagonista,
   en tipografía grande; el amarillo de la ciclovía queda para el margen de la nota y los divisores. */
body { background: var(--wall); }
section { padding-block: clamp(34px, 6vw, 72px); }

/* Portada de la exposición */
.expo { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.25fr); gap: clamp(22px, 4vw, 56px); align-items: end; padding-block: clamp(28px, 6vw, 72px) clamp(24px, 4vw, 44px); }
.expo h1 { font-size: clamp(2.6rem, 8vw, 5.4rem); font-weight: 800; font-stretch: 112%; line-height: .94; letter-spacing: -.015em; margin-top: 14px; }
.expo .sub { font-family: var(--f-mono); font-size: .92rem; margin-top: 18px; color: var(--ink-2); }
.expo .intro { margin-top: 18px; max-width: 46ch; }
.expo-img { margin: 0; min-width: 0; }
.expo-img img { display: block; width: 100%; height: auto; aspect-ratio: 4 / 3; object-fit: cover; }
.expo-img figcaption { font-size: .86rem; color: var(--ink-2); margin-top: 8px; }
.recorrido { list-style: none; margin: 26px 0 0; padding: 0; border-top: 1px solid var(--line); }
.recorrido li { border-bottom: 1px solid var(--line); }
.recorrido a { display: grid; grid-template-columns: 72px minmax(0, 1fr) auto; gap: 12px; align-items: baseline; padding: 10px 2px; color: var(--ink); text-decoration: none; }
.recorrido a:hover .r-t { text-decoration: underline; }
.r-n { font-family: var(--f-mono); font-weight: 500; color: var(--ink-2); }
.r-t { font-weight: 700; }
.r-c { font-family: var(--f-mono); font-size: .82rem; color: var(--ink-2); white-space: nowrap; }

/* Regla de tiempo */
.regla-head { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 6px 20px; align-items: baseline; }
.regla-head h2 { font-size: clamp(1.4rem, 3.4vw, 2rem); font-weight: 800; font-stretch: 105%; }
.regla-scroll { overflow-x: auto; margin-top: 18px; padding-bottom: 6px; }
.regla { position: relative; min-width: 780px; padding-inline: 4px; }
.eje { position: relative; border-bottom: 2px solid var(--ink); }
.eje.vista { height: 56px; }
.eje.zoom { height: 112px; margin-top: 74px; }
.barra {
  position: absolute; bottom: 0; width: 6px; margin-left: -3px; height: calc(var(--n) * var(--u));
  background: var(--c); border-radius: 2px 2px 0 0;
}
.vista .barra { --u: 3px; }
.zoom .barra { --u: 5.5px; width: 10px; margin-left: -5px; }
.barra span { position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); padding-bottom: 2px; font-family: var(--f-mono); font-size: .72rem; color: var(--ink-2); }
.ventana { position: absolute; top: 0; bottom: -2px; border: 1.5px dashed var(--ink-2); border-bottom: 0; border-radius: 6px 6px 0 0; }
.ventana::after { content: "ampliado abajo ↓"; position: absolute; left: 50%; top: calc(100% + 30px); transform: translateX(-50%); font-family: var(--f-mono); font-size: .72rem; color: var(--ink-2); white-space: nowrap; }
.marcas { position: relative; height: 22px; }
.marcas span { position: absolute; top: 4px; font-family: var(--f-mono); font-size: .76rem; color: var(--ink-2); padding-left: 5px; border-left: 1px solid var(--line); line-height: 1.2; }
.bandas { position: relative; height: 30px; margin-top: 4px; }
.banda {
  position: absolute; top: 0; height: 26px; min-width: 30px; border-radius: 6px; display: grid; place-items: center;
  background: var(--c); color: var(--btn-fg); font-family: var(--f-mono); font-weight: 500; font-size: .8rem; text-decoration: none;
}
.banda[data-sn="03"] { color: var(--on-yellow); }
.banda:hover { outline: 2px solid var(--ink); outline-offset: 1px; }
.hitos { position: relative; height: 62px; margin-top: 6px; }
.hito { position: absolute; font-size: .8rem; line-height: 1.2; white-space: nowrap; padding-left: 12px; color: var(--ink); }
.hito::before { content: ""; position: absolute; left: -4px; top: 3px; width: 9px; height: 9px; border-radius: 50%; background: var(--card-solid); border: 2px solid var(--ink); }
.hito.rango::after { content: ""; position: absolute; left: 0; top: -2px; width: var(--w); height: 3px; background: var(--ink); }
.hito[data-fila="2"] { top: 30px; }
.hito.fin { padding-left: 0; padding-right: 12px; transform: translateX(-100%); }
.hito.fin::before { left: auto; right: -5px; }
.hito-t { display: block; font-family: var(--f-mono); font-size: .72rem; color: var(--ink-2); }
.leyenda { display: flex; flex-wrap: wrap; gap: 6px 18px; margin-top: 14px; font-size: .9rem; color: var(--ink-2); }
.leyenda a { color: var(--ink-2); text-decoration: none; }
.leyenda a:hover { color: var(--ink); text-decoration: underline; }
.leyenda a::before { content: ""; display: inline-block; width: 10px; height: 10px; border-radius: 2px; background: var(--c); margin-right: 6px; vertical-align: -1px; }

/* Etapas */
.etapa { border-top: 1px solid var(--line); }
.etapa-grid { display: grid; grid-template-columns: minmax(0, 300px) minmax(0, 1fr); gap: clamp(22px, 4vw, 52px); align-items: start; }
.nota { position: sticky; top: 92px; padding-left: 18px; border-left: 4px solid var(--yellow); }
/* Fecha de apertura de la etapa: día grande y mes/año apilados */
.fecha { display: flex; align-items: center; gap: 12px; line-height: 1; }
.fecha b { font-family: var(--f-display); font-weight: 800; font-stretch: 108%; font-size: clamp(3.2rem, 7vw, 4.6rem); letter-spacing: -.02em; }
.fecha span { font-family: var(--f-mono); font-size: .86rem; letter-spacing: .08em; text-transform: uppercase; line-height: 1.3; color: var(--ink-2); }
.nota .fecha + .eyebrow { margin-top: 14px; }
.nota h2 { font-size: clamp(1.7rem, 3.6vw, 2.3rem); font-weight: 800; font-stretch: 105%; line-height: 1.05; margin-top: 14px; }
.nota .periodo { font-family: var(--f-mono); font-size: .86rem; color: var(--ink-2); margin-top: 10px; }
.nota .texto { margin-top: 14px; }
.nota .cuenta { margin-top: 14px; font-size: .9rem; color: var(--ink-2); }

.obra { position: relative; display: block; width: 100%; padding: 0; border: 0; background: var(--wall-2); cursor: zoom-in; overflow: hidden; }
.obra img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
.obra:hover img { transform: scale(1.025); }
.obra:focus-visible { outline: 3px solid var(--yellow); outline-offset: 3px; }
@media (prefers-reduced-motion: reduce) { .obra img { transition: none; } .obra:hover img { transform: none; } }

/* Foto clave: foto grande con su nota al costado */
.destacada { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 220px); gap: 22px; align-items: end; margin: 0; }
.destacada.v { grid-template-columns: minmax(0, .8fr) minmax(0, 1fr); }
.destacada .obra img { height: auto; max-height: 78vh; object-fit: contain; }
.pie-nota { font-size: .95rem; }
.pie-nota .eyebrow { display: block; margin-bottom: 8px; }
.pie-nota strong { display: block; font-family: var(--f-display); font-size: 1.2rem; line-height: 1.2; }
.pie-nota time { display: block; font-family: var(--f-mono); font-size: .82rem; color: var(--ink-2); margin-top: 6px; }
.pie-nota p { margin-top: 10px; color: var(--ink-2); }

/* Díptico: mismo lugar, otra fecha */
.diptico { margin-top: clamp(30px, 5vw, 52px); padding: clamp(16px, 3vw, 26px); background: var(--card-solid); border: 1px solid var(--line); }
.diptico h3 { font-size: clamp(1.25rem, 2.6vw, 1.55rem); font-weight: 800; font-stretch: 105%; line-height: 1.15; }
.diptico > p { margin-top: 8px; color: var(--ink-2); max-width: 62ch; }
.par { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px; }
.par figure { margin: 0; min-width: 0; }
.par .obra { aspect-ratio: 4 / 3; }
.par figcaption { margin-top: 8px; font-family: var(--f-mono); font-size: .84rem; }
.par figcaption b { font-family: var(--f-display); font-size: 1rem; margin-right: 6px; }

/* Mosaico en filas finas: horizontales de 2 filas, verticales de 3; algunas horizontales ocupan dos columnas */
.mosaico {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 210px), 1fr));
  grid-auto-rows: 112px; grid-auto-flow: dense; gap: 18px 16px; margin-top: clamp(30px, 5vw, 52px);
}
.mosaico figure { margin: 0; min-width: 0; display: flex; flex-direction: column; }
.mosaico .obra { flex: 1; min-height: 0; }
.mosaico figure { grid-row: span 2; }
.mosaico .v, .mosaico .xl { grid-row: span 3; }
.mosaico .xl { grid-column: span 2; }
.mosaico figcaption { padding-top: 8px; font-size: .88rem; line-height: 1.25; min-height: 52px; }
.mosaico figcaption span { display: block; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.mosaico figcaption time { display: block; font-family: var(--f-mono); font-size: .76rem; color: var(--ink-2); margin-top: 3px; }
.dia { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 12px; border-bottom: 2px solid var(--ink); padding-bottom: 6px; margin-top: clamp(34px, 5vw, 56px); }
.dia + .mosaico { margin-top: 18px; }
.dia strong { font-family: var(--f-display); font-size: 1.15rem; }
.dia span { font-family: var(--f-mono); font-size: .8rem; color: var(--ink-2); }

/* Cierre */
.acciones { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
.btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px; background: var(--btn-bg); color: var(--btn-fg); text-decoration: none; font-weight: 700; }
.btn.sec { background: transparent; color: var(--ink); border: 1px solid var(--line); }

/* Visor */
.visor { width: 100%; max-width: none; height: 100%; max-height: none; margin: 0; padding: 0; border: 0; background: rgba(6, 14, 24, .96); color: #EEF4FA; }
.visor::backdrop { background: rgba(6, 14, 24, .9); }
.visor[open] { display: grid; grid-template-rows: auto minmax(0, 1fr) auto; }
.v-top { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 12px max(16px, env(safe-area-inset-left, 0px)); font-family: var(--f-mono); font-size: .86rem; }
.v-img { display: grid; place-items: center; min-height: 0; padding-inline: 56px; }
.v-img img { max-width: 100%; max-height: 100%; object-fit: contain; }
.v-pie { padding: 12px max(16px, env(safe-area-inset-left, 0px)) max(18px, env(safe-area-inset-bottom, 0px)); max-width: 820px; margin-inline: auto; width: 100%; }
.v-pie strong { display: block; font-family: var(--f-display); font-size: 1.2rem; }
.v-pie time { font-family: var(--f-mono); font-size: .84rem; color: #A9C4DA; }
.v-pie p { margin-top: 6px; color: #D3E2EE; }
.v-btn { font: inherit; background: rgba(255, 255, 255, .1); color: #fff; border: 0; border-radius: 999px; min-width: 44px; height: 44px; padding: 0 14px; cursor: pointer; }
.v-btn:hover { background: rgba(255, 255, 255, .2); }
.v-nav { position: absolute; top: 50%; transform: translateY(-50%); font-size: 1.4rem; }
.v-prev { left: 8px; }
.v-next { right: 8px; }

@media (max-width: 900px) {
  .expo, .etapa-grid { grid-template-columns: minmax(0, 1fr); }
  .nota { position: static; }
  .destacada, .destacada.v { grid-template-columns: minmax(0, 1fr); }
}
@media (max-width: 560px) {
  .mosaico { grid-template-columns: 1fr 1fr; grid-auto-rows: 84px; gap: 14px 10px; }
  .mosaico figcaption { font-size: .82rem; }
  .par { grid-template-columns: minmax(0, 1fr); }
  .v-img { padding-inline: 0; }
  .v-nav { top: auto; bottom: 8px; transform: none; }
}
</style>
</head>

<body>
<div class="topbar">
  <div class="wrap">
    <a class="brand" href="{{ route('paseo.index') }}"><img src="{{ asset('logo_cuidemoselpaseo.jpeg') }}" alt="Movimiento Cuidemos el Paseo Wheelwright" width="44" height="44"><span class="full">← El paseo</span></a>
    <nav class="topnav" aria-label="Etapas">
      <a href="#top">Inicio</a>
@foreach ($salas as $sala)
      <a href="#{{ $sala['id'] }}">{{ $sala['corto'] }}</a>
@endforeach
    </nav>
  </div>
</div>

<main>
<header class="wrap expo" id="top">
  <div>
    <p class="eyebrow">Bitácora fotográfica · Paseo Wheelwright</p>
    <h1>El paseo, foto a foto</h1>
    <p class="sub">{{ $todas->count() }} fotografías · marzo 2023 – octubre 2026</p>
    <p class="intro">Cinco etapas siguen el estado del paseo: las primeras señales de deterioro, el registro de octubre de 2025, la intervención de noviembre, lo que quedó después y el paseo ya cerrado. Toca cualquier foto para verla en grande.</p>
    <ol class="recorrido">
@foreach ($salas as $sala)
      <li><a href="#{{ $sala['id'] }}"><span class="r-n">{{ str_replace('.', '', collect($sala['fotos'])->min('fecha')->locale('es')->isoFormat("MMM 'YY")) }}</span><span class="r-t">{{ $sala['titulo'] }}</span><span class="r-c">{{ count($sala['fotos']) }} fotos</span></a></li>
@endforeach
    </ol>
  </div>
  <figure class="expo-img">
    <button class="obra" type="button" data-i="{{ $portada['i'] }}" aria-label="Ampliar: {{ $portada['t'] }}">
      <img src="{{ $portada['src2'] }}" srcset="{{ $portada['src'] }} 800w, {{ $portada['src2'] }} 1800w" sizes="(max-width: 900px) 100vw, 640px" alt="{{ $portada['d'] }}" width="{{ $portada['w'] }}" height="{{ $portada['h'] }}" fetchpriority="high">
    </button>
    <figcaption>{{ $portada['t'] }} · {{ $corta($portada['fecha']) }}</figcaption>
  </figure>
</header>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section aria-labelledby="regla-t">
  <div class="wrap">
    <div class="regla-head">
      <h2 id="regla-t">Las fotos en el tiempo</h2>
      <p class="eyebrow">Cada barra es un día con fotos</p>
    </div>
    <div class="regla-scroll">
      <div class="regla">
        {{-- Vista completa, 2023 a 2026 --}}
        <div class="eje vista">
          <div class="ventana" style="left: {{ $pos('2025-10-01') }}%; width: {{ $pos('2026-02-01') - $pos('2025-10-01') }}%"></div>
@foreach ($dias as $dia)
          <div class="barra" style="left: {{ $pos($dia['d']) }}%; --n: {{ $dia['n'] }}; --c: {{ $color[$dia['sn']] }}"></div>
@endforeach
        </div>
        <div class="marcas">
@foreach ([2023, 2024, 2025, 2026] as $anio)
          <span style="left: {{ $pos($anio . '-01-01') }}%">{{ $anio }}</span>
@endforeach
        </div>
        <div class="bandas">
          <a class="banda" data-sn="01" href="#{{ $rangos[0]['id'] }}" aria-label="{{ $salas[0]['titulo'] }}" style="left: {{ $pos($rangos[0]['desde']) }}%; width: {{ $pos($rangos[0]['hasta']) - $pos($rangos[0]['desde']) }}%; --c: {{ $color['01'] }}"></a>
@foreach ($rangos->where('num', '05') as $r)
          <a class="banda" data-sn="05" href="#{{ $r['id'] }}" aria-label="{{ $salas[4]['titulo'] }}" style="left: {{ $pos($r['desde']) }}%; --c: {{ $color['05'] }}"></a>
@endforeach
        </div>
        <div class="hitos" aria-label="Hitos del cronograma">
@foreach ($hitos as $h)
          <div class="hito{{ isset($h['h']) ? ' rango' : '' }}{{ isset($h['fin']) ? ' fin' : '' }}" data-fila="{{ $h['fila'] }}" style="left: {{ $pos($h['d']) }}%{{ isset($h['h']) ? '; --w: ' . max(4, ($pos($h['h']) - $pos($h['d'])) * 7.8) . 'px' : '' }}">{{ $h['t'] }}<span class="hito-t">{{ str_replace('.', '', Carbon::parse($h['d'])->locale('es')->isoFormat('MMM YYYY')) }}</span></div>
@endforeach
        </div>

        {{-- Ampliación: octubre 2025 a enero 2026 --}}
        <div class="eje zoom">
@foreach ($dias as $dia)
  @if ($dia['d'] >= '2025-10-01' && $dia['d'] < '2026-02-01')
          <div class="barra" style="left: {{ $zoom($dia['d']) }}%; --n: {{ $dia['n'] }}; --c: {{ $color[$dia['sn']] }}" title="{{ $dia['n'] }} fotos"><span>{{ $dia['n'] }}</span></div>
  @endif
@endforeach
        </div>
        <div class="marcas">
@foreach (['2025-10-01', '2025-11-01', '2025-12-01', '2026-01-01'] as $mes)
          <span style="left: {{ $zoom($mes) }}%">{{ str_replace('.', '', Carbon::parse($mes)->locale('es')->isoFormat('MMM YYYY')) }}</span>
@endforeach
        </div>
        <div class="bandas">
@foreach ($rangos->slice(1)->where('desde', '<', '2026-02-01') as $r)
          <a class="banda" data-sn="{{ $r['num'] }}" href="#{{ $r['id'] }}" aria-label="{{ $salas[(int) $r['num'] - 1]['titulo'] }}" style="left: {{ $zoom($r['desde']) }}%; width: {{ $zoom($r['hasta']) - $zoom($r['desde']) }}%; --c: {{ $color[$r['num']] }}"></a>
@endforeach
        </div>
      </div>
    </div>
    <p class="leyenda">
@foreach ($salas as $sala)
      <a href="#{{ $sala['id'] }}" style="--c: {{ $color[$sala['num']] }}">{{ $sala['titulo'] }}</a>
@endforeach
    </p>
  </div>
</section>

@foreach ($salas as $sala)
  @php
      $dest = collect($sala['fotos'])->firstWhere('dest', true);
      $resto = collect($sala['fotos'])->reject(fn ($f) => $f['f'] === ($dest['f'] ?? null))->values();
      // En las etapas por día, un bloque por cada recorrido
      $grupos = ! empty($sala['por_dia']) ? $resto->groupBy(fn ($f) => $f['fecha']->toDateString()) : collect([$resto]);
      $nh = 0;
  @endphp
<section class="etapa" id="{{ $sala['id'] }}" aria-labelledby="{{ $sala['id'] }}-t">
  <div class="wrap etapa-grid">
    <div class="nota">
      <p class="fecha" aria-hidden="true"><b>{{ $sala['apertura']->day }}</b><span>{{ str_replace('.', '', $sala['apertura']->locale('es')->isoFormat('MMM')) }}<br>{{ $sala['apertura']->year }}</span></p>
      <p class="eyebrow">Etapa</p>
      <h2 id="{{ $sala['id'] }}-t">{{ $sala['titulo'] }}</h2>
      <p class="periodo">{{ $sala['periodo'] }}</p>
      <p class="texto">{{ $sala['texto'] }}</p>
      <p class="cuenta">{{ count($sala['fotos']) }} fotografías</p>
    </div>

    <div>
  @if ($dest)
      <figure class="destacada{{ $dest['h'] > $dest['w'] ? ' v' : '' }}">
        <button class="obra" type="button" data-i="{{ $dest['i'] }}" aria-label="Ampliar: {{ $dest['t'] }}">
          <img src="{{ $dest['src2'] }}" srcset="{{ $dest['src'] }} 800w, {{ $dest['src2'] }} 1800w" sizes="(max-width: 900px) 100vw, 560px" alt="{{ $dest['d'] }}" width="{{ $dest['w'] }}" height="{{ $dest['h'] }}" loading="lazy" decoding="async">
        </button>
        <figcaption class="pie-nota">
          <span class="eyebrow">Foto clave</span>
          <strong>{{ $dest['t'] }}</strong>
          <time datetime="{{ $dest['fecha']->toIso8601String() }}">{{ $corta($dest['fecha']) }}</time>
          <p>{{ $dest['d'] }}</p>
        </figcaption>
      </figure>
  @endif

  @isset($sala['diptico'])
      @php $dp = $sala['diptico']; @endphp
      <div class="diptico">
        <p class="eyebrow">Mismo lugar, otra fecha</p>
        <h3>{{ $dp['titulo'] }}</h3>
        <p>{{ $dp['texto'] }}</p>
        <div class="par">
    @foreach (['Antes' => $dp['a'], 'Después' => $dp['b']] as $rotulo => $f)
          <figure>
            <button class="obra" type="button" data-i="{{ $f['i'] }}" aria-label="Ampliar: {{ $f['t'] }}">
              <img src="{{ $f['src'] }}" srcset="{{ $f['src'] }} 800w, {{ $f['src2'] }} 1800w" sizes="(max-width: 560px) 100vw, 400px" alt="{{ $f['d'] }}" width="{{ $f['w'] }}" height="{{ $f['h'] }}" loading="lazy" decoding="async">
            </button>
            <figcaption><b>{{ $rotulo }}</b>{{ $corta($f['fecha']) }}</figcaption>
          </figure>
    @endforeach
        </div>
      </div>
  @endisset

  @foreach ($grupos as $grupo)
    @if (! empty($sala['por_dia']))
      <h3 class="dia"><strong>{{ ucfirst($grupo[0]['fecha']->isoFormat('dddd D [de] MMMM')) }}</strong><span>{{ $grupo->count() }} {{ $grupo->count() === 1 ? 'foto' : 'fotos' }}</span></h3>
    @endif
      <div class="mosaico">
    @foreach ($grupo as $f)
    @php
        $vertical = $f['h'] > $f['w'];
        // Cada séptima foto horizontal se muestra al doble, para darle ritmo
        $xl = ! $vertical && (++$nh % 7 === 0 || ! empty($sala['anchas']));
    @endphp
        <figure class="{{ $vertical ? 'v' : '' }}{{ $xl ? 'xl' : '' }}">
          <button class="obra" type="button" data-i="{{ $f['i'] }}" aria-label="Ampliar: {{ $f['t'] }}">
            <img src="{{ $f['src'] }}" srcset="{{ $f['src'] }} 800w, {{ $f['src2'] }} 1800w" sizes="{{ $xl ? '(max-width: 560px) 100vw, 560px' : '(max-width: 560px) 50vw, 280px' }}" alt="{{ $f['d'] }}" width="{{ $f['w'] }}" height="{{ $f['h'] }}" loading="lazy" decoding="async">
          </button>
          <figcaption><span>{{ $f['t'] }}</span><time datetime="{{ $f['fecha']->toIso8601String() }}">{{ $corta($f['fecha']) }}</time></figcaption>
        </figure>
    @endforeach
      </div>
  @endforeach
    </div>
  </div>
</section>
@endforeach

<section id="epilogo" aria-labelledby="epilogo-t">
  <div class="wrap">
    <h2 id="epilogo-t" style="font-size:clamp(1.4rem,3.4vw,2rem)">¿Tienes fotos del paseo?</h2>
    <p class="sec-intro">De cualquier año. Nos ayudan a completar este archivo.</p>
    <div class="acciones">
      <a class="btn" href="{{ route('paseo.index') }}#cronograma">Ver el cronograma</a>
      <a class="btn sec" href="https://www.instagram.com/cuidemospaseowheelwright/" target="_blank" rel="noopener noreferrer">Enviar fotos por Instagram ↗</a>
    </div>
  </div>
</section>
</main>

<footer>
  <div class="wrap">
    <div class="lane" role="presentation"></div>
    <p><span class="name">Cuidemos el Paseo Wheelwright</span> · Valparaíso · <a href="https://www.instagram.com/cuidemospaseowheelwright/" target="_blank" rel="noopener noreferrer">Instagram @cuidemospaseowheelwright</a></p>
    <p>La fecha y la hora de cada foto vienen del archivo original. Las notas describen lo que se ve en la imagen.</p>
  </div>
</footer>

<dialog class="visor" aria-label="Visor de fotos">
  <div class="v-top">
    <span class="v-cuenta"></span>
    <button class="v-btn v-cerrar" type="button" aria-label="Cerrar">✕ Cerrar</button>
  </div>
  <div class="v-img"><img alt=""></div>
  <div class="v-pie" aria-live="polite">
    <span class="eyebrow v-sala" style="color:#A9C4DA"></span>
    <strong class="v-t"></strong>
    <time class="v-f"></time>
    <p class="v-d"></p>
  </div>
  <button class="v-btn v-nav v-prev" type="button" aria-label="Foto anterior">←</button>
  <button class="v-btn v-nav v-next" type="button" aria-label="Foto siguiente">→</button>
</dialog>

<script>
// Visor: recorre todas las fotos en orden cronológico
(function () {
  var fotos = @json($visor);
  var dlg = document.querySelector('.visor');
  if (!dlg || typeof dlg.showModal !== 'function') return;
  var img = dlg.querySelector('.v-img img');
  var q = function (s) { return dlg.querySelector(s); };
  var i = 0;
  function show(k) {
    i = (k + fotos.length) % fotos.length;
    var f = fotos[i];
    img.src = f.src;
    img.alt = f.d;
    q('.v-t').textContent = f.t;
    q('.v-f').textContent = f.f;
    q('.v-d').textContent = f.d;
    q('.v-sala').textContent = f.s;
    q('.v-cuenta').textContent = 'Foto ' + (i + 1) + ' de ' + fotos.length;
    // Precarga la siguiente para que el cambio sea inmediato
    new Image().src = fotos[(i + 1) % fotos.length].src;
  }
  document.querySelectorAll('.obra[data-i]').forEach(function (b) {
    b.addEventListener('click', function () {
      show(+b.getAttribute('data-i'));
      dlg.showModal();
    });
  });
  q('.v-prev').addEventListener('click', function () { show(i - 1); });
  q('.v-next').addEventListener('click', function () { show(i + 1); });
  q('.v-cerrar').addEventListener('click', function () { dlg.close(); });
  // Clic fuera de la foto y del pie cierra el visor
  dlg.addEventListener('click', function (e) { if (e.target === dlg || e.target === q('.v-img')) dlg.close(); });
  dlg.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft') show(i - 1);
    if (e.key === 'ArrowRight') show(i + 1);
  });
  var x0 = null;
  dlg.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
  dlg.addEventListener('touchend', function (e) {
    if (x0 === null) return;
    var dx = e.changedTouches[0].clientX - x0;
    if (Math.abs(dx) > 50) show(dx < 0 ? i + 1 : i - 1);
    x0 = null;
  });
})();

// Marca en la barra la etapa visible
(function () {
  var nav = document.querySelector('.topnav');
  var links = document.querySelectorAll('.topnav a[href^="#"]');
  if (!('IntersectionObserver' in window)) return;
  var obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (!e.isIntersecting) return;
      links.forEach(function (a) {
        var on = a.getAttribute('href') === '#' + e.target.id;
        a.setAttribute('aria-current', on ? 'true' : 'false');
        if (on && nav.scrollWidth > nav.clientWidth) {
          nav.scrollLeft = a.offsetLeft - (nav.clientWidth - a.offsetWidth) / 2 - nav.offsetLeft;
        }
      });
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  links.forEach(function (a) {
    var s = document.querySelector(a.getAttribute('href'));
    if (s) obs.observe(s);
  });
})();
</script>

@include('paseo.partials.contacto-flotante')

</body>

</html>
