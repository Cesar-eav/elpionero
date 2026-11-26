@use('App\Support\PaseoGlosario', 'G')
@use('App\Support\PaseoDocumentos', 'D')
@php
    $principios = collect(require resource_path('data/paseo-manifiesto.php'))->keyBy('id');
    $colores = ['var(--blue)', 'var(--teal)', 'var(--rust)'];
    $especial = route('revista.show', 'especial-paseo-wheelwright-entre-el-potencial-y-el-olvido');
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>Un solo borde, un solo responsable · Cuidemos el Paseo Wheelwright</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo_cuidemoselpaseo.jpeg') }}">
    <meta name="description" content="Por qué la institución que administre el Parque Barón debería hacerse cargo también del Paseo Wheelwright, y cómo se puede hacer con las reglas que ya existen. Explicado paso a paso, con citas de los documentos oficiales.">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Un solo borde, un solo responsable · Cuidemos el Paseo Wheelwright">
    <meta property="og:description" content="Ocho razones, con documentos oficiales, para que el Parque Barón se haga cargo del Paseo Wheelwright.">
    <meta property="og:url" content="{{ route('paseo.propuesta') }}">
    <meta property="og:site_name" content="Cuidemos el Paseo Wheelwright">
    <meta property="og:image" content="{{ asset('cuidemos_el_paseo/drone/banner/fondo-1.webp') }}">
    <meta name="twitter:card" content="summary_large_image">

@include('paseo.partials.base-head')
@include('paseo.partials.lectura')
<style>
/* La propuesta en una frase */
.frase { font-family: var(--f-display); font-weight: 800; font-stretch: 104%; font-size: clamp(1.25rem, 2.8vw, 1.7rem); line-height: 1.2; margin-top: 10px; }
.frase-nota { margin-top: 12px; color: var(--ink-2); font-size: .96rem; }

/* El problema: cifras */
.datos { list-style: none; margin: 26px 0 0; padding: 0; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
.dato { border-top: 6px solid var(--c); }
.dato .cifra { font: 900 clamp(1.6rem, 3.4vw, 2.2rem)/1 var(--f-display); font-stretch: 108%; color: var(--c); }
.dato .que { font-weight: 700; margin-top: 4px; }
.dato p { margin-top: 8px; font-size: .96rem; }
.dato .src { margin-top: 10px; }
@media (max-width: 980px) { .datos { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 560px) { .datos { grid-template-columns: minmax(0, 1fr); } }

/* Cómo funciona: diagrama de flujo */
.flujo { display: grid; justify-items: center; gap: 0; margin-top: 26px; }
.nodo { background: var(--card-solid); border: 2px solid var(--c, var(--line)); border-radius: 14px; padding: 14px 18px; width: min(100%, 600px); text-align: center; }
.nodo h3 { font-size: 1.1rem; font-weight: 800; line-height: 1.2; }
.nodo p { font-size: .95rem; color: var(--ink-2); margin-top: 4px; }
.nodo .rot { display: block; font: 500 .7rem var(--f-mono); letter-spacing: .07em; text-transform: uppercase; color: var(--c, var(--ink-2)); margin-bottom: 4px; }
.flecha { width: 3px; height: 26px; background: var(--ink-2); position: relative; margin-bottom: 8px; }
.flecha::after { content: ""; position: absolute; left: 50%; bottom: -8px; transform: translateX(-50%); border: 7px solid transparent; border-top: 9px solid var(--ink-2); border-bottom: 0; }
.ramas { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; width: 100%; }
.ramas .nodo { width: auto; }
.plata { display: grid; grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr) auto minmax(0, 1fr); align-items: center; gap: 8px; width: 100%; margin-top: 18px; }
.plata .nodo { width: auto; border-style: dashed; }
.plata .fl { font-size: 1.6rem; color: var(--ink-2); line-height: 1; }
.plata-t { width: 100%; margin-top: 22px; font: 500 .74rem var(--f-mono); letter-spacing: .07em; text-transform: uppercase; color: var(--ink-2); text-align: center; }
.fuera { margin-top: 22px; width: 100%; border: 2px dashed var(--rust); border-radius: 14px; padding: 14px 18px; background: color-mix(in srgb, var(--rust) 7%, var(--card-solid)); display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 14px; align-items: center; }
.fuera .ic { width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center; background: var(--rust); color: var(--card-solid); font: 900 1.3rem var(--f-display); }
.fuera h3 { font-size: 1.1rem; font-weight: 800; }
@media (max-width: 760px) {
  .ramas, .plata { grid-template-columns: minmax(0, 1fr); }
  .plata .fl { transform: rotate(90deg); justify-self: center; }
}

/* Las razones */
.razones { list-style: none; margin: 30px 0 0; padding: 0; display: grid; gap: 18px; counter-reset: r; }
.razon { position: relative; overflow: hidden; counter-increment: r; background: var(--card-solid); border: 1px solid var(--line); border-radius: 16px; padding: clamp(18px, 3vw, 26px) clamp(18px, 3vw, 28px) clamp(18px, 3vw, 24px) clamp(24px, 3.6vw, 34px); box-shadow: 0 10px 28px -18px rgba(10, 37, 64, .45); scroll-margin-top: 80px; }
.razon::before { content: ""; position: absolute; inset: 0 auto 0 0; width: 6px; background: var(--c); }
.razon::after { content: counter(r, decimal-leading-zero); position: absolute; top: 6px; right: 16px; font: 900 4.2rem/1 var(--f-display); font-stretch: 112%; color: var(--c); opacity: .14; pointer-events: none; }
.razon h3 { font-size: clamp(1.25rem, 2.6vw, 1.5rem); font-weight: 800; font-stretch: 104%; line-height: 1.12; padding-right: 90px; }
.razon .idea { margin-top: 8px; font-size: 1.08rem; }
.razon-body { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: clamp(14px, 3vw, 26px); margin-top: 16px; align-items: start; }
.razon h4 { margin: 0 0 8px; font: 700 .76rem/1.2 var(--f-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--ink-2); }
.razon .cumple { margin-top: 14px; padding-top: 10px; border-top: 1px dashed var(--line); font-size: .94rem; color: var(--ink-2); }
.razon .cumple b { font: 500 .72rem var(--f-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--c); margin-right: 6px; }
@media (max-width: 820px) { .razon-body { grid-template-columns: minmax(0, 1fr); } }
@media (max-width: 560px) { .razon::after { font-size: 2.8rem; } .razon h3 { padding-right: 56px; } }

/* Hoy y con la propuesta */
.comp { width: 100%; margin-top: 24px; border-collapse: separate; border-spacing: 0; background: var(--card-solid); border: 1px solid var(--line); border-radius: 14px; overflow: hidden; }
.comp th, .comp td { padding: 12px 16px; text-align: left; vertical-align: top; border-bottom: 1px solid var(--line); }
.comp tr:last-child th, .comp tr:last-child td { border-bottom: 0; }
.comp thead th { font: 700 .8rem var(--f-mono); letter-spacing: .06em; text-transform: uppercase; background: color-mix(in srgb, var(--ink) 6%, var(--card-solid)); }
.comp thead th.hoy { color: var(--rust); }
.comp thead th.prop { color: var(--teal); }
.comp tbody th { font-family: var(--f-display); font-weight: 800; width: 24%; }
.comp td.prop { background: color-mix(in srgb, var(--teal) 8%, var(--card-solid)); }
@media (max-width: 720px) {
  .comp thead { display: none; }
  .comp, .comp tbody, .comp tr, .comp th, .comp td { display: block; width: auto; }
  .comp tr { border-bottom: 1px solid var(--line); }
  .comp tr:last-child { border-bottom: 0; }
  .comp tbody th, .comp td { border-bottom: 0; }
  .comp tbody th { padding-bottom: 4px; }
  .comp td { padding-top: 6px; }
  .comp td::before { content: attr(data-l); display: block; font: 700 .72rem var(--f-mono); letter-spacing: .06em; text-transform: uppercase; margin-bottom: 2px; }
  .comp td.hoy::before { color: var(--rust); }
  .comp td.prop::before { color: var(--teal); }
}

/* Qué pedimos */
.fases { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-top: 26px; }
.fase { border-top: 6px solid var(--c); }
.fase h3 { font-size: 1.2rem; font-weight: 800; }
.fase .cuando { font: 500 .74rem var(--f-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--ink-2); }
.fase ol { margin: 12px 0 0; padding-left: 1.4em; display: grid; gap: 10px; }
.fase li::marker { font-family: var(--f-display); font-weight: 800; color: var(--c); }
@media (max-width: 900px) { .fases { grid-template-columns: minmax(0, 1fr); } }

/* Preguntas difíciles y dudas abiertas */
.faq { display: grid; gap: 10px; margin-top: 24px; }
.faq details { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 4px 18px; }
.faq summary { cursor: pointer; list-style: none; padding: 12px 30px 12px 0; position: relative; font-family: var(--f-display); font-weight: 800; font-size: 1.1rem; }
.faq summary::-webkit-details-marker { display: none; }
.faq summary::after { content: "+"; position: absolute; right: 0; top: 8px; font: 700 1.5rem var(--f-body); color: var(--blue); }
.faq details[open] summary::after { content: "−"; }
.faq details > div { padding: 0 0 16px; display: grid; gap: 8px; }
.dudas ul { margin: 14px 0 0; padding-left: 1.2em; display: grid; gap: 10px; }
</style>
</head>

<body>
<div class="topbar">
  <div class="wrap">
    <a class="brand" href="{{ route('paseo.index') }}"><img src="{{ asset('logo_cuidemoselpaseo.jpeg') }}" alt="Movimiento Cuidemos el Paseo Wheelwright" width="44" height="44"><span class="full">← El paseo</span></a>
    <nav class="topnav" aria-label="Partes de la propuesta">
      <a href="#problema">El problema</a>
      <a href="#como-funciona">Cómo funciona</a>
      <a href="#razones">8 razones</a>
      <a href="#comparacion">Hoy y mañana</a>
      <a href="#pedimos">Qué pedimos</a>
      <a href="#preguntas">Preguntas</a>
      <a href="#glosario">Glosario</a>
      <a class="otra" href="{{ route('paseo.documentos') }}">Documentos →</a>
    </nav>
  </div>
</div>

<header class="hero" id="top" style="--hero: url('{{ asset('cuidemos_el_paseo/drone/banner/fondo-1.webp') }}'); --hero-m: url('{{ asset('cuidemos_el_paseo/drone/banner/fondo-1-movil.webp') }}')">
  <div class="wrap">
    <p class="eyebrow"><b>Propuesta</b> · Cuidemos el Paseo Wheelwright</p>
    <h1>Un solo borde, un solo responsable</h1>
    <p class="lead">Por qué la institución que administre el Parque Barón debería hacerse cargo también del Paseo Wheelwright, y cómo se puede hacer con las reglas que ya existen.</p>
    <ol class="indice">
      <li><a href="#problema">1 · El problema</a></li>
      <li><a href="#como-funciona">2 · Cómo funciona el parque</a></li>
      <li><a href="#razones">3 · Ocho razones</a></li>
      <li><a href="#pedimos">4 · Qué pedimos</a></li>
      <li><a href="#preguntas">5 · Preguntas difíciles</a></li>
    </ol>
  </div>
</header>

<main>
<div class="wrap guia-wrap">
  <div class="card">
    <p class="eyebrow">La propuesta en una frase</p>
    <p class="frase">El Paseo Wheelwright y el Parque Barón son un mismo frente al mar. Proponemos que el Paseo entre al sistema del parque, con su convenio, su Comité de Gestión y su contrato de conservación y seguridad, y que el Municipio participe como gestor territorial, no como único responsable de su mantención.</p>
    <p class="frase-nota">Cada razón trae la cita textual del documento oficial y su página. Las palabras <x-paseo.termino id="convenio-marco">subrayadas así</x-paseo.termino> se explican al tocarlas. Los documentos completos, explicados, están en <a href="{{ route('paseo.documentos') }}">Lo que dicen los documentos</a>.</p>
  </div>
</div>

<section id="problema">
  <div class="wrap">
    <p class="eyebrow">1 · El problema</p>
    <h2>Un Paseo dañado que nadie termina de asumir</h2>
    <p class="sec-intro">El Paseo une Valparaíso y Viña del Mar por el borde, lejos de los autos. Hoy está cerrado, con daños graves, y su cuidado queda entre instituciones.</p>
    <ul class="datos">
      <li class="card dato" style="--c: var(--rust)">
        <div class="cifra">«Muy Grave»</div>
        <p class="que">El daño de julio de 2026</p>
        <p>Así lo calificó la <x-paseo.termino id="dop">Dirección de Obras Portuarias</x-paseo.termino>: <x-paseo.termino id="socavon">socavones</x-paseo.termino> y <x-paseo.termino id="perdida-sustentacion">pérdida de sustentación</x-paseo.termino> que comprometen la seguridad.</p>
        <p class="src">Fuente: <a href="{{ route('paseo.documentos') }}#doc-oficio-236">Oficio N° 236 de SENAPRED, pág. 186</a></p>
      </li>
      <li class="card dato" style="--c: var(--blue)">
        <div class="cifra">Cerrado</div>
        <p class="que">Desde julio de 2026</p>
        <p>El Municipio lo cerró por riesgo, con rejas en un extremo y el otro abierto, sin aviso.</p>
        <p class="src">Fuente: <a href="{{ route('paseo.index') }}#cronograma">prensa de julio de 2026, en el cronograma</a></p>
      </li>
      <li class="card dato" style="--c: var(--teal)">
        <div class="cifra">720 m</div>
        <p class="que">Sin atender en 2025</p>
        <p>De los 1.300 metros en mal estado, el Municipio y voluntarios intervinieron 580 en noviembre de 2025.</p>
        <p class="src">Fuente: <a href="{{ $especial }}">El Pionero de Valparaíso, enero de 2026</a></p>
      </li>
      <li class="card dato" style="--c: var(--ink-2)">
        <div class="cifra">¿Quién?</div>
        <p class="que">Nadie responde por todo</p>
        <p>La DOP dice que el Paseo está bajo <x-paseo.termino id="tuicion">tuición</x-paseo.termino> municipal; SENAPRED, que los catastros de daños son del Municipio y del MOP.</p>
        <p class="src">Fuentes: El Mercurio de Valparaíso, 5 ene 2025 · <a href="{{ route('paseo.documentos') }}#doc-oficio-236">Oficio N° 236, pág. 1</a></p>
      </li>
    </ul>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="como-funciona">
  <div class="wrap">
    <p class="eyebrow">2 · Cómo funciona el Parque Barón</p>
    <h2>Antes de proponer, entender el sistema</h2>
    <p class="sec-intro">Así lo arma el <x-paseo.termino id="convenio-marco">convenio</x-paseo.termino> de 2019 y así lo están completando las actas del Comité. Léelo de arriba hacia abajo.</p>

    <div class="flujo">
      <div class="nodo" style="--c: var(--blue)">
        <span class="rot">El acuerdo</span>
        <h3>Convenio marco de 2019</h3>
        <p>Lo firman el <x-paseo.termino id="minvu">MINVU</x-paseo.termino>, el <x-paseo.termino id="serviu">SERVIU</x-paseo.termino> y la <x-paseo.termino id="epv">EPV</x-paseo.termino>. Dura 30 años y se renueva solo.</p>
      </div>
      <div class="flecha" aria-hidden="true"></div>
      <div class="nodo" style="--c: var(--teal)">
        <span class="rot">Quién decide</span>
        <h3><x-paseo.termino id="comite-gestion">Comité de Gestión</x-paseo.termino></h3>
        <p>Dos de la EPV, dos del MINVU y uno del Presidente. Decide por mayoría, deja todo en <x-paseo.termino id="acta">actas</x-paseo.termino> y puede proponer cambios al convenio.</p>
      </div>
      <div class="flecha" aria-hidden="true"></div>
      <div class="ramas">
        <div class="nodo" style="--c: var(--blue)">
          <span class="rot">Pone el terreno</span>
          <h3>EPV</h3>
          <p>Sigue siendo dueña. Recibe las <x-paseo.termino id="utilidades">utilidades</x-paseo.termino> que deje el parque.</p>
        </div>
        <div class="nodo" style="--c: var(--blue)">
          <span class="rot">Construye y cuida</span>
          <h3>SERVIU</h3>
          <p>Responde por el cuidado, la mantención y la seguridad. Licita la conservación y seguridad 2026–2028.</p>
        </div>
        <div class="nodo" style="--c: var(--blue)">
          <span class="rot">Anima el lugar</span>
          <h3>Municipio</h3>
          <p><x-paseo.termino id="gestor-territorial">Gestor territorial</x-paseo.termino>: actividades y programas. En la Rotonda, solo aseo y seguridad, sin presupuesto propio.</p>
        </div>
      </div>
      <p class="plata-t">Cómo circula el dinero</p>
      <div class="plata">
        <div class="nodo" style="--c: var(--yellow)"><h3>Concesiones y arriendos</h3><p>Lo que paguen quienes usen espacios del parque.</p></div>
        <span class="fl" aria-hidden="true">→</span>
        <div class="nodo" style="--c: var(--yellow)"><h3>1.° la mantención</h3><p>Primero se paga el contrato de administración y mantención.</p></div>
        <span class="fl" aria-hidden="true">→</span>
        <div class="nodo" style="--c: var(--yellow)"><h3>2.° la EPV</h3><p>Lo que sobra es su <x-paseo.termino id="contraprestacion">contraprestación</x-paseo.termino>.</p></div>
      </div>
      <div class="fuera">
        <span class="ic" aria-hidden="true">!</span>
        <div>
          <h3>Y el Paseo Wheelwright, afuera de todo esto</h3>
          <p>Está fuera del <x-paseo.termino id="poligono">polígono</x-paseo.termino>. Lo construyó el <x-paseo.termino id="mop">MOP</x-paseo.termino>, depende del Municipio y no tiene un contrato de mantención permanente conocido.</p>
        </div>
      </div>
    </div>
    <p class="src" style="margin-top:14px">Fuentes: <a href="{{ route('paseo.documentos') }}#doc-convenio">convenio de 2019, cláusulas quinta a octava</a> · <a href="{{ route('paseo.documentos') }}#doc-acta-2026">acta del Comité de junio de 2026</a>.</p>
  </div>
</section>

<section id="razones">
  <div class="wrap">
    <p class="eyebrow">3 · Ocho razones</p>
    <h2>Por qué el Parque Barón debe hacerse cargo del Paseo</h2>
    <p class="sec-intro">Cada razón tiene tres partes: la idea en una frase, lo que dice el documento oficial y por qué importa. Al final, el principio del manifiesto que cumple.</p>
    <ol class="razones">
@foreach ($puntos as $i => $p)
@php $pr = $principios[$p['principio']] ?? null; @endphp
      <li class="razon" id="r-{{ $p['id'] }}" style="--c: {{ $colores[$i % 3] }}">
        <h3>{{ $p['titulo'] }}</h3>
        <p class="idea">{{ G::marcar($p['idea']) }}</p>
        <div class="razon-body">
          <div>
            <h4>Lo que dice el documento</h4>
            <div class="citas">
@foreach ($p['citas'] as $c)
@php $doc = D::porId($c['doc']); $u = D::url($doc['archivo'] ?? null, $c['pagina']); @endphp
              <figure class="cita">
                <blockquote>{{ $c['texto'] }}</blockquote>
                <figcaption>
                  <a href="{{ route('paseo.documentos') }}#doc-{{ $c['doc'] }}">{{ $doc['titulo'] }}</a>
                  <span>página {{ $c['pagina'] }}@if ($u) · <a href="{{ $u }}" target="_blank" rel="noopener noreferrer">ver en el PDF</a>@endif</span>
                </figcaption>
              </figure>
@endforeach
            </div>
          </div>
          <div class="para-paseo">
            <p class="rot">Por qué importa</p>
            <p>{{ G::marcar($p['por_que']) }}</p>
          </div>
        </div>
@if ($pr)
        <p class="cumple"><b>Principio del manifiesto</b><a href="{{ route('paseo.manifiesto') }}#p-{{ $pr['id'] }}">{{ $pr['titulo'] }}</a></p>
@endif
      </li>
@endforeach
    </ol>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="comparacion">
  <div class="wrap">
    <p class="eyebrow">Hoy y con la propuesta</p>
    <h2>Qué cambiaría</h2>
    <table class="comp">
      <thead>
        <tr><th scope="col">Pregunta</th><th scope="col" class="hoy">Hoy</th><th scope="col" class="prop">Con la propuesta</th></tr>
      </thead>
      <tbody>
        <tr><th scope="row">¿Quién responde por el Paseo?</th><td class="hoy" data-l="Hoy">El Municipio, bajo tuición, sin recursos asegurados.</td><td class="prop" data-l="Con la propuesta">La institución del Parque Barón: un responsable con nombre y metas.</td></tr>
        <tr><th scope="row">¿Quién paga la mantención?</th><td class="hoy" data-l="Hoy">El presupuesto municipal, cuando alcanza.</td><td class="prop" data-l="Con la propuesta">El contrato de <x-paseo.termino id="ficha-conservacion">conservación</x-paseo.termino> y seguridad del parque, ampliado al Paseo.</td></tr>
        <tr><th scope="row">¿Quién decide?</th><td class="hoy" data-l="Hoy">Nadie en particular: el MOP y el Municipio, cada uno por su lado.</td><td class="prop" data-l="Con la propuesta">El Comité de Gestión, con actas públicas.</td></tr>
        <tr><th scope="row">¿Y cuando llega una marejada?</th><td class="hoy" data-l="Hoy">Se cierra con rejas y se espera una obra mayor.</td><td class="prop" data-l="Con la propuesta">Alerta, cierre a tiempo, retiro de lo móvil, reparación inmediata y reapertura.</td></tr>
        <tr><th scope="row">¿Qué hace el Municipio?</th><td class="hoy" data-l="Hoy">Responde por todo, sin presupuesto propio para hacerlo.</td><td class="prop" data-l="Con la propuesta">Gestor territorial: actividades, vecinos y seguridad ciudadana.</td></tr>
        <tr><th scope="row">¿Qué hace la DOP?</th><td class="hoy" data-l="Hoy">Obras grandes cada varios años.</td><td class="prop" data-l="Con la propuesta">Apoyo técnico y obras mayores, con diseños que convivan con el mar.</td></tr>
        <tr><th scope="row">¿Cómo sabemos si funciona?</th><td class="hoy" data-l="Hoy">No hay metas públicas.</td><td class="prop" data-l="Con la propuesta">Metas de limpieza, iluminación y seguridad que cualquiera pueda revisar.</td></tr>
      </tbody>
    </table>
  </div>
</section>

<section id="pedimos">
  <div class="wrap">
    <p class="eyebrow">4 · Qué pedimos</p>
    <h2>Paso a paso, al Comité de Gestión</h2>
    <p class="sec-intro">No pedimos una obra en particular. Pedimos que el Paseo entre al sistema que ya existe y que se cumplan los principios del <a href="{{ route('paseo.manifiesto') }}">manifiesto</a>.</p>
    <div class="fases">
      <div class="card fase" style="--c: var(--rust)">
        <span class="cuando">Ahora, antes de abrir el parque</span>
        <h3>Ponerlo en la mesa</h3>
        <ol>
          <li>Que el <x-paseo.termino id="comite-gestion">Comité de Gestión</x-paseo.termino> ponga el Paseo en su tabla e invite a la Dirección de Obras Portuarias y al Municipio.</li>
          <li>Que se aclare con la <x-paseo.termino id="subsecretaria-ffaa">Subsecretaría para las Fuerzas Armadas</x-paseo.termino> quién tiene hoy la <x-paseo.termino id="concesion-maritima">concesión marítima</x-paseo.termino> de la franja del Paseo y cómo puede pasar al sistema del parque.</li>
        </ol>
      </div>
      <div class="card fase" style="--c: var(--blue)">
        <span class="cuando">En el convenio nuevo</span>
        <h3>Escribirlo en las reglas</h3>
        <ol start="3">
          <li>Incorporar el Paseo al <x-paseo.termino id="poligono">polígono</x-paseo.termino>, como se pensó para el Muelle, o como área de mantención asociada, en el convenio nuevo o con un <x-paseo.termino id="addendum">addendum</x-paseo.termino>.</li>
          <li>Sumarlo a la licitación de conservación y seguridad, con metas públicas de limpieza, iluminación y seguridad.</li>
          <li>Un presupuesto anual de mantención y un fondo de reparación inmediata para después de cada marejada.</li>
        </ol>
      </div>
      <div class="card fase" style="--c: var(--teal)">
        <span class="cuando">Todos los días</span>
        <h3>Cuidarlo de verdad</h3>
        <ol start="6">
          <li>Un protocolo de cierre y reapertura ligado a las <x-paseo.termino id="alerta-temprana">alertas</x-paseo.termino> de marejadas, con aviso en ambos accesos y una ruta alternativa segura.</li>
          <li>Que la DOP siga a cargo de las obras mayores, con diseños <x-paseo.termino id="infraestructura-permeable">permeables</x-paseo.termino> y <x-paseo.termino id="infraestructura-danable">dañables</x-paseo.termino>.</li>
          <li>Que el Municipio participe como gestor territorial: actividades, vecinos y seguridad ciudadana.</li>
          <li>Publicar contratos, gastos y avances, y decidir con quienes usan el Paseo.</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="preguntas">
  <div class="wrap">
    <p class="eyebrow">5 · Preguntas difíciles</p>
    <h2>Lo que nos han preguntado</h2>
    <div class="faq">
      <details>
        <summary>¿Por qué no el Municipio?</summary>
        <div>
          <p>No es un problema de voluntad. El Municipio cuida toda una ciudad con recursos limitados, y en el Comité dejó claro que en la Rotonda del parque solo puede aportar aseo y seguridad, sin presupuesto propio.</p>
          <p>Una obra frente al mar necesita un contrato estable y capacidad técnica. El Municipio sigue siendo clave, pero como <x-paseo.termino id="gestor-territorial">gestor territorial</x-paseo.termino>: el que organiza la vida del lugar.</p>
        </div>
      </details>
      <details>
        <summary>¿No le saldría más caro al parque?</summary>
        <div>
          <p>Mantener cuesta menos que reconstruir. Entre 2016 y 2017 se adjudicaron $926 millones en reparaciones del Paseo, y en 2023 se anunció una conservación de $558 millones.</p>
          <p>Sumar 1,7 km a un contrato de conservación que ya se va a licitar cuesta menos que esperar al próximo daño grave. Ver los montos en el <a href="{{ route('paseo.index') }}#cronograma">cronograma</a>.</p>
        </div>
      </details>
      <details>
        <summary>¿El Paseo dejaría de ser libre y gratuito?</summary>
        <div>
          <p>No debería. El parque está dentro del <x-paseo.termino id="recinto-portuario">recinto portuario</x-paseo.termino> y tendrá horario de funcionamiento, según los talleres de 2024. El Paseo, en cambio, es una vía para ir de Viña del Mar a Valparaíso.</p>
          <p>Cualquier acuerdo debe asegurar que siga abierto todo el día y sin cobro, salvo cierres por marejada. Pedir que lo cuide el parque no es pedir que lo cierre.</p>
        </div>
      </details>
      <details>
        <summary>¿Y si el parque se entrega en concesión a privados?</summary>
        <div>
          <p>El convenio exige que el contrato de administración y mantención se adjudique por <x-paseo.termino id="licitacion-publica">licitación pública</x-paseo.termino>, y el Comité fija los criterios de las <x-paseo.termino id="concesion">concesiones</x-paseo.termino>.</p>
          <p>Si parte del parque se concesiona, el Paseo debe quedar como espacio público, con metas y cuentas públicas.</p>
        </div>
      </details>
      <details>
        <summary>¿Qué pasa con la playa y el tipo de obras?</summary>
        <div>
          <p>Cambiar quién administra no cambia cómo se construye. Por eso también pedimos obras que <a href="{{ route('paseo.manifiesto') }}#p-mar">convivan con el mar</a>: que dejen pasar el agua y se reparen rápido, en vez de muros que devuelven la ola y se llevan la arena.</p>
        </div>
      </details>
      <details>
        <summary>¿Quién es el dueño del Paseo?</summary>
        <div>
          <p>Ahora sabemos más. Según <a href="{{ route('paseo.documentos') }}#doc-ord-4614">Bienes Nacionales</a>, el Paseo está en la franja de <x-paseo.termino id="terreno-playa">terreno de playa</x-paseo.termino> del <x-paseo.termino id="fisco">Fisco</x-paseo.termino>, que no se puede vender y que solo administra la <x-paseo.termino id="subsecretaria-ffaa">Subsecretaría para las Fuerzas Armadas</x-paseo.termino>, mediante <x-paseo.termino id="concesion-maritima">concesiones marítimas</x-paseo.termino>.</p>
          <p>Lo que falta saber es a nombre de quién está la concesión de la franja del Paseo, desde cuándo y por cuánto tiempo. En 2004 la EPV acordó pedir el sector en <x-paseo.termino id="comodato">comodato</x-paseo.termino> por 90 años, y en 2025 la DOP dijo que está bajo tuición municipal. Esa respuesta define por dónde se suma el Paseo al parque.</p>
        </div>
      </details>
    </div>

    <div class="card dudas" style="margin-top: 26px">
      <h3>Lo que todavía no sabemos</h3>
      <ul>
        <li>¿A nombre de quién está la concesión marítima que Bienes Nacionales marca sobre la franja del Paseo, desde cuándo y hasta cuándo? ¿Bajo qué figura lo tiene a cargo el Municipio?</li>
        <li>¿El Comité de Gestión ha tratado la mantención del Paseo? En las actas que recibimos solo aparece como «conexión».</li>
        <li>¿Qué dice el convenio nuevo que se prepara desde 2024? ¿Existe una versión del 29 de abril de 2020, como menciona el acta de entrega?</li>
        <li>¿Quién administrará el parque y con qué presupuesto? Entre los documentos que nos entregó la SEREMI no hay un <x-paseo.termino id="modelo-gestion">modelo de gestión</x-paseo.termino>.</li>
        <li>¿Qué incluye la licitación de conservación y seguridad 2026–2028, y por cuánto?</li>
        <li>¿Qué catastro técnico hizo el MOP del daño de julio de 2026, y cuándo se repara?</li>
      </ul>
      <p class="src" style="margin-top:12px">Las seguiremos preguntando por la <x-paseo.termino id="ley-transparencia">Ley de Transparencia</x-paseo.termino> y publicaremos las respuestas.</p>
    </div>
  </div>
</section>

<section id="sumate">
  <div class="wrap">
    <div class="card cierre-card">
      <div>
        <p class="eyebrow">Súmate</p>
        <p class="big" style="margin-top:8px">El parque se abriría a fines de 2026. Es el momento de que el Paseo entre en la conversación.</p>
      </div>
      <div>
        <p>Si estás de acuerdo, súmate al movimiento o comparte esta propuesta.</p>
        <div class="acciones">
          <button type="button" class="btn" data-abrir-contacto>Me sumo</button>
          <button type="button" class="btn sec" data-compartir>Compartir</button>
          <a class="btn sec" href="{{ route('paseo.documentos') }}">Ver los documentos</a>
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
    <p>Propuesta del movimiento Cuidemos el Paseo Wheelwright, versión de noviembre de 2026. Las citas son textuales y llevan su página; los documentos completos están en <a href="{{ route('paseo.documentos') }}">Lo que dicen los documentos</a>.</p>
  </div>
</footer>

@include('paseo.partials.lectura-scripts')
@include('paseo.partials.contacto-flotante')

</body>

</html>
