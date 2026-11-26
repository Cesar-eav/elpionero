@use('App\Support\PaseoGlosario', 'G')
@use('App\Support\PaseoDocumentos', 'D')
@php
    // Íconos por tipo de documento (trazos de 24 × 24)
    $iconos = [
        'Planos' => '<path d="M3 6l6-3 6 3 6-3v15l-6 3-6-3-6 3z"/><path d="M9 3v15M15 6v15"/>',
        'Oficio' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'Convenio' => '<path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6"/><path d="M8 14l3 3 5-6"/>',
        'Acta de entrega' => '<circle cx="8" cy="15" r="4"/><path d="M10.8 12.2L20 3M16 7l3 3M14 9l2 2"/>',
        'Acta' => '<path d="M9 6h11M9 12h11M9 18h11"/><path d="M4 6h1M4 12h1M4 18h1"/>',
        'Solicitud' => '<path d="M4 5h16v11H9l-5 4z"/><path d="M10 9.2a2 2 0 1 1 2.9 1.8c-.6.3-.9.7-.9 1.2"/><path d="M12 14.2v.01"/>',
    ];
    $colores = ['paseo' => 'var(--rust)', 'reglas' => 'var(--blue)', 'decisiones' => 'var(--teal)', 'bodega' => 'var(--ink-2)', 'preguntas' => 'var(--yellow)'];

    // Tramos del Paseo según los planos as built: metros desde el lado de Caleta Portales
    $tramos = [
        ['n' => 1, 'nombre' => 'Transición Portales', 'desde' => 382.65, 'hasta' => 476.65, 'lam' => 'C1'],
        ['n' => 2, 'nombre' => 'Vía elevada', 'desde' => 476.65, 'hasta' => 717.3, 'lam' => 'D1'],
        ['n' => 3, 'nombre' => 'Tiburón Contreras (pasarela)', 'desde' => 717.3, 'hasta' => 893.7, 'lam' => 'E1'],
        ['n' => 4, 'nombre' => 'Miradores', 'desde' => 893.7, 'hasta' => 979.2, 'lam' => 'F1'],
        ['n' => 5, 'nombre' => 'Entre cauces', 'desde' => 979.2, 'hasta' => 1103.0, 'lam' => 'G1'],
        ['n' => 6, 'nombre' => 'Playa El Rostro', 'desde' => 1103.0, 'hasta' => 1420.0, 'lam' => 'H1'],
    ];
    // En el mapa el Paseo mide 530 px para 1.722 m: el metro 0 queda en x = 960 (Caleta Portales)
    $x = fn ($m) => round(960 - $m * 530 / 1722, 1);
    $laminas = collect(D::porId('as-built')['laminas'])->keyBy('codigo');
    $metros = fn ($m) => number_format($m, 0, ',', '.');

    $actores = [
        ['id' => 'epv', 'sigla' => 'EPV', 'parque' => 'Dueña del terreno. Recibe las utilidades del parque.', 'paseo' => 'En 2004 acordó pedir el sector del Paseo en comodato. No sabemos si hoy lo tiene.'],
        ['id' => 'minvu', 'sigla' => 'MINVU y SEREMI', 'parque' => 'Firmó el convenio y vota en el Comité. La SEREMI presidió la sesión de 2026 y tramita los cambios de normas.', 'paseo' => null],
        ['id' => 'serviu', 'sigla' => 'SERVIU', 'parque' => 'Construye el parque y responde por su cuidado, mantención y seguridad.', 'paseo' => null],
        ['id' => 'municipio', 'sigla' => 'Municipio', 'parque' => 'Gestor territorial propuesto. En la Rotonda, solo aseo y seguridad, sin presupuesto propio.', 'paseo' => 'Según la DOP, lo tiene bajo tuición. Lo cerró en julio de 2026.'],
        ['id' => 'dop', 'sigla' => 'MOP – DOP', 'parque' => null, 'paseo' => 'Lo construyó (2005–2008), lo reparó (2016–2017 y 2023) y calificó su daño en 2026.'],
        ['id' => 'bienes-nacionales', 'sigla' => 'Bienes Nacionales', 'parque' => null, 'paseo' => 'Lleva el registro de los terrenos fiscales del borde. Nos respondió con el Ord. N° 4614.'],
        ['id' => 'subsecretaria-ffaa', 'sigla' => 'Subsecretaría FF.AA.', 'parque' => null, 'paseo' => 'Administra la franja de 80 metros donde está el Paseo y entrega sus concesiones marítimas.'],
        ['id' => 'senapred', 'sigla' => 'SENAPRED', 'parque' => null, 'paseo' => 'Coordina las alertas por marejadas y reunió los reportes de daños de julio de 2026.'],
        ['id' => 'directemar', 'sigla' => 'DIRECTEMAR', 'parque' => null, 'paseo' => 'Pronostica el oleaje y avisa las marejadas en toda la costa.'],
        ['id' => 'cmn', 'sigla' => 'CMN', 'parque' => 'Supervisa la arqueología del Paño Barón.', 'paseo' => null],
        ['id' => 'efe', 'sigla' => 'EFE', 'parque' => 'Los accesos al parque deben cruzar su vía férrea.', 'paseo' => 'Su vía corre junto al Paseo, separada por un cierre metálico.'],
        ['id' => 'foinsa', 'sigla' => 'FOINSA', 'parque' => 'Estudia cómo financiar la Bodega con inversión privada.', 'paseo' => null],
    ];
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>Lo que dicen los documentos · Cuidemos el Paseo Wheelwright</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo_cuidemoselpaseo.jpeg') }}">
    <meta name="description" content="El convenio del Parque Barón, las actas de su Comité de Gestión, el informe de SENAPRED sobre las marejadas y los planos del Paseo Wheelwright, explicados en lenguaje simple y con un glosario.">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Lo que dicen los documentos · Cuidemos el Paseo Wheelwright">
    <meta property="og:description" content="Documentos oficiales del Paseo Wheelwright y del Parque Barón, explicados para cualquier persona.">
    <meta property="og:url" content="{{ route('paseo.documentos') }}">
    <meta property="og:site_name" content="Cuidemos el Paseo Wheelwright">
    <meta property="og:image" content="{{ asset('cuidemos_el_paseo/drone/banner/fondo-3.webp') }}">
    <meta name="twitter:card" content="summary_large_image">

@include('paseo.partials.base-head')
@include('paseo.partials.lectura')
<style>
/* En 1 minuto */
.minuto { list-style: none; margin: 26px 0 0; padding: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; counter-reset: m; }
.minuto li { counter-increment: m; position: relative; padding-left: clamp(22px, 3.4vw, 30px); }
.minuto li::before { content: ""; position: absolute; inset: 0 auto 0 0; width: 6px; border-radius: 14px 0 0 14px; background: var(--c); }
.minuto .n { font: 900 2.6rem/1 var(--f-display); font-stretch: 112%; color: color-mix(in srgb, var(--c) 78%, var(--ink)); }
.minuto h3 { font-size: clamp(1.15rem, 2.4vw, 1.34rem); font-weight: 800; line-height: 1.15; margin-top: 6px; }
.minuto p { margin-top: 8px; }
.minuto .ir { display: inline-block; margin-top: 10px; font-weight: 700; }
@media (max-width: 760px) { .minuto { grid-template-columns: minmax(0, 1fr); } }

/* Quién es quién */
.actores { list-style: none; margin: 26px 0 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px; }
.actor { padding: 16px 18px; }
.actor h3 { font-size: 1.2rem; font-weight: 800; }
.actor dl { margin: 10px 0 0; display: grid; gap: 8px; }
.actor dt { font: 500 .7rem var(--f-mono); letter-spacing: .07em; text-transform: uppercase; }
.actor dt.pq { color: var(--teal); }
.actor dt.ps { color: var(--blue); }
.actor dd { margin: 2px 0 0; font-size: .96rem; }
.actor .def { font-size: .92rem; color: var(--ink-2); margin-top: 4px; }

/* Mapa */
.mapa svg { min-width: 760px; background: var(--card-solid); }
.mapa-leyenda { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-top: 16px; }
.mapa-leyenda .card { border-top: 6px solid var(--c); }
.mapa-leyenda h3 { font-size: 1.15rem; font-weight: 800; }
.mapa-leyenda ul { margin: 10px 0 0; padding-left: 1.1em; display: grid; gap: 6px; }
.tramos { list-style: none; margin: 16px 0 0; padding: 0; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px 16px; }
.tramos li { display: grid; grid-template-columns: 28px minmax(0, 1fr); gap: 8px; align-items: start; font-size: .95rem; }
.tramos .num { width: 26px; height: 26px; border-radius: 50%; display: grid; place-items: center; font: 800 .85rem var(--f-display); background: var(--btn-bg); color: var(--btn-fg); }
.tramos small { display: block; color: var(--ink-2); }
@media (max-width: 820px) { .mapa-leyenda { grid-template-columns: minmax(0, 1fr); } .tramos { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 520px) { .tramos { grid-template-columns: minmax(0, 1fr); } }

/* Línea de tiempo de los documentos */
.tl { list-style: none; margin: 26px 0 0; padding: 0; position: relative; }
.tl::before { content: ""; position: absolute; left: 6px; top: 6px; bottom: 6px; width: 8px; border-radius: 4px; background: var(--lane); }
.tl::after { content: ""; position: absolute; left: 9px; top: 12px; bottom: 12px; width: 2px; background: repeating-linear-gradient(180deg, var(--lane-dash) 0 9px, transparent 9px 18px); }
.tl li { position: relative; padding: 0 0 18px 40px; }
.tl li::before { content: ""; position: absolute; left: 1px; top: 4px; width: 18px; height: 18px; border-radius: 50%; background: var(--card-solid); border: 4px solid var(--c, var(--blue)); z-index: 1; }
.tl time { font: 500 .8rem var(--f-mono); letter-spacing: .05em; text-transform: uppercase; color: var(--ink-2); }
.tl p { margin-top: 2px; font-weight: 600; max-width: 70ch; }
.tl a { font-size: .92rem; }

/* Fichas de documentos */
.carpeta { margin-top: 38px; scroll-margin-top: 80px; }
.carpeta-h { display: flex; align-items: baseline; flex-wrap: wrap; gap: 4px 14px; border-bottom: 3px solid var(--c); padding-bottom: 8px; }
.carpeta-h h3 { font-size: clamp(1.35rem, 3vw, 1.7rem); font-weight: 800; font-stretch: 104%; }
.carpeta-h p { color: var(--ink-2); }
.docs { display: grid; gap: 16px; margin-top: 16px; }
.doc { scroll-margin-top: 80px; position: relative; }
.doc-h { display: grid; gap: 6px; }
.doc-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 12px; }
.doc-tipo {
  display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px;
  font: 600 .82rem var(--f-body); background: color-mix(in srgb, var(--c) 16%, var(--card-solid)); color: var(--ink);
  box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--c) 45%, transparent);
}
.doc-tipo svg { width: 16px; height: 16px; }
.doc-meta time { font: 500 .8rem var(--f-mono); letter-spacing: .04em; color: var(--ink-2); text-transform: uppercase; }
.doc h4 { font-family: var(--f-display); font-size: clamp(1.22rem, 2.6vw, 1.45rem); font-weight: 800; line-height: 1.15; margin: 0; }
.doc-quien { font-size: .94rem; color: var(--ink-2); }
.doc-quien b { color: var(--ink); }
.doc-body { display: grid; grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr); gap: clamp(16px, 3vw, 28px); margin-top: 16px; align-items: start; }
.doc h5 { margin: 0 0 6px; font: 700 .78rem/1.2 var(--f-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--ink-2); }
.doc .puntos, .doc .pedido { margin: 6px 0 0; padding-left: 1.2em; display: grid; gap: 8px; }
.doc .bloque + .bloque { margin-top: 16px; }
.doc-f { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--line); }
.doc-origen { font-size: .88rem; color: var(--ink-2); max-width: 60ch; }
.doc-acc { display: flex; flex-wrap: wrap; gap: 8px; }
.pag-lista { margin: 0; padding: 0; list-style: none; display: grid; gap: 6px; }
.pag-lista a { font-weight: 600; }
.pag-lista .p { font: 500 .78rem var(--f-mono); color: var(--ink-2); margin-right: 8px; }
.estado { display: inline-block; padding: 3px 10px; border-radius: 999px; font: 600 .82rem var(--f-body); background: color-mix(in srgb, var(--teal) 18%, var(--card-solid)); color: var(--ink); }
@media (max-width: 820px) { .doc-body { grid-template-columns: minmax(0, 1fr); } }

/* Corte tipo del Paseo */
.corte { margin-top: 18px; }
.corte svg { min-width: 560px; background: color-mix(in srgb, var(--sky-3) 40%, var(--card-solid)); }
.corte-ley { margin: 12px 0 0; padding: 0; list-style: none; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 6px 18px; counter-reset: c; }
.corte-ley li { counter-increment: c; display: grid; grid-template-columns: 26px minmax(0, 1fr); gap: 8px; font-size: .95rem; }
.corte-ley li::before {
  content: counter(c); width: 24px; height: 24px; border-radius: 50%; display: grid; place-items: center;
  font: 800 .8rem var(--f-display); background: var(--blue); color: var(--card-solid);
}
.corte-ley li.dano::before { background: var(--rust); }
.corte-ley li.dano b { color: var(--rust); font-weight: 700; }
.corte-nota { margin-top: 10px; font-size: .9rem; color: var(--ink-2); }
.laminas { margin: 0; display: grid; gap: 12px; }
.laminas dt { font-weight: 700; }
.laminas dd { margin: 4px 0 0; }
.laminas ul { margin: 0; padding: 0; list-style: none; display: grid; gap: 4px; }
.laminas code { font: 600 .82rem var(--f-mono); display: inline-block; min-width: 2.4em; color: var(--blue); }
@media (max-width: 620px) { .corte-ley { grid-template-columns: minmax(0, 1fr); } }

/* Imagen dentro de una ficha */
.doc-img { margin: 18px 0 0; }
.doc-img img { display: block; width: 100%; height: auto; border-radius: 12px; border: 1px solid var(--line); }
.doc-img figcaption { margin-top: 8px; }

/* Cómo pedir información */
.como-pedir { margin-top: 16px; }
.como-pedir ol { margin: 8px 0 0; padding-left: 1.3em; display: grid; gap: 6px; }
</style>
</head>

<body>
<div class="topbar">
  <div class="wrap">
    <a class="brand" href="{{ route('paseo.index') }}"><img src="{{ asset('logo_cuidemoselpaseo.jpeg') }}" alt="Movimiento Cuidemos el Paseo Wheelwright" width="44" height="44"><span class="full">← El paseo</span></a>
    <nav class="topnav" aria-label="Partes de esta página">
      <a href="#resumen">En 1 minuto</a>
      <a href="#actores">Quién es quién</a>
      <a href="#mapa">Mapa</a>
      <a href="#linea">Línea de tiempo</a>
      <a href="#documentos">Documentos</a>
      <a href="#glosario">Glosario</a>
      <a class="otra" href="{{ route('paseo.propuesta') }}">Propuesta →</a>
    </nav>
  </div>
</div>

<header class="hero" id="top" style="--hero: url('{{ asset('cuidemos_el_paseo/drone/banner/fondo-3.webp') }}'); --hero-m: url('{{ asset('cuidemos_el_paseo/drone/banner/fondo-3-movil.webp') }}')">
  <div class="wrap">
    <p class="eyebrow"><b>Documentos</b> · Cuidemos el Paseo Wheelwright</p>
    <h1>Lo que dicen los documentos</h1>
    <p class="lead">El convenio del Parque Barón, las actas de su Comité, el informe de SENAPRED sobre las marejadas y los planos del Paseo. Los leímos completos y aquí los explicamos para cualquier persona, con las citas textuales y su página.</p>
    <ol class="indice">
      <li><a href="#resumen">1 · En 1 minuto</a></li>
      <li><a href="#actores">2 · Quién es quién</a></li>
      <li><a href="#mapa">3 · El mapa</a></li>
      <li><a href="#linea">4 · Línea de tiempo</a></li>
      <li><a href="#documentos">5 · Los documentos</a></li>
    </ol>
  </div>
</header>

<main>
<div class="wrap guia-wrap">
  <div class="card">
    <h2>Cómo leer esta página</h2>
    <ol class="guia">
      <li><b>Empieza por «En 1 minuto»</b>Cuatro ideas que resumen todo. Si solo tienes un minuto, con eso basta.</li>
      <li><b>Cada documento tiene una ficha</b>Qué es, quién lo firma, lo más importante y qué tiene que ver con el Paseo. Las citas van textuales, con su página, y puedes abrir el PDF justo ahí.</li>
      <li><b>Toca las palabras difíciles</b>Las que están <x-paseo.termino id="as-built">subrayadas así</x-paseo.termino> se explican al tocarlas. Todas están también en el glosario, al final.</li>
    </ol>
  </div>
</div>

<section id="resumen">
  <div class="wrap">
    <p class="eyebrow">1 · En 1 minuto</p>
    <h2>Cuatro cosas que dicen los documentos</h2>
    <ol class="minuto">
      <li class="card" style="--c: var(--rust)">
        <span class="n" aria-hidden="true">01</span>
        <h3>El Estado calificó el daño del Paseo como «Muy Grave»</h3>
        <p>En julio de 2026 la <x-paseo.termino id="dop">Dirección de Obras Portuarias</x-paseo.termino> registró <x-paseo.termino id="socavon">socavones</x-paseo.termino> y <x-paseo.termino id="perdida-sustentacion">pérdida de sustentación</x-paseo.termino> del Paseo, «comprometiendo la seguridad de usuarios».</p>
        <a class="ir" href="#doc-oficio-236">Ver el informe ↓</a>
      </li>
      <li class="card" style="--c: var(--blue)">
        <span class="n" aria-hidden="true">02</span>
        <h3>El Parque Barón ya tiene dueño, responsable y reglas</h3>
        <p>La <x-paseo.termino id="epv">Empresa Portuaria</x-paseo.termino> pone el terreno, el <x-paseo.termino id="serviu">SERVIU</x-paseo.termino> construye y mantiene, y un <x-paseo.termino id="comite-gestion">Comité de Gestión</x-paseo.termino> decide. Para 2026–2028 se licita su conservación y seguridad.</p>
        <a class="ir" href="#doc-convenio">Ver el convenio ↓</a>
      </li>
      <li class="card" style="--c: var(--teal)">
        <span class="n" aria-hidden="true">03</span>
        <h3>Parque y Paseo se tocan, pero se cuidan por separado</h3>
        <p>El parque llega hasta la Rotonda y ya cuida su propio paseo costero hasta el Muelle Barón. Desde ahí, el Paseo Wheelwright queda fuera de su <x-paseo.termino id="poligono">polígono</x-paseo.termino> y a cargo del Municipio.</p>
        <a class="ir" href="#mapa">Ver el mapa ↓</a>
      </li>
      <li class="card" style="--c: var(--yellow)">
        <span class="n" aria-hidden="true">04</span>
        <h3>Las reglas se pueden cambiar</h3>
        <p>El Comité puede proponer cambios al convenio, ya conversó sumar el Muelle Barón y prepara un convenio nuevo. Ahí puede entrar el Paseo.</p>
        <a class="ir" href="{{ route('paseo.propuesta') }}">Leer nuestra propuesta →</a>
      </li>
    </ol>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="actores">
  <div class="wrap">
    <p class="eyebrow">2 · Quién es quién</p>
    <h2>Las instituciones que aparecen en los documentos</h2>
    <p class="sec-intro">Son muchas siglas. Esta es la lista corta: qué hace cada una en el Parque Barón y en el Paseo.</p>
    <ul class="actores">
@foreach ($actores as $a)
      <li class="card actor">
        <h3>{{ G::boton($a['id'], $a['sigla']) }}</h3>
        <p class="def">{{ G::porId($a['id'])['corto'] }}</p>
        <dl>
@if ($a['parque'])
          <div><dt class="pq">En el parque</dt><dd>{{ $a['parque'] }}</dd></div>
@endif
@if ($a['paseo'])
          <div><dt class="ps">En el Paseo</dt><dd>{{ $a['paseo'] }}</dd></div>
@endif
        </dl>
      </li>
@endforeach
    </ul>
  </div>
</section>

<section id="mapa">
  <div class="wrap">
    <p class="eyebrow">3 · El mapa</p>
    <h2>Un solo borde, dos administraciones</h2>
    <p class="sec-intro">El Parque Barón y el Paseo Wheelwright forman un mismo frente al mar, de la avenida Francia a Caleta Portales. Pero el parque está dentro del <x-paseo.termino id="poligono">polígono</x-paseo.termino> del convenio y el Paseo no.</p>

    <figure class="mapa" style="margin: 0">
      <div class="diag-scroll">
        <svg viewBox="0 0 1000 345" role="img" aria-labelledby="mapa-t mapa-d">
          <title id="mapa-t">Esquema del borde costero entre el Parque Barón y Caleta Portales</title>
          <desc id="mapa-d">A la izquierda, el Parque Barón con la Bodega Simón Bolívar, el Muelle Barón y la Rotonda, dentro del polígono del convenio. Desde la Rotonda hacia la derecha sigue el Paseo Wheelwright, de unos 1,7 kilómetros, dividido en seis tramos según los planos as built, hasta Caleta Portales. Entre el Paseo y la ciudad corren la vía del tren y la avenida España.</desc>
          <rect x="0" y="0" width="1000" height="152" fill="var(--water)" opacity=".3"/>
          <path d="M20 60q15-8 30 0t30 0M436 118q15-8 30 0t30 0M150 120q15-8 30 0t30 0M890 78q15-8 30 0t30 0" fill="none" stroke="var(--water)" stroke-width="2" opacity=".9"/>
          <rect x="0" y="152" width="1000" height="193" fill="var(--land)" opacity=".45"/>
          <text class="svg-t2" x="36" y="38" font-size="15" font-style="italic">Océano Pacífico</text>

          <!-- Ciudad: vía del tren y avenidas -->
          <path d="M0 296 H1000" stroke="var(--land)" stroke-width="22" opacity=".9"/>
          <path d="M440 226 H1000" stroke="var(--land)" stroke-width="18" opacity=".9"/>
          <path d="M0 270 H380 C420 270 430 188 470 188 H1000" fill="none" stroke="var(--ink-2)" stroke-width="6" opacity=".45"/>
          <path d="M0 270 H380 C420 270 430 188 470 188 H1000" fill="none" stroke="var(--card-solid)" stroke-width="2" stroke-dasharray="3 7"/>
          <text class="svg-t2" x="985" y="207" font-size="12" text-anchor="end">Vía del tren (Merval)</text>
          <text class="svg-t2" x="985" y="246" font-size="12" text-anchor="end">Av. España</text>
          <text class="svg-t2" x="36" y="319" font-size="12">Av. Errázuriz</text>
          <text class="svg-m" x="36" y="338" font-size="12">← Valparaíso, centro</text>
          <text class="svg-m" x="985" y="338" font-size="12" text-anchor="end">Viña del Mar →</text>

          <!-- Parque Barón -->
          <path d="M30 154 H432 V236 Q432 252 416 252 H46 Q30 252 30 236 Z" fill="var(--teal)" fill-opacity=".2" stroke="var(--teal)" stroke-width="2.5" stroke-dasharray="9 5"/>
          <text class="svg-d" x="50" y="184" font-size="22">Parque Barón</text>
          <text class="svg-t2" x="50" y="202" font-size="13">11,8 ha · terreno de la EPV · polígono del convenio</text>
          <rect x="50" y="213" width="150" height="26" rx="3" fill="var(--ink)" fill-opacity=".12" stroke="var(--ink-2)"/>
          <text class="svg-t" x="125" y="230" font-size="12" text-anchor="middle">Bodega Simón Bolívar</text>
          <rect x="322" y="58" width="18" height="96" fill="var(--card-solid)" stroke="var(--ink-2)" stroke-width="1.5"/>
          <text class="svg-t" x="348" y="76" font-size="14">Muelle Barón</text>
          <line x1="340" y1="158" x2="430" y2="158" stroke="var(--teal)" stroke-width="6" stroke-linecap="round"/>
          <circle cx="398" cy="196" r="13" fill="none" stroke="var(--ink-2)" stroke-width="2"/>
          <text class="svg-t" x="398" y="226" font-size="12" text-anchor="middle">Rotonda</text>

          <!-- Paseo Wheelwright: los extremos no cubiertos por los planos van punteados -->
          <text class="svg-d" x="690" y="46" font-size="20" text-anchor="middle">Paseo Wheelwright · ≈1,7 km</text>
          <text class="svg-t2" x="690" y="66" font-size="13" text-anchor="middle">fuera del polígono del parque</text>
          <path d="M{{ $x(1420) }} 112 V104 H{{ $x(382.65) }} V112" fill="none" stroke="var(--ink-2)" stroke-width="1.5"/>
          <text class="svg-t2" x="{{ ($x(1420) + $x(382.65)) / 2 }}" y="97" font-size="12" text-anchor="middle">Planos as built de 2005 (metros 383 a 1.420)</text>
          <line x1="{{ $x(1722) }}" y1="158" x2="{{ $x(1420) }}" y2="158" stroke="var(--blue)" stroke-width="8" stroke-dasharray="6 5" opacity=".7"/>
          <line x1="{{ $x(382.65) }}" y1="158" x2="{{ $x(0) }}" y2="158" stroke="var(--blue)" stroke-width="8" stroke-dasharray="6 5" opacity=".7"/>
@foreach ($tramos as $t)
@php $x1 = $x($t['hasta']); $x2 = $x($t['desde']); $cx = round(($x1 + $x2) / 2, 1); @endphp
          <line x1="{{ $x1 + 1 }}" y1="158" x2="{{ $x2 - 1 }}" y2="158" stroke="{{ $t['n'] % 2 ? 'var(--blue)' : 'var(--teal)' }}" stroke-width="11"/>
          <line x1="{{ $cx }}" y1="139" x2="{{ $cx }}" y2="151" stroke="var(--ink-2)" stroke-width="1.2"/>
          <circle cx="{{ $cx }}" cy="128" r="11" fill="var(--btn-bg)"/>
          <text x="{{ $cx }}" y="132.5" font-size="13" font-weight="800" text-anchor="middle" fill="var(--btn-fg)" font-family="var(--f-display)">{{ $t['n'] }}</text>
@endforeach
          <circle cx="966" cy="158" r="8" fill="var(--ink)"/>
          <text class="svg-t" x="990" y="132" font-size="14" text-anchor="end">Caleta Portales</text>
        </svg>
      </div>
      <p class="diag-hint">Desliza el mapa hacia los lados para verlo completo.</p>
      <figcaption class="src" style="margin-top:8px">Esquema simplificado, no está a escala. Los tramos del Paseo se dibujan según los metros de los <x-paseo.termino id="as-built">planos as built</x-paseo.termino>, que se cuentan desde el lado de Caleta Portales; los tramos punteados de los extremos no aparecen en esos planos.</figcaption>
    </figure>

    <ol class="tramos" aria-label="Tramos del Paseo según los planos">
@foreach ($tramos as $t)
@php $lam = $laminas[$t['lam']]; $u = D::url($lam['archivo']); @endphp
      <li><span class="num" aria-hidden="true">{{ $t['n'] }}</span><span>{{ $t['nombre'] }}<small>Metros {{ $metros($t['desde']) }} a {{ $metros($t['hasta']) }}@if ($u) · <a href="{{ $u }}" target="_blank" rel="noopener noreferrer">lámina {{ $t['lam'] }}</a>@endif</small></span></li>
@endforeach
    </ol>

    <div class="mapa-leyenda">
      <div class="card" style="--c: var(--teal)">
        <h3>Dentro del polígono: Parque Barón</h3>
        <ul>
          <li>Dueña del terreno: la <x-paseo.termino id="epv">EPV</x-paseo.termino>.</li>
          <li>Construye, cuida y mantiene: el <x-paseo.termino id="serviu">SERVIU</x-paseo.termino>, con un contrato de <x-paseo.termino id="ficha-conservacion">conservación</x-paseo.termino> y seguridad para 2026–2028.</li>
          <li>Decide: el <x-paseo.termino id="comite-gestion">Comité de Gestión</x-paseo.termino>.</li>
          <li>Incluye un paseo costero entre la Rotonda y el Muelle Barón, con su muro de defensa.</li>
        </ul>
      </div>
      <div class="card" style="--c: var(--blue)">
        <h3>Fuera del polígono: Paseo Wheelwright</h3>
        <ul>
          <li>Lo construyó y lo ha reparado el <x-paseo.termino id="mop">MOP</x-paseo.termino>, por medio de su <x-paseo.termino id="dop">Dirección de Obras Portuarias</x-paseo.termino>.</li>
          <li>A cargo hoy: el Municipio, bajo <x-paseo.termino id="tuicion">tuición</x-paseo.termino> municipal según la DOP.</li>
          <li>Terreno: <x-paseo.termino id="terreno-playa">terreno de playa</x-paseo.termino> del <x-paseo.termino id="fisco">Fisco</x-paseo.termino>, que no se puede vender. Su uso lo entrega la <x-paseo.termino id="subsecretaria-ffaa">Subsecretaría para las Fuerzas Armadas</x-paseo.termino>; quién tiene hoy la concesión, por aclarar.</li>
          <li>Mantención: sin un contrato permanente conocido.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="linea">
  <div class="wrap">
    <p class="eyebrow">4 · Línea de tiempo</p>
    <h2>Los documentos, en orden</h2>
    <p class="sec-intro">De los planos de 2005 a las respuestas de noviembre de 2026. Cada hito lleva a la ficha de su documento.</p>
    <ol class="tl">
@foreach (D::hitos() as $h)
@php $doc = D::porId($h['doc']); @endphp
      <li style="--c: {{ $colores[$doc['carpeta']] }}">
        <time>{{ $h['etiqueta'] }}</time>
        <p>{{ $h['texto'] }}</p>
        <a href="#doc-{{ $h['doc'] }}">{{ $doc['titulo'] }} ↓</a>
      </li>
@endforeach
    </ol>
  </div>
</section>

<section id="documentos">
  <div class="wrap">
    <p class="eyebrow">5 · Los documentos</p>
    <h2>Uno por uno, explicados</h2>
    <p class="sec-intro">Ordenados en cinco carpetas. Usa los botones para ver solo una.</p>
    <div class="chips" role="group" aria-label="Filtrar por carpeta">
      <button class="chip" type="button" data-f="all" aria-pressed="true">Todas</button>
@foreach ($carpetas as $c)
      <button class="chip" type="button" data-f="{{ $c['id'] }}" aria-pressed="false">{{ $c['titulo'] }}</button>
@endforeach
    </div>

@foreach ($carpetas as $c)
    <div class="carpeta" id="carpeta-{{ $c['id'] }}" data-carpeta="{{ $c['id'] }}" style="--c: {{ $colores[$c['id']] }}">
      <div class="carpeta-h">
        <h3>{{ $c['titulo'] }}</h3>
        <p>{{ $c['intro'] }}</p>
      </div>

@if ($c['id'] === 'preguntas')
      <div class="card como-pedir">
        <h4 style="font-family: var(--f-display); font-size: 1.15rem; margin: 0">¿Cómo se pide información pública?</h4>
        <ol>
          <li>Entra al <a href="https://www.portaltransparencia.cl/" target="_blank" rel="noopener noreferrer">Portal de Transparencia</a> y elige el organismo.</li>
          <li>Escribe qué documentos quieres, de qué fechas y en qué formato.</li>
          <li>El organismo tiene 20 días hábiles para responder, y puede pedir 10 más.</li>
          <li>Si no responde o la respuesta está incompleta, puedes presentar un <x-paseo.termino id="amparo">amparo</x-paseo.termino> dentro de 15 días hábiles.</li>
        </ol>
        <p class="src" style="margin-top:8px">Es un derecho de cualquier persona, por la <x-paseo.termino id="ley-transparencia">Ley 20.285</x-paseo.termino>. No hay que explicar para qué se quiere la información.</p>
      </div>
@endif

      <div class="docs">
@foreach ($c['documentos'] as $d)
@php
    $url = D::url($d['archivo'] ?? null);
    $peso = D::peso($d['archivo'] ?? null);
    $paginas = is_int($d['paginas'] ?? null) ? $d['paginas'] . ' páginas' : ($d['paginas'] ?? null);
@endphp
        <article class="card doc" id="doc-{{ $d['id'] }}">
          <header class="doc-h">
            <div class="doc-meta">
              <span class="doc-tipo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $iconos[$d['tipo']] !!}</svg>{{ $d['tipo'] }}</span>
              <time>{{ $d['fecha'] }}</time>
@isset ($d['estado'])
              <span class="estado">{{ $d['estado'] }}</span>
@endisset
            </div>
            <h4>{{ $d['titulo'] }}</h4>
            <p class="doc-quien"><b>{{ $d['tipo'] === 'Solicitud' ? 'Quién la hizo:' : 'Quién lo firma:' }}</b> {{ $d['emisor'] }}@if ($paginas) · {{ $paginas }}@endif</p>
          </header>

          <div class="doc-body">
            <div>
              <div class="bloque">
                <h5>¿Qué es?</h5>
                <p>{{ G::marcar($d['que_es']) }}</p>
@isset ($d['pedido'])
                <ol class="pedido">
@foreach ($d['pedido'] as $p)
                  <li>{{ G::marcar($p) }}</li>
@endforeach
                </ol>
@endisset
              </div>
@isset ($d['puntos'])
              <div class="bloque">
                <h5>Lo más importante</h5>
                <ul class="puntos">
@foreach ($d['puntos'] as $p)
                  <li>{{ G::marcar($p) }}</li>
@endforeach
                </ul>
              </div>
@endisset
            </div>
            <aside class="para-paseo">
              <h5>¿Qué tiene que ver con el Paseo?</h5>
              <p>{{ G::marcar($d['para_el_paseo']) }}</p>
            </aside>
          </div>

@isset ($d['ojo'])
          <p class="ojo"><b>Ojo:</b> {{ G::marcar($d['ojo']) }}</p>
@endisset

@if (! empty($d['imagen']) && is_file(public_path(D::CARPETA . $d['imagen']['archivo'])))
          <figure class="doc-img">
            <a href="{{ asset(D::CARPETA . $d['imagen']['archivo']) }}" target="_blank" rel="noopener noreferrer"><img src="{{ asset(D::CARPETA . $d['imagen']['archivo']) }}" alt="{{ $d['imagen']['alt'] }}" loading="lazy" width="1600" height="1131"></a>
            <figcaption class="src">{{ $d['imagen']['pie'] }} Toca la imagen para verla en grande.</figcaption>
          </figure>
@endif

@if ($d['id'] === 'as-built')
@php $f1 = D::url($laminas['F1']['archivo']); @endphp
          <figure class="corte" style="margin-inline: 0">
            <h5>Cómo está construido: el corte tipo</h5>
            <div class="diag-scroll">
              <svg viewBox="0 0 760 270" role="img" aria-labelledby="corte-t corte-d">
                <title id="corte-t">Corte tipo del Paseo Wheelwright, del mar a la vía del tren</title>
                <desc id="corte-d">De izquierda a derecha: el mar, el enrocado de rocas grandes, el relleno que sostiene el paseo, la base estabilizada, el radier de hormigón donde se camina con una luminaria, la solerilla de borde, la parrilla metálica sobre la canaleta de aguas lluvia, el cierre metálico y la vía del tren. En rojo, el relleno, el pavimento y la parrilla, que los reportes de julio de 2026 registran como dañados.</desc>
                <path d="M0 150 q20-10 40 0 t40 0 t40 0 t40 0 V270 H0 Z" fill="var(--water)" opacity=".55"/>
                <text class="svg-m" x="34" y="210" font-size="14">MAR</text>
                <rect x="250" y="138" width="370" height="132" fill="var(--sand)" opacity=".75"/>
                <path d="M250 138 H620 V270 H250 Z" fill="none" stroke="var(--rust)" stroke-width="2.5" stroke-dasharray="7 5"/>
                <g fill="var(--wall-2)" stroke="var(--ink-2)" stroke-width="1.3">
                  <ellipse cx="128" cy="248" rx="24" ry="17"/><ellipse cx="172" cy="236" rx="25" ry="18"/>
                  <ellipse cx="150" cy="206" rx="20" ry="15"/><ellipse cx="196" cy="200" rx="23" ry="16"/>
                  <ellipse cx="214" cy="246" rx="22" ry="17"/><ellipse cx="190" cy="168" rx="18" ry="13"/>
                  <ellipse cx="226" cy="174" rx="20" ry="15"/><ellipse cx="230" cy="146" rx="16" ry="12"/>
                  <ellipse cx="252" cy="150" rx="14" ry="11"/>
                </g>
                <rect x="262" y="126" width="280" height="12" fill="var(--ink-2)" opacity=".35"/>
                <rect x="262" y="114" width="280" height="12" fill="var(--wall)" stroke="var(--rust)" stroke-width="2.5"/>
                <rect x="252" y="106" width="10" height="32" fill="var(--wall-2)" stroke="var(--ink-2)"/>
                <path d="M546 114 V142 H580 V114" fill="color-mix(in srgb, var(--water) 30%, transparent)" stroke="var(--ink-2)" stroke-width="3"/>
                <rect x="544" y="110" width="38" height="5" fill="none" stroke="var(--rust)" stroke-width="2.5"/>
                <path d="M549 110v5M554 110v5M559 110v5M564 110v5M569 110v5M574 110v5M579 110v5" stroke="var(--rust)" stroke-width="1.6"/>
                <rect x="582" y="106" width="10" height="32" fill="var(--wall-2)" stroke="var(--ink-2)"/>
                <rect x="604" y="52" width="4" height="62" fill="var(--ink-2)"/>
                <rect x="640" y="52" width="4" height="62" fill="var(--ink-2)"/>
                <path d="M604 58 H644 M604 108 H644 M612 58 v50 M618 58 v50 M624 58 v50 M630 58 v50 M636 58 v50" stroke="var(--ink-2)" stroke-width="1.2"/>
                <path d="M650 132 L664 114 H750 L760 132 Z" fill="var(--ink-2)" opacity=".3"/>
                <rect x="682" y="108" width="9" height="6" fill="var(--ink-2)"/><rect x="722" y="108" width="9" height="6" fill="var(--ink-2)"/>
                <path d="M672 114 H748" stroke="var(--ink-2)" stroke-width="2"/>
                <text class="svg-m" x="706" y="152" font-size="11" text-anchor="middle">VÍA DEL TREN</text>
                <rect x="288" y="30" width="4" height="84" fill="var(--ink-2)"/>
                <path d="M290 34 H314" stroke="var(--ink-2)" stroke-width="3"/>
                <ellipse cx="316" cy="38" rx="9" ry="4" fill="var(--yellow)" stroke="var(--ink-2)"/>
                <g stroke="var(--ink)" stroke-width="3" stroke-linecap="round" fill="none">
                  <circle cx="430" cy="66" r="7" fill="var(--ink)"/><path d="M430 74 V96 M430 96 L421 113 M430 96 L439 113 M430 80 L420 92 M430 80 L441 89"/>
                </g>
                <text class="svg-m" x="486" y="106" font-size="11" text-anchor="middle">PASEO</text>
                <g font-family="var(--f-display)" font-weight="800" font-size="13" text-anchor="middle">
                  <line x1="168" y1="108" x2="196" y2="166" stroke="var(--ink-2)"/><circle cx="168" cy="98" r="12" fill="var(--blue)"/><text x="168" y="102.5" fill="var(--card-solid)">1</text>
                  <circle cx="330" cy="214" r="12" fill="var(--rust)"/><text x="330" y="218.5" fill="var(--card-solid)">2</text>
                  <line x1="506" y1="164" x2="506" y2="134" stroke="var(--ink-2)"/><circle cx="506" cy="176" r="12" fill="var(--blue)"/><text x="506" y="180.5" fill="var(--card-solid)">3</text>
                  <line x1="370" y1="92" x2="370" y2="117" stroke="var(--ink-2)"/><circle cx="370" cy="80" r="12" fill="var(--rust)"/><text x="370" y="84.5" fill="var(--card-solid)">4</text>
                  <line x1="244" y1="96" x2="255" y2="108" stroke="var(--ink-2)"/><circle cx="236" cy="86" r="12" fill="var(--blue)"/><text x="236" y="90.5" fill="var(--card-solid)">5</text>
                  <line x1="563" y1="88" x2="563" y2="109" stroke="var(--ink-2)"/><circle cx="563" cy="76" r="12" fill="var(--rust)"/><text x="563" y="80.5" fill="var(--card-solid)">6</text>
                  <line x1="624" y1="38" x2="624" y2="56" stroke="var(--ink-2)"/><circle cx="624" cy="26" r="12" fill="var(--blue)"/><text x="624" y="30.5" fill="var(--card-solid)">7</text>
                  <line x1="706" y1="88" x2="706" y2="106" stroke="var(--ink-2)"/><circle cx="706" cy="76" r="12" fill="var(--blue)"/><text x="706" y="80.5" fill="var(--card-solid)">8</text>
                </g>
              </svg>
            </div>
            <figcaption>
              <ol class="corte-ley">
                <li><span><x-paseo.termino id="enrocado">Enrocado</x-paseo.termino>: rocas que reciben el golpe de la ola.</span></li>
                <li class="dano"><span><x-paseo.termino id="relleno">Relleno</x-paseo.termino>: el suelo que sostiene todo. <b>Dañado en 2026.</b></span></li>
                <li><span><x-paseo.termino id="base-estabilizada">Base estabilizada</x-paseo.termino>: capa que reparte el peso.</span></li>
                <li class="dano"><span><x-paseo.termino id="radier">Radier</x-paseo.termino> de hormigón: el piso por donde se camina. <b>Pavimento dañado en 2026.</b></span></li>
                <li><span><x-paseo.termino id="solerilla">Solerilla</x-paseo.termino>: el borde que remata el piso.</span></li>
                <li class="dano"><span><x-paseo.termino id="parrilla">Parrilla</x-paseo.termino> sobre la canaleta de aguas lluvia. <b>Dañada en 2026.</b></span></li>
                <li><span>Cierre metálico entre el Paseo y la vía.</span></li>
                <li><span>Vía del tren de <x-paseo.termino id="efe">EFE</x-paseo.termino> (Merval).</span></li>
              </ol>
              <p class="corte-nota">Dibujo simplificado a partir del perfil tipo de la lámina F1 (tramo Miradores); no está a escala. En rojo, lo que los reportes de julio de 2026 registran como dañado: «rellenos, parrillas de acero, pavimento».@if ($f1) <a href="{{ $f1 }}" target="_blank" rel="noopener noreferrer">Ver la lámina F1 original</a>.@endif</p>
            </figcaption>
          </figure>

          <details class="mas">
            <summary>Las 26 láminas, tramo por tramo</summary>
            <dl class="laminas">
@foreach (collect($d['laminas'])->groupBy('tramo') as $tramo => $grupo)
              <div>
                <dt>{{ $tramo }}</dt>
                <dd>
                  <ul>
@foreach ($grupo as $l)
@php $ul = D::url($l['archivo']); @endphp
                    <li><code>{{ $l['codigo'] }}</code> @if ($ul)<a href="{{ $ul }}" target="_blank" rel="noopener noreferrer">{{ $l['titulo'] }}</a>@else{{ $l['titulo'] }}@endif</li>
@endforeach
                  </ul>
                </dd>
              </div>
@endforeach
            </dl>
          </details>
@endif

@if (! empty($d['citas']))
          <details class="mas">
            <summary>Citas textuales ({{ count($d['citas']) }})</summary>
            <div class="citas">
@foreach ($d['citas'] as $cita)
@php $uc = $cita['pagina'] ? D::url($d['archivo'], $cita['pagina']) : null; @endphp
              <figure class="cita">
                <blockquote>{{ $cita['texto'] }}</blockquote>
                <figcaption>
                  <span>{{ $cita['donde'] }}{{ $cita['pagina'] ? ', página ' . $cita['pagina'] : '' }}</span>
@if ($uc)
                  <a href="{{ $uc }}" target="_blank" rel="noopener noreferrer">Abrir el PDF en esa página</a>
@endif
                </figcaption>
              </figure>
@endforeach
            </div>
          </details>
@endif

@if (! empty($d['paginas_clave']) && $url)
          <details class="mas">
            <summary>Páginas clave del documento</summary>
            <ul class="pag-lista">
@foreach ($d['paginas_clave'] as [$p, $t])
              <li><span class="p">pág. {{ $p }}</span><a href="{{ D::url($d['archivo'], $p) }}" target="_blank" rel="noopener noreferrer">{{ $t }}</a></li>
@endforeach
            </ul>
          </details>
@endif

@foreach (['anexos' => 'Anexos', 'tambien' => 'También por separado'] as $clave => $rotulo)
@if (! empty($d[$clave]))
          <details class="mas">
            <summary>{{ $rotulo }} ({{ count($d[$clave]) }})</summary>
            <ul class="pag-lista">
@foreach ($d[$clave] as $an)
@php $ua = D::url($an['archivo']); @endphp
              <li>@if ($ua)<a href="{{ $ua }}" target="_blank" rel="noopener noreferrer">{{ $an['titulo'] }}</a> <span class="src">PDF · {{ D::peso($an['archivo']) }}</span>@else{{ $an['titulo'] }} <span class="src">(disponible pronto)</span>@endif</li>
@endforeach
            </ul>
          </details>
@endif
@endforeach

          <div class="doc-f">
            <p class="doc-origen">{{ $d['origen'] ?? $d['nota'] ?? '' }}</p>
            <div class="doc-acc">
@if ($url)
              <a class="btn" href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $d['archivo_etiqueta'] ?? 'Abrir el documento' }} <small>{{ str_ends_with($d['archivo'], '.zip') ? 'ZIP' : 'PDF' }} · {{ $peso }}</small></a>
@elseif (! empty($d['archivo']))
              <span class="btn off">Documento disponible pronto</span>
@elseif (! empty($d['respuesta']))
              <a class="btn sec" href="#doc-{{ $d['respuesta'] }}">Ver la respuesta ↑</a>
@endif
            </div>
          </div>
        </article>
@endforeach
      </div>
    </div>
@endforeach
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="seguir">
  <div class="wrap">
    <div class="card cierre-card">
      <div>
        <p class="eyebrow">¿Y ahora?</p>
        <p class="big" style="margin-top:8px">Con estos documentos armamos una propuesta: que la institución del Parque Barón se haga cargo también del Paseo.</p>
      </div>
      <div>
        <p>Explicamos por qué, con qué reglas se puede hacer y qué le pedimos al Comité de Gestión.</p>
        <div class="acciones">
          <a class="btn" href="{{ route('paseo.propuesta') }}">Leer la propuesta →</a>
          <button type="button" class="btn sec" data-abrir-contacto>Me sumo</button>
          <button type="button" class="btn sec" data-compartir>Compartir</button>
        </div>
      </div>
    </div>
  </div>
</section>

@include('paseo.partials.glosario')
</main>

<footer>
  <div class="wrap">
    <div class="lane" role="presentation"></div>
    <p><span class="name">Cuidemos el Paseo Wheelwright</span> · Valparaíso · <a href="https://www.instagram.com/cuidemospaseowheelwright/" target="_blank" rel="noopener noreferrer">Instagram @cuidemospaseowheelwright</a></p>
    <p>Resúmenes y explicaciones del movimiento Cuidemos el Paseo Wheelwright. Las citas son textuales y llevan la página del documento original. Actualizado en noviembre de 2026. <a href="{{ route('paseo.index') }}#cronograma">Ver el cronograma del Paseo</a> · <a href="{{ route('paseo.manifiesto') }}">Leer el manifiesto</a>.</p>
  </div>
</footer>

<script>
// Filtro por carpeta
(function () {
  var chips = document.querySelectorAll('#documentos .chip[data-f]');
  var carpetas = document.querySelectorAll('.carpeta[data-carpeta]');
  chips.forEach(function (c) {
    c.addEventListener('click', function () {
      var f = c.getAttribute('data-f');
      chips.forEach(function (x) { x.setAttribute('aria-pressed', x === c ? 'true' : 'false'); });
      carpetas.forEach(function (k) { k.hidden = !(f === 'all' || k.getAttribute('data-carpeta') === f); });
    });
  });
  // Un enlace a una ficha muestra su carpeta aunque haya un filtro activo
  function mostrar() {
    var el = location.hash.length > 1 ? document.getElementById(location.hash.slice(1)) : null;
    var k = el && el.closest('.carpeta');
    if (k && k.hidden) document.querySelector('#documentos .chip[data-f="all"]').click();
    if (k) el.scrollIntoView();
  }
  window.addEventListener('hashchange', mostrar);
})();
</script>
@include('paseo.partials.lectura-scripts')
@include('paseo.partials.contacto-flotante')

</body>

</html>
