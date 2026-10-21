<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>Cuidemos el Paseo Wheelwright</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo_cuidemoselpaseo.jpeg') }}">
    <meta name="description" content="Estado, cronograma, ideas y fuentes sobre el Paseo Wheelwright de Valparaíso, cerrado formalmente desde julio de 2026 tras el temporal, aunque uno de sus accesos sigue abierto y sin señalización.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Cuidemos el Paseo Wheelwright">
    <meta property="og:description" content="Estado, cronograma, ideas y fuentes sobre el Paseo Wheelwright de Valparaíso.">
    <meta property="og:url" content="{{ route('paseo.index') }}">
    <meta property="og:site_name" content="Cuidemos el Paseo Wheelwright">
    <meta property="og:image" content="{{ asset('logo_cuidemoselpaseo.jpeg') }}">
    <meta name="twitter:card" content="summary">

    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "z1clr1kyrz");
    </script>

@include('paseo.partials.base-head')
<style>
/* Hero */
.hero {
  position: relative; overflow: hidden; isolation: isolate;
  display: flex; align-items: flex-end;
  min-height: clamp(440px, 78vh, 760px);
  min-height: clamp(440px, 78svh, 760px);
  margin-inline: calc(-1 * max(16px, env(safe-area-inset-left, 0px)));
  padding-inline: max(16px, env(safe-area-inset-left, 0px));
  padding-block: clamp(36px, 7vw, 84px) clamp(52px, 7vw, 76px);
  background: #0A2540; color: #fff;
}
.hero .wrap { position: relative; z-index: 2; }
.hero h1, .hero .lead { color: #fff; text-shadow: 0 2px 18px rgba(0, 0, 0, .35); }
.hero .eyebrow { color: rgba(255, 255, 255, .88); }
/* Fotos de fondo: se alternan con fundido y un zoom lento */
.slides { position: absolute; inset: 0; z-index: 0; }
.slide {
  position: absolute; inset: 0; background-size: cover; background-position: center;
  opacity: 0; transform: scale(1.04); transition: opacity 1.6s ease;
}
.slide.on { opacity: 1; animation: kenburns 9s ease-out forwards; }
@keyframes kenburns { from { transform: scale(1.04); } to { transform: scale(1.12); } }
/* La clase .sN se agrega por JS justo antes de mostrar cada foto, para no descargarlas todas al inicio */
@for ($i = 2; $i <= 10; $i++)
.s{{ $i }} { background-image: url('{{ asset("cuidemos_el_paseo/drone/banner/fondo-$i.webp") }}'); }
@endfor
@media (max-width: 760px) {
@for ($i = 2; $i <= 10; $i++)
  .s{{ $i }} { background-image: url('{{ asset("cuidemos_el_paseo/drone/banner/fondo-$i-movil.webp") }}'); }
@endfor
}
.hero::after {
  content: ""; position: absolute; inset: 0; z-index: 1; pointer-events: none;
  background: linear-gradient(0deg, rgba(4, 20, 38, .88) 0%, rgba(4, 20, 38, .45) 55%, rgba(4, 20, 38, .2) 100%);
}
/* Puntos para elegir foto */
.dots {
  position: absolute; z-index: 3; left: 50%; bottom: clamp(10px, 2vw, 18px); transform: translateX(-50%);
  display: flex; gap: 2px;
}
.dots button {
  width: 24px; height: 24px; padding: 0; border: 0; background: none; cursor: pointer;
  display: grid; place-items: center;
}
.dots button::before {
  content: ""; width: 9px; height: 9px; border-radius: 5px;
  background: rgba(255, 255, 255, .55); transition: width .3s ease, background-color .3s ease;
}
.dots button:hover::before { background: rgba(255, 255, 255, .9); }
.dots button[aria-current="true"]::before { width: 22px; background: var(--yellow); }
/* Flechas tenues de avance y retroceso */
.hero { touch-action: pan-y; }
.arrow {
  position: absolute; z-index: 3; top: 50%; transform: translateY(-50%);
  width: 44px; height: 44px; padding: 0; border: 0; border-radius: 50%; cursor: pointer;
  display: grid; place-items: center; color: #fff;
  background: rgba(4, 20, 38, .22); opacity: .5; transition: opacity .2s ease, background-color .2s ease;
}
.arrow:hover, .arrow:focus-visible { opacity: .95; background: rgba(4, 20, 38, .5); }
.arrow.prev { left: max(8px, env(safe-area-inset-left, 0px)); }
.arrow.next { right: max(8px, env(safe-area-inset-right, 0px)); }
.arrow svg { width: 22px; height: 22px; }
@media (max-width: 760px) {
  .arrow { width: 36px; height: 36px; opacity: .4; }
  .arrow svg { width: 18px; height: 18px; }
}
@media (prefers-reduced-motion: reduce) {
  .arrow { transition: none; }
  .slide { transition: none; }
  .slide.on { animation: none; }
  .dots button::before { transition: none; }
}
.hero h1 {
  font-size: clamp(2.5rem, 8.5vw, 5.2rem);
  font-weight: 800;
  font-stretch: 108%;
  line-height: .98;
  letter-spacing: -.01em;
  max-width: 14ch;
  margin-top: 14px;
}
.lead { font-size: clamp(1.1rem, 2.4vw, 1.32rem); max-width: 56ch; margin-top: 20px; color: var(--ink); }

section { padding-block: clamp(34px, 6vw, 68px); }
section h2 { font-size: clamp(1.7rem, 4.2vw, 2.5rem); font-weight: 800; font-stretch: 105%; line-height: 1.05; margin-top: 8px; }
.sec-intro { max-width: 62ch; color: var(--ink-2); margin-top: 14px; }

.card {
  background: var(--card); border: 1px solid var(--line); border-radius: 14px;
  padding: clamp(16px, 3vw, 24px); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
}
/* En móviles el desenfoque de tantas tarjetas hace pesado el scroll */
@media (max-width: 760px) {
  .card { -webkit-backdrop-filter: none; backdrop-filter: none; }
}
.pill {
  display: inline-block; font-family: var(--f-mono); font-size: .78rem; font-weight: 500; letter-spacing: .05em;
  padding: 4px 12px; border-radius: 999px; background: var(--yellow); color: var(--on-yellow);
}

/* Estado */
.status h2 { margin-top: 12px; }
/* Mismas columnas que los indicadores, para que el texto no quede cargado a un lado en escritorio */
.status-text { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 250px), 1fr)); gap: 12px 22px; margin-top: 12px; }
.status-text p { color: var(--ink-2); }
.meters { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 250px), 1fr)); gap: 22px; margin-top: 26px; }
.meter { min-width: 0; }
.m-num { font-family: var(--f-display); font-weight: 800; font-stretch: 108%; font-size: 2.1rem; line-height: 1; font-variant-numeric: tabular-nums; }
.m-num small { font-family: var(--f-body); font-weight: 600; font-size: .95rem; color: var(--ink-2); font-stretch: 100%; }
.bar { height: 10px; border-radius: 5px; background: var(--line); margin-top: 10px; overflow: hidden; }
.bar span { display: block; height: 100%; border-radius: 5px; background: var(--c, var(--blue)); }
.meter p { margin-top: 10px; font-size: .96rem; color: var(--ink-2); }
.src { font-size: .88rem; color: var(--ink-2); }

/* Cronograma */
.filters { display: flex; flex-wrap: wrap; gap: 8px; margin: 22px 0 30px; }
.chip {
  font: inherit; font-size: .92rem; font-weight: 600; padding: 8px 15px; border-radius: 999px;
  border: 1px solid var(--line); background: var(--card); color: var(--ink); cursor: pointer;
}
.chip[aria-pressed="true"] { background: var(--btn-bg); color: var(--btn-fg); border-color: transparent; }
.timeline { list-style: none; margin: 0; padding: 0; position: relative; }
.timeline::before { content: ""; position: absolute; left: 6px; top: 6px; bottom: 6px; width: 8px; border-radius: 4px; background: var(--lane); }
.timeline::after {
  content: ""; position: absolute; left: 9px; top: 12px; bottom: 12px; width: 2px;
  background: repeating-linear-gradient(180deg, var(--lane-dash) 0 9px, transparent 9px 18px);
}
/* Cada año es un bloque: la etiqueta se monta sobre la línea del tiempo y el panel agrupa sus eventos */
.yr { position: relative; padding: 12px 14px 14px 0; }
.yr + .yr { margin-top: 22px; }
.yr::before {
  content: ""; position: absolute; left: 26px; right: 0; top: 0; bottom: 0; border-radius: 16px;
  background: color-mix(in srgb, var(--card-solid) 38%, transparent); border: 1px solid var(--line);
}
.yr-h { position: relative; z-index: 2; margin: 0 0 14px; line-height: 1; }
.yr-h span {
  display: inline-block; padding: 7px 14px; border-radius: 999px;
  background: var(--btn-bg); color: var(--btn-fg);
  font-family: var(--f-display); font-weight: 800; font-stretch: 108%; font-size: 1.35rem;
  font-variant-numeric: tabular-nums; letter-spacing: .01em;
}
.yr-evs { list-style: none; margin: 0; padding: 0; position: relative; }
.ev { position: relative; padding-left: 42px; padding-bottom: 16px; --t: var(--blue); }
.ev:last-child { padding-bottom: 0; }
.ev[data-t="mar"] { --t: var(--rust); }
.ev[data-t="ciu"] { --t: var(--ink-2); }
.ev::before {
  content: ""; position: absolute; left: 0; top: 20px; width: 20px; height: 20px; border-radius: 50%;
  background: var(--card-solid); border: 5px solid var(--t); z-index: 1;
}
.ev-card { min-width: 0; }
.ev-card header { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 12px; margin-bottom: 8px; }
.ev-card time { font-family: var(--f-mono); font-size: .88rem; font-weight: 500; font-variant-numeric: tabular-nums; }
.tag {
  color: var(--t); border: 1px solid currentColor; border-radius: 999px; padding: 1px 10px;
  font-size: .74rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
}
.ev-card h4 { margin: 0; font-family: var(--f-display); text-wrap: balance; color: var(--ink); font-size: 1.22rem; font-weight: 700; font-stretch: 100%; line-height: 1.2; }
.ev-card p { margin-top: 8px; }
.ev-card .src { margin-top: 10px; }
.ev.next .ev-card { border-style: dashed; }

.open-q { margin-top: 30px; }
.open-q h3 { font-size: 1.3rem; font-weight: 700; }
.open-q ul { margin: 14px 0 0; padding-left: 1.2em; display: grid; gap: 10px; }
.open-q li::marker { color: var(--ink-2); }

/* Adelanto de la galería */
.teaser { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 180px), 1fr)); gap: 16px; margin-top: 28px; }
.t-sala { display: block; padding: 0 0 14px; overflow: hidden; text-decoration: none; color: var(--ink); }
.t-sala img { display: block; width: 100%; aspect-ratio: 4 / 3; object-fit: cover; margin-bottom: 12px; }
.t-sala .eyebrow, .t-t { display: block; padding-inline: 14px; }
.t-t { font-family: var(--f-display); font-weight: 700; font-size: 1.2rem; margin-top: 2px; }
.t-sala:hover .t-t { text-decoration: underline; }
#galeria .btn { margin-top: 22px; }

/* Ideas */
.feature { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr); gap: clamp(20px, 4vw, 40px); align-items: start; margin-top: 28px; }
.feature h3 { font-size: clamp(1.5rem, 3.4vw, 2rem); font-weight: 800; font-stretch: 105%; line-height: 1.1; }
.feature p { margin-top: 12px; }
blockquote { margin: 18px 0 0; padding: 0; font-family: var(--f-display); font-weight: 700; font-size: 1.2rem; line-height: 1.25; }
blockquote cite { display: block; margin-top: 6px; font-family: var(--f-body); font-weight: 400; font-style: normal; font-size: .92rem; color: var(--ink-2); }
.diagram { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; min-width: 0; }
.diagram figure { margin: 0; min-width: 0; }
.diagram svg { display: block; width: 100%; height: auto; border-radius: 10px; background: var(--card-solid); border: 1px solid var(--line); }
.diagram figcaption { font-size: .9rem; margin-top: 8px; color: var(--ink-2); }
.diagram figcaption strong { color: var(--ink); display: block; }
.diag-note { font-size: .86rem; color: var(--ink-2); margin-top: 10px; }
.ideas { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 280px), 1fr)); gap: 16px; margin-top: 30px; }
.idea { min-width: 0; }
.idea h3 { font-size: 1.25rem; font-weight: 700; line-height: 1.2; margin-top: 6px; }
.idea p { margin-top: 10px; }
.caveat { margin-top: 22px; max-width: 66ch; font-size: .96rem; color: var(--ink-2); }

/* Marejadas */
.sea { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr); gap: clamp(20px, 4vw, 40px); margin-top: 26px; align-items: start; }
.scale { list-style: none; margin: 18px 0 0; padding: 0; display: grid; gap: 10px; }
.scale li { display: grid; grid-template-columns: 64px minmax(0, 1fr); gap: 12px; align-items: start; }
.m {
  font-family: var(--f-mono); font-weight: 500; font-size: .92rem; text-align: center; padding: 4px 0;
  border-radius: 8px; background: var(--btn-bg); color: var(--btn-fg);
}
.m.hi { background: var(--rust); color: #fff; }
.site-list { list-style: none; margin: 0; padding: 0; }
.site-list li + li { border-top: 1px solid var(--line); }
.site-list a { display: flex; justify-content: space-between; gap: 12px; padding: 12px 2px; text-decoration: none; font-weight: 600; color: var(--ink); }
.site-list a span:last-child { color: var(--blue); font-weight: 600; white-space: nowrap; }
.site-list a:hover span:first-child { text-decoration: underline; }
.sea h3 { font-size: 1.25rem; font-weight: 700; }
.btn {
  display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px;
  background: var(--btn-bg); color: var(--btn-fg); text-decoration: none; font-weight: 700; margin-top: 16px;
}

/* Enlaces */
.links { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 420px), 1fr)); gap: 16px; margin-top: 28px; }
.links h3 { font-size: 1.2rem; font-weight: 700; }
.links ul { list-style: none; margin: 10px 0 0; padding: 0; }
.links li + li { border-top: 1px solid var(--line); }
.links a { display: block; padding: 11px 2px; text-decoration: none; color: var(--ink); }
.links a:hover .l-title { text-decoration: underline; }
.l-title { display: block; font-weight: 600; color: var(--blue); }
.l-meta { display: block; font-size: .86rem; color: var(--ink-2); font-family: var(--f-mono); }

/* Nosotros */
.about { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: clamp(20px, 4vw, 40px); margin-top: 26px; align-items: start; }
.about p + p { margin-top: 12px; }
.about .big { font-family: var(--f-display); font-weight: 700; font-size: 1.35rem; line-height: 1.25; }
.fronts { list-style: none; margin: 0; padding: 0; display: grid; gap: 14px; }
.fronts h3 { font-size: 1.12rem; font-weight: 700; }
.fronts p { margin-top: 4px; color: var(--ink-2); }
.about .contact { margin-top: 22px; }
.contact h3 { font-size: 1.2rem; font-weight: 700; }
.contact p { margin-top: 6px; color: var(--ink-2); }


@media (max-width: 820px) {
  .feature, .sea, .about { grid-template-columns: minmax(0, 1fr); }
}
@media (max-width: 560px) {
  .diagram { grid-template-columns: minmax(0, 1fr); }
  .scale li { grid-template-columns: 54px minmax(0, 1fr); }
}
</style>

</head>

<body>
<div class="topbar">
  <div class="wrap">
    <a class="brand" href="#top"><img src="{{ asset('logo_cuidemoselpaseo.jpeg') }}" alt="Movimiento Cuidemos el Paseo Wheelwright" width="44" height="44"><span class="full">Cuidemos el Paseo Wheelwright</span></a>
    <nav class="topnav" aria-label="Secciones">
      <a href="#estado">Estado hoy</a>
      <a href="#cronograma">Cronograma</a>
      <a href="#galeria">Galería</a>
      <a href="#ideas">Ideas</a>
      <a href="#mar">Mar</a>
      <a href="#enlaces">Enlaces</a>
      <a href="#nosotros">Quiénes somos</a>
      <a href="#contacto">Contacto</a>
    </nav>
  </div>
</div>

<header class="hero" id="top">
  <div class="slides" role="img" aria-label="Fotos del Paseo Wheelwright y el borde costero de Valparaíso">
    <div class="slide s2 on"></div>
@for ($i = 3; $i <= 10; $i++)
    <div class="slide" data-s="{{ $i }}"></div>
@endfor
  </div>
  <button type="button" class="arrow prev" aria-label="Foto anterior"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg></button>
  <button type="button" class="arrow next" aria-label="Foto siguiente"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg></button>
  <div class="dots" role="group" aria-label="Elegir foto">
@for ($i = 2; $i <= 10; $i++)
    <button type="button" aria-label="Foto {{ $i - 1 }}" aria-current="{{ $i === 2 ? 'true' : 'false' }}"></button>
@endfor
  </div>
  <div class="wrap">
    <p class="eyebrow"> <b>Movimiento ciudadano</b> · Valparaíso</p>
    <h1>Cuidemos el Paseo Wheelwright</h1>
    <p class="lead">Un paseo de cerca de 1,7 kilómetros frente al mar, entre el Muelle Barón y Caleta Portales. Queremos que esté abierto, seguro y bien mantenido todo el año.</p>
  </div>
</header>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<main>
<section id="estado">
  <div class="wrap">
    <div class="card status">
      <span class="pill">Estado al 21 de octubre de 2026</span>
      <h2><A:link></A:link>Años de abandono</h2>
      <div class="status-text">
      <p>A fines de 2024, fuertes marejadas destruyeron parte del paseo y, hasta la fecha, la Dirección de Obras Portuarias del MOP <b>aún no publica una licitación para repararlo.</b> A esta inacción estatal se suma el descuido municipal: pasan meses sin que se limpie la arena de la ciclovía y del paso peatonal, mientras falta la mitad de las luminarias. En la práctica, se trata de un espacio público completamente abandonado.</p>
      <p>Para nuestro movimiento, el paseo es un <b>espacio público fundamental para el desplazamiento y la recreación;</b> un punto de encuentro directo con el mar que ofrece una alternativa segura frente al constante peligro que representa la avenida España para peatones y ciclistas.</p>
      </div>
      <div class="meters">
        <div class="meter" style="--c: var(--blue)">
          <div class="m-num">580 m <small>de 1.300 m</small></div>
          <div class="bar" role="img" aria-label="45 por ciento"><span style="width: 44.6%"></span></div>
          <p>Tramo intervenido en noviembre de 2025, entre los metros en mal estado. Quedan 720 m sin atender.</p>
          <p class="src">Fuente: El Pionero de Valparaíso, especial de enero de 2026 (<a href="{{ route('revista.show', 'especial-paseo-wheelwright-entre-el-potencial-y-el-olvido') }}">ver revista</a>)</p>
        </div>
        <div class="meter" style="--c: var(--rust)">
          <div class="m-num">48 <small>de 96 luminarias</small></div>
          <div class="bar" role="img" aria-label="50 por ciento"><span style="width: 50%"></span></div>
          <p>Estaban apagadas en noviembre de 2025. En diciembre, 44 seguían sin funcionar.</p>
          <p class="src">Fuente: El Pionero de Valparaíso, especial de enero de 2026</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="cronograma">
  <div class="wrap">
    <p class="eyebrow">Cronograma</p>
    <h2>Del primer proyecto al cierre de 2026</h2>
    <p class="sec-intro">Construcción, temporales y anuncios de reparación. Las fechas marcadas como aproximadas se confirmarán con información pública.</p>

    <div class="filters" role="group" aria-label="Filtrar cronograma">
      <button class="chip" type="button" data-f="all" aria-pressed="true">Todo</button>
      <button class="chip" type="button" data-f="obra" aria-pressed="false">Paseo y obras</button>
      <button class="chip" type="button" data-f="mar" aria-pressed="false">Marejadas y daños</button>
      <button class="chip" type="button" data-f="ciu" aria-pressed="false">Ciudadanía</button>
    </div>

    <ol class="timeline">
      <li class="yr">
        <h3 class="yr-h"><span>2004</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>Ago 2004</time><span class="tag">Paseo y obras</span></header>
            <h4>La Empresa Portuaria pide el paseo en comodato</h4>
            <p>El directorio de la Empresa Portuaria Valparaíso acuerda pedir en comodato, por 90 años, el sector del Paseo Wheelwright. Hoy queda por aclarar quién es titular del terreno.</p>
            <p class="src">Fuente: <a href="https://www.mundomaritimo.cl/noticias/editor/1982" target="_blank" rel="noopener noreferrer">MundoMarítimo, desde El Mercurio de Valparaíso, 13 ago 2004</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2005–2006</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>2005–2006</time><span class="tag">Paseo y obras</span></header>
            <h4>Se construye el paseo</h4>
            <p>El paseo, de 1.722 metros, se construye en tres etapas durante 2005 y 2006.</p>
            <p class="src">Fuentes: <a href="https://www.maritimoportuario.cl/mp/invierten-588-millones-para-recuperar-paseo-wheelwright-en-valparaiso/" target="_blank" rel="noopener noreferrer">Marítimo Portuario, 16 ene 2017</a> · <a href="https://www.latercera.com/noticia/inauguran-paseo-peatonal-wheelwright-en-valparaiso/" target="_blank" rel="noopener noreferrer">La Tercera, 13 may 2008</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2007</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>Ene 2007</time><span class="tag">Paseo y obras</span></header>
            <h4>Se licita la habilitación del paseo entre Caleta Portales y el Muelle Barón</h4>
            <p>La Dirección de Obras Portuarias licita una pasarela sobre la línea del tren para unir la avenida España con el paseo, junto con obras para mejorar el paseo costero. Mercado Público la muestra como cerrada, sin publicar a quién se adjudicó.</p>
            <p class="src">Fuente: <a href="https://www.mercadopublico.cl/Procurement/Modules/RFB/DetailsAcquisition.aspx?idlicitacion=1267-34-LP06" target="_blank" rel="noopener noreferrer">Mercado Público, licitación 1267-34-LP06</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2008</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>13 may 2008</time><span class="tag">Paseo y obras</span></header>
            <h4>Se inaugura el Paseo Wheelwright</h4>
            <p>El ministro de Obras Públicas, Sergio Bitar, y el alcalde Aldo Cornejo entregan oficialmente el paseo, con una inversión total de $2.516 millones.</p>
            <p class="src">Fuentes: <a href="https://www.latercera.com/noticia/inauguran-paseo-peatonal-wheelwright-en-valparaiso/" target="_blank" rel="noopener noreferrer">La Tercera, 13 may 2008</a> · <a href="{{ route('revista.show', 'especial-paseo-wheelwright-entre-el-potencial-y-el-olvido') }}">El Pionero de Valparaíso, especial de enero de 2026</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2015</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="mar"><article class="card ev-card">
            <header><time>Ago 2015</time><span class="tag">Marejadas y daños</span></header>
            <h4>El temporal destruye parte del paseo</h4>
            <p>El temporal que golpeó el litoral central daña infraestructura y defensas costeras del Wheelwright.</p>
            <p class="src">Fuente: <a href="https://www.maritimoportuario.cl/mp/invierten-588-millones-para-recuperar-paseo-wheelwright-en-valparaiso/" target="_blank" rel="noopener noreferrer">Marítimo Portuario, ene 2017</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2016</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>15 mar 2016</time><span class="tag">Paseo y obras</span></header>
            <h4>El MOP anuncia obras de emergencia</h4>
            <p>Inversión de $1.150 millones en tres obras del borde costero regional. Para el Wheelwright se destinan $600 millones, con un plazo de 240 días.</p>
            <p class="src">Fuente: <a href="https://www.mediabanco.com/ministro-undurraga-anuncia-inversion-1-150-millones-pesos-reparar-danos-ocasionados-marejadas-borde-costero-la-region-valparaiso/" target="_blank" rel="noopener noreferrer">Comunicado del MOP vía Mediabanco</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2017</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>Ene – feb 2017</time><span class="tag">Paseo y obras</span></header>
            <h4>Se adjudica la reparación por $588 millones</h4>
            <p>La obra se adjudica a Francisco Huenchunir Díaz por $588.004.902, con un plazo de 150 días, cerca de cinco meses. Incluye reparar el pavimento y los decks de madera, y reconstruir muros de enrocado y hormigón para proteger el sector de socavones.</p>
            <p class="src">Fuentes: <a href="https://www.mercadopublico.cl/Procurement/Modules/RFB/DetailsAcquisition.aspx?idlicitacion=1267-22-LR16" target="_blank" rel="noopener noreferrer">Mercado Público, licitación 1267-22-LR16</a> · <a href="https://www.maritimoportuario.cl/mp/invierten-588-millones-para-recuperar-paseo-wheelwright-en-valparaiso/" target="_blank" rel="noopener noreferrer">Marítimo Portuario</a> · <a href="https://www.radiofestival.cl/inicio-de-obras-de-conservacion-de-paseo-wheelwright-en-valparaiso/" target="_blank" rel="noopener noreferrer">Radio Festival</a></p>
          </article></li>

          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>11 oct 2017</time><span class="tag">Paseo y obras</span></header>
            <h4>Termina la primera etapa y se adjudica una segunda por $338 millones</h4>
            <p>La segunda etapa se adjudica a INCOSS Ingenieros Constructores por $337.645.269, bajo el presupuesto de $438 millones. Incluye escaños de hormigón, pintura de barandas y sombreaderos de madera. El MOP habla de más de $1.100 millones invertidos en total, pero las dos adjudicaciones suman $926 millones, por eso pediremos los contratos.</p>
            <p class="src">Fuentes: <a href="https://www.mercadopublico.cl/Procurement/Modules/RFB/DetailsAcquisition.aspx?idlicitacion=1267-9-O117" target="_blank" rel="noopener noreferrer">Mercado Público, licitación 1267-9-O117</a> · <a href="https://www.maritimoportuario.cl/mp/concluye-primera-etapa-de-reparacion-en-paseo-wheelwright-debido-a-marejadas/" target="_blank" rel="noopener noreferrer">Marítimo Portuario</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2023</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>Mar 2023</time><span class="tag">Paseo y obras</span></header>
            <h4>Obras de conservación del MOP</h4>
            <p>El MOP inicia trabajos de conservación por $558 millones entre Caleta Portales y la zona de miradores: pavimentos, iluminación, mobiliario y una rampa de acceso universal a la playa. Coinciden con la licitación 1267-22-O122, publicada en julio de 2022 con un presupuesto de $407,7 millones y tres ofertas. Mercado Público no publica a quién se adjudicó ni por qué el monto anunciado es mayor.</p>
            <p class="src">Fuentes: <a href="https://www.mercadopublico.cl/Procurement/Modules/RFB/DetailsAcquisition.aspx?idlicitacion=1267-22-O122" target="_blank" rel="noopener noreferrer">Mercado Público, licitación 1267-22-O122</a> · <a href="https://radiosomos.cl/region/valparaiso-ministerio-de-obras-publicas-inicio-mejoras-en-el-paseo-wheelwright-de-valparaiso/" target="_blank" rel="noopener noreferrer">Radio Somos</a> · <a href="https://www.radiofestival.cl/mop-comenzo-obras-de-mantencion-en-paseo-wheelwright-de-valparaiso/" target="_blank" rel="noopener noreferrer">Radio Festival</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2024</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="mar"><article class="card ev-card">
            <header><time>Dic 2024 – ene 2025</time><span class="tag">Marejadas y daños</span></header>
            <h4>Marejadas anormales dañan el paseo</h4>
            <p>El Centro Meteorológico Marítimo de Valparaíso, de la Armada, emite un aviso de marejadas anormales de Arica al golfo de Arauco entre el 27 y el 31 de diciembre, con mayor intensidad del 28 al 30, y un segundo aviso para el 1 y 2 de enero. El ingeniero Mauricio Molina lo describe como la marejada más fuerte en 50 años desde el Pacífico Norte.</p>
            <p class="src">Fuentes: <a href="https://www.radioagricultura.cl/noticias/nacional/armada-emite-aviso-de-marejadas-anormales_20241226/" target="_blank" rel="noopener noreferrer">Radio Agricultura, 26 dic 2024</a> · <a href="https://www.t13.cl/noticia/el-tiempo/nacional/armada-emite-nuevo-aviso-marejadas-para-gran-parte-costa-chile-30-12-2024" target="_blank" rel="noopener noreferrer">T13, 30 dic 2024</a> · <a href="https://www.mercuriovalpo.cl/impresa/2025/01/05/full/cuerpo-principal/8" target="_blank" rel="noopener noreferrer">El Mercurio de Valparaíso, 5 ene 2025</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2025</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="obra"><article class="card ev-card">
            <header><time>5 ene 2025</time><span class="tag">Paseo y obras</span></header>
            <h4>El MOP dice que el paseo está bajo tuición municipal</h4>
            <p>La Dirección de Obras Portuarias señala que el paseo está bajo tuición municipal y anuncia trabajos de conservación mayor para mediados de 2025. Las obras no fueron ejecutadas.</p>
            <p class="src">Fuentes: <a href="https://www.mercuriovalpo.cl/impresa/2025/01/05/full/cuerpo-principal/8" target="_blank" rel="noopener noreferrer">El Mercurio de Valparaíso</a> · <a href="https://cpichile.org/mop-evalua-proyectos-para-afrontar-danos-en-borde-costero/" target="_blank" rel="noopener noreferrer">CPI Chile (republicación)</a></p>
          </article></li>

          <li class="ev" data-t="ciu"><article class="card ev-card">
            <header><time>Oct – nov 2025</time><span class="tag">Ciudadanía</span></header>
            <h4>Se documenta el abandono y el municipio interviene 580 m</h4>
            <p>El municipio, junto a voluntarios, interviene 580 m del paseo. Quedan sin atender 720 m de los 1.300 m en mal estado.</p>
            <p class="src">Fuente: <a href="{{ route('revista.show', 'especial-paseo-wheelwright-entre-el-potencial-y-el-olvido') }}">El Pionero de Valparaíso, especial de enero de 2026</a> · <a href="{{ route('paseo.galeria') }}#registro">Ver las fotos →</a></p>
          </article></li>
        </ol>
      </li>

      <li class="yr">
        <h3 class="yr-h"><span>2026</span></h3>
        <ol class="yr-evs">
          <li class="ev" data-t="ciu"><article class="card ev-card">
            <header><time>Ene 2026</time><span class="tag">Ciudadanía</span></header>
            <h4>Especial del paseo y entrevista a Mauricio Molina</h4>
            <p>El Pionero de Valparaíso publica un diagnóstico del paseo. Molina, ingeniero civil oceánico y creador del Sistema de Alerta de Marejadas, propone infraestructura dañable, presupuesto anual de mantención y un fondo de reparación inmediata.</p>
            <p class="src">Fuente: <a href="{{ route('revista.show', 'especial-paseo-wheelwright-entre-el-potencial-y-el-olvido') }}">El Pionero de Valparaíso</a></p>
          </article></li>

          <li class="ev" data-t="mar"><article class="card ev-card">
            <header><time>Jul 2026</time><span class="tag">Marejadas y daños</span></header>
            <h4>El temporal deja el paseo cerrado, pero solo formalmente</h4>
            <p>El municipio cierra el paseo indefinidamente por riesgo para la seguridad de las personas, tras los severos daños que dejó el temporal. Pone vallas en un extremo, pero deja abierto el otro, junto al futuro Parque Barón, sin ningún aviso de que no se debe entrar.</p>
            <p class="src">Fuentes: <a href="https://www.theclinic.cl/2026/07/26/cierran-el-paseo-wheelwright-de-valparaiso-hasta-nuevo-aviso-ingresar-al-sector-representa-un-riesgo-para-la-seguridad/" target="_blank" rel="noopener noreferrer">The Clinic, 26 jul</a> · <a href="https://www.biobiochile.cl/noticias/nacional/region-de-valparaiso/2026/07/27/paseo-wheelwright-en-valparaiso-permanecera-cerrado-indefinidamente-tras-severos-danos-por-temporal.shtml" target="_blank" rel="noopener noreferrer">BioBioChile, 27 jul</a></p>
          </article></li>

          <li class="ev" data-t="mar"><article class="card ev-card">
            <header><time>17 oct 2026</time><span class="tag">Marejadas y daños</span></header>
            <h4>Daños a la vista y reparación sin avances visibles</h4>
            <p>Se anunció una inversión para reparar el paseo, sin monto ni plazo conocidos, y no se ven avances. El reportaje muestra daños graves a la vista.</p>
            <p class="src">Fuente: <a href="https://www.meganoticias.cl/nacional/534153-tradicional-paseo-costero-wheelwright-valparaiso-presenta-graves-danos-marejadas-temporales-video-17-10-2026.html" target="_blank" rel="noopener noreferrer">Meganoticias, 17 oct 2026</a></p>
          </article></li>
        </ol>
      </li>
    </ol>

    <div class="card open-q">
      <h3>Lo que todavía no sabemos</h3>
      <ul>
        <li>¿Quién es hoy titular del terreno del paseo?</li>
        <li>¿Qué entidad administrará el Parque Barón? Proponemos que esa misma entidad se haga cargo de la mantención del paseo.</li>
        <li>¿Cuánto costaron al final las obras de 2016–2017? Conocemos los montos adjudicados, pero no los contratos ni sus modificaciones.</li>
        <li>¿Quién ejecutó las obras de 2023 y por qué se anunciaron $558 millones si la licitación tenía un presupuesto de $407,7 millones? ¿Qué se hizo en 2025?</li>
        <li>¿Qué obras, plazo y monto tendrá la reparación de 2026, y con qué criterios de diseño frente al oleaje?</li>
      </ul>
      <p class="src" style="margin-top:14px">Las vamos a consultar mediante solicitudes de acceso a la información pública (Ley 20.285) y publicaremos las respuestas.</p>
    </div>
  </div>
</section>

<section id="galeria">
  <div class="wrap">
    <p class="eyebrow">Galería</p>
    <h2>El paseo, foto a foto</h2>
    <p class="sec-intro">Más de 80 fotografías, de marzo de 2023 a octubre de 2026, ordenadas en cinco etapas: antes, durante y después de la intervención de noviembre, y con el paseo cerrado.</p>
    <div class="teaser">
@foreach ([
    ['senales', '01', 'Señales tempranas', 'Mar 2023 – mar 2024', 'IMG_20240312_172124'],
    ['registro', '02', 'Recorrer y registrar', 'Oct – nov 2025', 'IMG_20251012_164445656_HDR'],
    ['intervencion', '03', 'La intervención', 'Nov 2025', 'IMG_20251123_171251174_HDR'],
    ['despues', '04', 'Lo que quedó', 'Dic 2025 – ene 2026', 'IMG_20260120_191547533_HDR'],
    ['cierre', '05', 'Con el paseo cerrado', 'Oct 2026', 'IMG_20261018_114123797_HDR_AE'],
] as [$id, $num, $titulo, $periodo, $foto])
      <a class="card t-sala" href="{{ route('paseo.galeria') }}#{{ $id }}">
        <img src="{{ asset('cuidemos_el_paseo/galeria/web/' . $foto . '-800.webp') }}" alt="" loading="lazy" decoding="async">
        <span class="eyebrow">{{ $periodo }}</span>
        <span class="t-t">{{ $titulo }}</span>
      </a>
@endforeach
    </div>
    <a class="btn" href="{{ route('paseo.galeria') }}">Ver imágenes →</a>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="ideas">
  <div class="wrap">
    <p class="eyebrow">Ideas para instalar</p>
    <h2>Un paseo hecho para convivir con el mar</h2>
    <p class="sec-intro">Estas propuestas recogen la entrevista a Mauricio Molina, ingeniero civil oceánico y creador del Sistema de Alerta de Marejadas de la Universidad de Valparaíso.</p>

    <div class="feature">
      <div>
        <h3>Infraestructura dañable</h3>
        <p>Para que una obra de borde costero resistiera cualquier marejada tendría que ser enorme, carísima y taparía la vista. Además, un muro sólido devuelve la ola y se lleva la arena hacia el fondo, y la playa desaparece.</p>
        <p>La alternativa es una estructura que deje pasar el agua, libere energía y se pueda reponer rápido y a bajo costo cuando algo se dañe. Es lo que el paseo ya intentó con maderas y rejillas, y lo que falta es la reparación a tiempo.</p>
        <blockquote>En la parte marítima lo definitivo no existe.<cite>Mauricio Molina, ingeniero civil oceánico</cite></blockquote>
      </div>
      <div>
        <div class="diagram">
          <figure>
            <svg viewBox="0 0 320 190" role="img" aria-labelledby="dA">
              <title id="dA">Esquema de un muro sólido: la ola rebota y arrastra la arena hacia el fondo</title>
              <defs>
                <marker id="ahA" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto"><path d="M0 0L10 5L0 10z" fill="var(--arrow-wave)"/></marker>
                <marker id="ahS" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto"><path d="M0 0L10 5L0 10z" fill="var(--arrow-sand)"/></marker>
              </defs>
              <rect x="0" y="70" width="200" height="120" fill="var(--water)"/>
              <path d="M0 70 q12.5 -8 25 0 t25 0 t25 0 t25 0 t25 0 t25 0 t25 0 t25 0" fill="none" stroke="var(--arrow-wave)" stroke-opacity=".4" stroke-width="2"/>
              <path d="M30 190 L196 128 L196 190 Z" fill="var(--sand)"/>
              <rect x="212" y="78" width="108" height="112" fill="var(--land)"/>
              <rect x="196" y="60" width="16" height="130" fill="var(--arrow-wave)"/>
              <path d="M20 96 H182" fill="none" stroke="var(--arrow-wave)" stroke-width="3" stroke-linecap="round" marker-end="url(#ahA)"/>
              <path d="M184 86 C168 44 108 40 62 72" fill="none" stroke="var(--arrow-wave)" stroke-width="3" stroke-linecap="round" marker-end="url(#ahA)"/>
              <path d="M172 126 L84 160" fill="none" stroke="var(--arrow-sand)" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="1 8" marker-end="url(#ahS)"/>
            </svg>
            <figcaption><strong>Muro sólido</strong>La ola rebota y la arena se va al fondo.</figcaption>
          </figure>
          <figure>
            <svg viewBox="0 0 320 190" role="img" aria-labelledby="dB">
              <title id="dB">Esquema de una estructura permeable: el agua pasa bajo y a través de la plataforma y la arena se mantiene</title>
              <rect x="0" y="96" width="236" height="94" fill="var(--water)"/>
              <path d="M0 96 q12.5 -8 25 0 t25 0 t25 0 t25 0 t25 0 t25 0 t25 0 t25 0 t25 0" fill="none" stroke="var(--arrow-wave)" stroke-opacity=".4" stroke-width="2"/>
              <path d="M30 190 L252 120 L252 190 Z" fill="var(--sand)"/>
              <rect x="264" y="78" width="56" height="112" fill="var(--land)"/>
              <rect x="158" y="78" width="6" height="64" fill="var(--arrow-wave)"/>
              <rect x="212" y="78" width="6" height="52" fill="var(--arrow-wave)"/>
              <rect x="252" y="78" width="6" height="42" fill="var(--arrow-wave)"/>
              <g fill="var(--arrow-wave)">
                <rect x="150" y="70" width="14" height="8"/><rect x="168" y="70" width="14" height="8"/><rect x="186" y="70" width="14" height="8"/>
                <rect x="204" y="70" width="14" height="8"/><rect x="222" y="70" width="14" height="8"/><rect x="240" y="70" width="14" height="8"/>
              </g>
              <path d="M20 116 H206" fill="none" stroke="var(--arrow-wave)" stroke-width="3" stroke-linecap="round" marker-end="url(#ahA)"/>
              <path d="M214 116 H248" fill="none" stroke="var(--arrow-wave)" stroke-width="2" stroke-linecap="round" stroke-dasharray="1 7" marker-end="url(#ahA)"/>
              <path d="M193 66 V44" fill="none" stroke="var(--arrow-wave)" stroke-width="2.5" stroke-linecap="round" marker-end="url(#ahA)"/>
            </svg>
            <figcaption><strong>Estructura permeable</strong>El agua pasa, pierde fuerza y la arena queda.</figcaption>
          </figure>
        </div>
        <p class="diag-note">Esquema ilustrativo y sin escala, según la explicación de Molina. Flecha oscura: ola. Flecha blanca punteada: arena que se pierde.</p>
      </div>
    </div>

    <div class="ideas">
      <article class="card idea">
        <p class="eyebrow">Diseño</p>
        <h3>Más permeable, no más duro</h3>
        <p>Si el agua atraviesa la estructura, no acumula la presión que arranca los tablones. Las rampas de acceso que hoy son de madera podrían ser de rejilla de acero galvanizado de alta resistencia, y bajo los decks hay que disipar la energía de la ola antes de que llegue al piso peatonal.</p>
      </article>
      <article class="card idea">
        <p class="eyebrow">Presupuesto</p>
        <h3>Mantención con recursos asegurados</h3>
        <p>Un presupuesto anual de mantención y un fondo de reparación inmediata, para reaccionar en días y no en años. Molina advierte que hoy se vive en ciclos de destrucción y reparaciones tardías.</p>
      </article>
      <article class="card idea">
        <p class="eyebrow">Alerta</p>
        <h3>Avisar antes del golpe</h3>
        <p>El oleaje se puede anticipar entre tres y diez días. Con alertas por playa se puede cerrar el paseo y retirar el mobiliario móvil durante las 48 horas previas a un evento de categoría 5.</p>
      </article>
      <article class="card idea">
        <p class="eyebrow">Gobernanza</p>
        <h3>Una sola administración</h3>
        <p>Que la entidad que gestione el Parque Barón incluya la mantención del Paseo Wheelwright, con estándares profesionales de seguridad, iluminación y limpieza. Hoy esa tarea recae en el municipio, que no ha logrado sostenerla.</p>
      </article>
    </div>
    <p class="caveat">Son propuestas en construcción. Antes de exigir un diseño concreto queremos conocer los estudios y los criterios de diseño del MOP para el oleaje.</p>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="mar">
  <div class="wrap">
    <p class="eyebrow">Mar</p>
    <h2>Mira el mar antes de ir</h2>
    <p class="sec-intro">El Sistema de Alerta de Marejadas de la Escuela de Ingeniería Civil Oceánica de la Universidad de Valparaíso publica pronósticos de oleaje, marea e impacto por playa, a siete días. Hay páginas específicas para el Wheelwright.</p>

    <div class="sea">
      <div>
        <h3>La escala M1 a M5</h3>
        <p style="margin-top:8px">Mide el impacto esperado, no la altura de la ola. Tres metros pueden ser poco en una costa expuesta y mucho en una protegida, por eso se usan categorías.</p>
        <ul class="scale">
          <li><span class="m">M1</span><span>Impacto bajo.</span></li>
          <li><span class="m">M3</span><span>Ocurre cerca de una vez al año. Hay daños y se afecta la actividad económica.</span></li>
          <li><span class="m hi">M4–M5</span><span>Eventos extraordinarios y muy poco frecuentes, que afectan actividades y dañan infraestructura.</span></li>
        </ul>
        <a class="btn" href="https://marejadas.uv.cl/categorias/folleto-categorias" target="_blank" rel="noopener noreferrer">Ver el folleto de categorías ↗</a>
      </div>
      <div class="card">
        <h3>Pronóstico a 7 días</h3>
        <ul class="site-list" style="margin-top:8px">
          <li><a href="https://marejadas.uv.cl/CL15VAS25" target="_blank" rel="noopener noreferrer"><span>Paseo Wheelwright Norte</span><span>Ver ↗</span></a></li>
          <li><a href="https://marejadas.uv.cl/CL15VAS27" target="_blank" rel="noopener noreferrer"><span>Paseo Wheelwright Sur</span><span>Ver ↗</span></a></li>
          <li><a href="https://marejadas.uv.cl/CL15VAS28" target="_blank" rel="noopener noreferrer"><span>Barón</span><span>Ver ↗</span></a></li>
          <li><a href="https://marejadas.uv.cl/CL15VAS24" target="_blank" rel="noopener noreferrer"><span>Playa Caleta Portales</span><span>Ver ↗</span></a></li>
          <li><a href="https://marejadas.uv.cl/pronosticos/cl15va" target="_blank" rel="noopener noreferrer"><span>Toda la bahía de Valparaíso</span><span>Ver ↗</span></a></li>
        </ul>
        <p class="src" style="margin-top:12px">Los pronósticos pertenecen a la Universidad de Valparaíso. Aquí solo enlazamos a su sitio.</p>
      </div>
    </div>
  </div>
</section>

<section id="enlaces">
  <div class="wrap">
    <p class="eyebrow">Enlaces</p>
    <h2>Dónde leer más</h2>
    <p class="sec-intro">Las fuentes que usamos, ordenadas por tema. Todas abren en una pestaña nueva.</p>

    <div class="links">
      <div class="card">
        <h3>Prensa 2026: cierre y daños</h3>
        <ul>
          <li><a href="https://www.theclinic.cl/2026/07/26/cierran-el-paseo-wheelwright-de-valparaiso-hasta-nuevo-aviso-ingresar-al-sector-representa-un-riesgo-para-la-seguridad/" target="_blank" rel="noopener noreferrer"><span class="l-title">Cierran el paseo hasta nuevo aviso</span><span class="l-meta">The Clinic · 26 jul 2026</span></a></li>
          <li><a href="https://www.biobiochile.cl/noticias/nacional/region-de-valparaiso/2026/07/27/paseo-wheelwright-en-valparaiso-permanecera-cerrado-indefinidamente-tras-severos-danos-por-temporal.shtml" target="_blank" rel="noopener noreferrer"><span class="l-title">Cerrado indefinidamente tras los daños del temporal</span><span class="l-meta">BioBioChile · 27 jul 2026</span></a></li>
          <li><a href="https://www.meganoticias.cl/nacional/534153-tradicional-paseo-costero-wheelwright-valparaiso-presenta-graves-danos-marejadas-temporales-video-17-10-2026.html" target="_blank" rel="noopener noreferrer"><span class="l-title">Daños visibles y reparación sin avances</span><span class="l-meta">Meganoticias · 17 oct 2026</span></a></li>
        </ul>
      </div>
      <div class="card">
        <h3>Obras del MOP en el paseo</h3>
        <ul>
          <li><a href="https://www.latercera.com/noticia/inauguran-paseo-peatonal-wheelwright-en-valparaiso/" target="_blank" rel="noopener noreferrer"><span class="l-title">Inauguran el paseo peatonal Wheelwright</span><span class="l-meta">La Tercera · 13 may 2008</span></a></li>
          <li><a href="https://www.mediabanco.com/ministro-undurraga-anuncia-inversion-1-150-millones-pesos-reparar-danos-ocasionados-marejadas-borde-costero-la-region-valparaiso/" target="_blank" rel="noopener noreferrer"><span class="l-title">Anuncio de $1.150 millones para el borde costero</span><span class="l-meta">Comunicado MOP vía Mediabanco · 15 mar 2016</span></a></li>
          <li><a href="https://www.maritimoportuario.cl/mp/invierten-588-millones-para-recuperar-paseo-wheelwright-en-valparaiso/" target="_blank" rel="noopener noreferrer"><span class="l-title">$588 millones para recuperar el paseo</span><span class="l-meta">Marítimo Portuario · ene 2017</span></a></li>
          <li><a href="https://www.maritimoportuario.cl/mp/concluye-primera-etapa-de-reparacion-en-paseo-wheelwright-debido-a-marejadas/" target="_blank" rel="noopener noreferrer"><span class="l-title">Termina la primera etapa de reparación</span><span class="l-meta">Marítimo Portuario · 2017 aprox.</span></a></li>
          <li><a href="https://valparaiso.mop.gob.cl/millonaria-inversion-del-mop-en-paseo-wheelwright/" target="_blank" rel="noopener noreferrer"><span class="l-title">Inversión del MOP de más de $500 millones</span><span class="l-meta">MOP Valparaíso · fecha por confirmar</span></a></li>
          <li><a href="https://radiosomos.cl/region/valparaiso-ministerio-de-obras-publicas-inicio-mejoras-en-el-paseo-wheelwright-de-valparaiso/" target="_blank" rel="noopener noreferrer"><span class="l-title">Conservación de $558 millones</span><span class="l-meta">Radio Somos · 2023 aprox.</span></a></li>
          <li><a href="https://www.mercuriovalpo.cl/impresa/2025/01/05/full/cuerpo-principal/8" target="_blank" rel="noopener noreferrer"><span class="l-title">El MOP evalúa proyectos tras las marejadas</span><span class="l-meta">El Mercurio de Valparaíso · 5 ene 2025</span></a></li>
        </ul>
      </div>
      <div class="card">
        <h3>Licitaciones en Mercado Público</h3>
        <ul>
          <li><a href="https://www.mercadopublico.cl/Procurement/Modules/RFB/DetailsAcquisition.aspx?idlicitacion=1267-34-LP06" target="_blank" rel="noopener noreferrer"><span class="l-title">Habilitación del paseo y pasarela sobre la vía férrea</span><span class="l-meta">1267-34-LP06 · cerrada, sin adjudicación publicada · 2007</span></a></li>
          <li><a href="https://www.mercadopublico.cl/Procurement/Modules/RFB/DetailsAcquisition.aspx?idlicitacion=1267-22-LR16" target="_blank" rel="noopener noreferrer"><span class="l-title">Conservación del paseo, primera etapa</span><span class="l-meta">1267-22-LR16 · adjudicada · $588 millones · 2016–2017</span></a></li>
          <li><a href="https://www.mercadopublico.cl/Procurement/Modules/RFB/DetailsAcquisition.aspx?idlicitacion=1267-9-O117" target="_blank" rel="noopener noreferrer"><span class="l-title">Conservación del paseo, segunda etapa</span><span class="l-meta">1267-9-O117 · adjudicada · $338 millones · 2017</span></a></li>
          <li><a href="https://www.mercadopublico.cl/Procurement/Modules/RFB/DetailsAcquisition.aspx?idlicitacion=1267-22-O122" target="_blank" rel="noopener noreferrer"><span class="l-title">Conservación y accesibilidad, Caleta Portales a miradores</span><span class="l-meta">1267-22-O122 · cerrada, sin adjudicación publicada · 2022</span></a></li>
        </ul>
        <p class="src" style="margin-top:12px">Al buscar "Wheelwright" aparecen otras licitaciones que no son del paseo: la Plaza Wheelwright del barrio puerto, frente a la Aduana, el bandejón de la avenida Errázuriz y el Paseo Wheelwright de Caldera, en la Región de Atacama.</p>
      </div>
      <div class="card">
        <h3>Marejadas y ciencia</h3>
        <ul>
          <li><a href="https://marejadas.uv.cl/" target="_blank" rel="noopener noreferrer"><span class="l-title">Sistema de Alerta de Marejadas UV</span><span class="l-meta">Universidad de Valparaíso</span></a></li>
          <li><a href="https://marejadas.uv.cl/pronosticos/cl15va" target="_blank" rel="noopener noreferrer"><span class="l-title">Pronóstico de la bahía de Valparaíso</span><span class="l-meta">Universidad de Valparaíso</span></a></li>
          <li><a href="https://doi.org/10.1007/s11069-026-08063-3" target="_blank" rel="noopener noreferrer"><span class="l-title">Artículo científico sobre la escala de impacto</span><span class="l-meta">Natural Hazards · 2026</span></a></li>
          <li><a href="https://www.mundomaritimo.cl/noticias/editor/1982" target="_blank" rel="noopener noreferrer"><span class="l-title">Comodato del paseo solicitado por la Empresa Portuaria</span><span class="l-meta">MundoMarítimo · 2004</span></a></li>
          <li><a href="{{ route('revista.show', 'especial-paseo-wheelwright-entre-el-potencial-y-el-olvido') }}"><span class="l-title">El Pionero de Valparaíso, especial sobre el paseo</span><span class="l-meta">elpionerodevalpo.cl · ene 2026</span></a></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="nosotros">
  <div class="wrap">
    <p class="eyebrow">Quiénes somos</p>
    <h2>Cuidemos el Paseo Wheelwright</h2>
    <div class="about">
      <div>
        <p class="big">Que el paseo esté abierto, seguro y cuidado todo el año, y que su obra se diseñe para convivir con el mar.</p>
        <p>Somos un movimiento ciudadano de personas que usan y quieren el paseo: quienes lo recorren a pie, en bicicleta o en patines. Nos organizamos para informarnos, proponer y pedir cuentas.</p>
        <p>Nuestra petición central es que la entidad que administre el Parque Barón se haga cargo también de la mantención del Paseo Wheelwright.</p>
        <div class="card contact" id="contacto">
          <h3>Contáctanos</h3>
          <p>¿Quieres sumarte, aportar información o conversar con nosotros? Déjanos tus datos o escríbenos por Instagram.</p>
          <button type="button" class="btn" data-abrir-contacto style="border:0;cursor:pointer;font:inherit;font-weight:700">Escríbenos aquí</button>
          <a class="btn" style="margin-left:8px" href="https://www.instagram.com/cuidemospaseowheelwright/" target="_blank" rel="noopener noreferrer"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg> @cuidemospaseowheelwright</a>
        </div>
      </div>
      <ul class="fronts">
        <li class="card">
          <h3>Administración y mantención</h3>
          <p>Hoy la mantención recae en el municipio. Pedimos que quede a cargo de quien administre el Parque Barón, con presupuesto propio y estándares claros.</p>
        </li>
        <li class="card">
          <h3>Tipo de infraestructura</h3>
          <p>Proponemos obras permeables y dañables, con reparación rápida y alertas por marejada, en lugar de muros que quitan la playa.</p>
        </li>
        <li class="card">
          <h3>Información pública</h3>
          <p>Pedimos por transparencia contratos, estudios, gastos y registros de marejadas, y publicamos lo que obtengamos.</p>
        </li>
      </ul>
    </div>
  </div>
</section>
</main>

<footer>
  <div class="wrap">
    <div class="lane" role="presentation"></div>
    <p><span class="name">Cuidemos el Paseo Wheelwright</span> · Valparaíso · <a href="https://www.instagram.com/cuidemospaseowheelwright/" target="_blank" rel="noopener noreferrer">Instagram @cuidemospaseowheelwright</a></p>
    <p>Página informativa del movimiento. Las cifras y fechas provienen de las fuentes enlazadas; las marcadas como aproximadas o por contrastar se confirmarán con solicitudes de información pública. Actualizada el 21 de octubre de 2026.</p>
  </div>
</footer>

<script>
(function () {
  var chips = document.querySelectorAll('.chip[data-f]');
  var items = document.querySelectorAll('.ev');
  var years = document.querySelectorAll('.yr');
  chips.forEach(function (c) {
    c.addEventListener('click', function () {
      var f = c.getAttribute('data-f');
      chips.forEach(function (x) { x.setAttribute('aria-pressed', x === c ? 'true' : 'false'); });
      items.forEach(function (li) { li.hidden = !(f === 'all' || li.getAttribute('data-t') === f); });
      // Oculta el año completo si el filtro no deja ningún evento en él
      years.forEach(function (y) { y.hidden = !y.querySelector('.ev:not([hidden])'); });
    });
  });
})();

// Alterna las fotos de fondo de la portada
(function () {
  var slides = document.querySelectorAll('.hero .slide');
  var dots = document.querySelectorAll('.hero .dots button');
  if (slides.length < 2) return;
  var auto = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var i = 0, timer;
  // Carga la foto siguiente con anticipación para que el fundido no muestre un fondo vacío
  function load(k) {
    var n = slides[k].getAttribute('data-s');
    if (n) { slides[k].classList.add('s' + n); slides[k].removeAttribute('data-s'); }
  }
  function show(k) {
    if (k === i) return;
    load(k);
    slides[i].classList.remove('on');
    dots[i].setAttribute('aria-current', 'false');
    i = k;
    slides[i].classList.add('on');
    dots[i].setAttribute('aria-current', 'true');
    load((i + 1) % slides.length);
  }
  // Al elegir una foto con los puntos, el contador parte de nuevo
  function start() {
    if (!auto) return;
    clearInterval(timer);
    timer = setInterval(function () { show((i + 1) % slides.length); }, 7000);
  }
  dots.forEach(function (d, k) {
    d.addEventListener('click', function () { show(k); start(); });
  });
  var n = slides.length;
  document.querySelector('.hero .arrow.prev').addEventListener('click', function () { show((i - 1 + n) % n); start(); });
  document.querySelector('.hero .arrow.next').addEventListener('click', function () { show((i + 1) % n); start(); });
  // Deslizar con el dedo: solo si el gesto es claramente horizontal
  var hero = document.querySelector('.hero');
  var x0 = 0, y0 = 0;
  hero.addEventListener('touchstart', function (e) {
    x0 = e.touches[0].clientX; y0 = e.touches[0].clientY;
  }, { passive: true });
  hero.addEventListener('touchend', function (e) {
    var dx = e.changedTouches[0].clientX - x0, dy = e.changedTouches[0].clientY - y0;
    if (Math.abs(dx) < 40 || Math.abs(dx) < Math.abs(dy) * 1.5) return;
    show(dx < 0 ? (i + 1) % n : (i - 1 + n) % n);
    start();
  }, { passive: true });
  load(1);
  start();
})();

// Marca en la barra la sección visible
(function () {
  var nav = document.querySelector('.topnav');
  var links = document.querySelectorAll('.topnav a');
  if (!('IntersectionObserver' in window)) return;
  var obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (!e.isIntersecting) return;
      links.forEach(function (a) {
        var on = a.getAttribute('href') === '#' + e.target.id;
        a.setAttribute('aria-current', on ? 'true' : 'false');
        // Desplaza solo la barra en horizontal; scrollIntoView movía la página en móviles
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
