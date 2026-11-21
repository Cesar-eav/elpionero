@php
    // Principios del manifiesto: también se listan en el adelanto de la portada (paseo/index)
    $principios = require resource_path('data/paseo-manifiesto.php');
    $colores = ['var(--blue)', 'var(--teal)', 'var(--rust)'];
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>Manifiesto · Cuidemos el Paseo Wheelwright</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo_cuidemoselpaseo.jpeg') }}">
    <meta name="description" content="Qué es para nosotros el Paseo Wheelwright de Valparaíso y hacia dónde deben apuntar las soluciones: un paseo abierto, que conviva con el mar, con mantención constante, un solo responsable y cuentas claras.">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Manifiesto · Cuidemos el Paseo Wheelwright">
    <meta property="og:description" content="Lo que el Paseo es para nosotros y hacia dónde deben apuntar las soluciones.">
    <meta property="og:url" content="{{ route('paseo.manifiesto') }}">
    <meta property="og:site_name" content="Cuidemos el Paseo Wheelwright">
    <meta property="og:image" content="{{ asset('cuidemos_el_paseo/drone/banner/fondo-6.webp') }}">
    <meta name="twitter:card" content="summary_large_image">

@include('paseo.partials.base-head')
<style>
/* Concept: un texto para leer de pie frente al mar. Portada con gente en el paseo, declaraciones grandes
   como pancartas, y los principios numerados como las propuestas de la portada. */
section { padding-block: clamp(36px, 6vw, 72px); }
section h2 { font-size: clamp(1.7rem, 4.2vw, 2.5rem); font-weight: 800; font-stretch: 105%; line-height: 1.05; margin-top: 8px; }
.sec-intro { max-width: 62ch; color: var(--ink-2); margin-top: 14px; }
.card {
  background: var(--card); border: 1px solid var(--line); border-radius: 14px;
  padding: clamp(18px, 3vw, 26px); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
}
@media (max-width: 760px) { .card { -webkit-backdrop-filter: none; backdrop-filter: none; } }
.btn {
  display: inline-flex; align-items: center; gap: 8px; padding: 11px 18px; border-radius: 10px; border: 0; cursor: pointer;
  background: var(--btn-bg); color: var(--btn-fg); text-decoration: none; font: inherit; font-weight: 700;
}
.btn.sec { background: transparent; color: var(--ink); box-shadow: inset 0 0 0 2px var(--line); }
.btn.sec:hover { box-shadow: inset 0 0 0 2px var(--ink-2); }

/* Portada */
.hero {
  position: relative; overflow: hidden; isolation: isolate;
  display: flex; align-items: flex-end;
  min-height: clamp(460px, 82vh, 780px);
  min-height: clamp(460px, 82svh, 780px);
  margin-inline: calc(-1 * max(16px, env(safe-area-inset-left, 0px)));
  padding-inline: max(16px, env(safe-area-inset-left, 0px));
  padding-block: clamp(36px, 7vw, 84px) clamp(40px, 6vw, 68px);
  background: #0A2540 url('{{ asset("cuidemos_el_paseo/drone/banner/fondo-6.webp") }}') center / cover no-repeat;
  color: #fff;
}
@media (max-width: 760px) {
  .hero { background-image: url('{{ asset("cuidemos_el_paseo/drone/banner/fondo-6-movil.webp") }}'); }
}
.hero::after {
  content: ""; position: absolute; inset: 0; z-index: 0; pointer-events: none;
  background: linear-gradient(0deg, rgba(4, 20, 38, .92) 0%, rgba(4, 20, 38, .55) 50%, rgba(4, 20, 38, .1) 100%);
}
.hero .wrap { position: relative; z-index: 1; }
.hero .eyebrow { color: rgba(255, 255, 255, .88); }
.hero .eyebrow b { color: var(--yellow); font-weight: 500; }
.hero h1 {
  color: #fff; text-shadow: 0 2px 18px rgba(0, 0, 0, .35);
  font-size: clamp(2.6rem, 9vw, 5.6rem); font-weight: 900; font-stretch: 110%;
  line-height: .95; letter-spacing: -.01em; max-width: 12ch; margin-top: 14px;
}
.hero .lead { color: #fff; font-size: clamp(1.1rem, 2.4vw, 1.35rem); max-width: 54ch; margin-top: 20px; text-shadow: 0 1px 10px rgba(0, 0, 0, .4); }
.indice { list-style: none; margin: 26px 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: 8px; }
.indice a {
  display: inline-block; padding: 7px 14px; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: .95rem;
  color: #fff; background: rgba(255, 255, 255, .14); box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .35);
}
.indice a:hover { background: rgba(255, 255, 255, .26); }

/* Lo que el Paseo es: declaraciones grandes, como pancartas */
.decls { list-style: none; margin: 30px 0 0; padding: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 30px clamp(24px, 4vw, 48px); }
.decl { border-top: 6px solid var(--ink); padding-top: 16px; }
.decl:nth-child(2) { border-color: var(--blue); }
.decl:nth-child(3) { border-color: var(--teal); }
.decl:nth-child(4) { border-color: var(--yellow); }
.decl .pre { display: block; font-family: var(--f-mono); font-size: .8rem; letter-spacing: .08em; text-transform: uppercase; color: var(--ink-2); }
.decl h3 { font-size: clamp(1.5rem, 3.4vw, 2.05rem); font-weight: 800; font-stretch: 106%; line-height: 1.08; margin-top: 6px; }
.decl p { margin-top: 12px; color: var(--ink-2); max-width: 46ch; }

/* Lo que no aceptamos */
.no { position: relative; overflow: hidden; padding-left: clamp(24px, 3.6vw, 34px); }
.no::before { content: ""; position: absolute; inset: 0 auto 0 0; width: 6px; background: var(--rust); }
.no ul { list-style: none; margin: 22px 0 0; padding: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px clamp(20px, 4vw, 40px); }
.no li { display: grid; grid-template-columns: 30px minmax(0, 1fr); gap: 12px; align-items: start; }
.no .x { width: 30px; height: 30px; border-radius: 50%; display: grid; place-items: center; background: var(--rust); color: var(--card-solid); }
.no .x svg { width: 16px; height: 16px; }
.no h3 { font-size: 1.2rem; font-weight: 700; line-height: 1.2; }
.no p { margin-top: 6px; color: var(--ink-2); }

/* Hacia dónde: principios numerados */
/* El primero (el más urgente hoy) va a todo el ancho; los otros seis quedan en 3 × 2 o 2 × 3 */
.princ { list-style: none; margin: 30px 0 0; padding: 0; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; counter-reset: p; }
.princ .p:first-child { grid-column: 1 / -1; }
.princ .p:first-child h3 { font-size: clamp(1.4rem, 3vw, 1.8rem); }
.princ .p:first-child p { max-width: 70ch; }
@media (max-width: 980px) { .princ { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 640px) {
  .princ { grid-template-columns: minmax(0, 1fr); }
  .p::after { font-size: 2.8rem; }
  .p h3 { padding-right: 72px; }
}
.p {
  position: relative; overflow: hidden; counter-increment: p;
  background: var(--card-solid); border: 1px solid var(--line); border-radius: 16px;
  padding: clamp(18px, 3vw, 24px) clamp(18px, 3vw, 26px) clamp(18px, 3vw, 22px) clamp(22px, 3.4vw, 30px);
  box-shadow: 0 10px 28px -18px rgba(10, 37, 64, .45);
}
.p::before { content: ""; position: absolute; inset: 0 auto 0 0; width: 6px; background: var(--c); }
.p::after {
  content: counter(p, decimal-leading-zero); position: absolute; top: 6px; right: 14px;
  font-family: var(--f-display); font-weight: 900; font-stretch: 112%; font-size: 4.2rem; line-height: 1;
  color: var(--c); opacity: .14; pointer-events: none;
}
.p h3 { font-size: clamp(1.2rem, 2.4vw, 1.38rem); font-weight: 800; font-stretch: 104%; line-height: 1.12; padding-right: 92px; }
.p p { margin-top: 10px; }
.p .vs { margin-top: 12px; padding-top: 10px; border-top: 1px dashed var(--line); font-size: .94rem; color: var(--ink-2); }
.p .vs b { font-family: var(--f-mono); font-size: .72rem; font-weight: 500; letter-spacing: .06em; text-transform: uppercase; color: var(--c); margin-right: 6px; }

/* Compromiso y cierre */
.comp { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr); gap: clamp(20px, 4vw, 44px); align-items: start; margin-top: 26px; }
.comp ol { margin: 0; padding-left: 1.3em; display: grid; gap: 12px; }
.comp li::marker { font-family: var(--f-display); font-weight: 800; color: var(--blue); }
.comp li b { color: var(--ink); }
.cierre { font-family: var(--f-display); font-weight: 800; font-stretch: 106%; font-size: clamp(1.5rem, 3.6vw, 2.2rem); line-height: 1.12; }
.cierre-sub { margin-top: 14px; color: var(--ink-2); }
.acciones { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px; }

@media (max-width: 820px) {
  .decls, .no ul, .comp { grid-template-columns: minmax(0, 1fr); }
}
</style>
</head>

<body>
<div class="topbar">
  <div class="wrap">
    <a class="brand" href="{{ route('paseo.index') }}"><img src="{{ asset('logo_cuidemoselpaseo.jpeg') }}" alt="Movimiento Cuidemos el Paseo Wheelwright" width="44" height="44"><span class="full">← El paseo</span></a>
    <nav class="topnav" aria-label="Partes del manifiesto">
      <a href="#que-es">Qué es</a>
      <a href="#no-aceptamos">Lo que no aceptamos</a>
      <a href="#soluciones">Hacia dónde</a>
      <a href="#compromiso">Nuestro compromiso</a>
    </nav>
  </div>
</div>

<header class="hero" id="top">
  <div class="wrap">
    <p class="eyebrow"><b>Manifiesto</b> · Cuidemos el Paseo Wheelwright</p>
    <h1>Un espacio público en el borde costero</h1>
    <p class="lead">Lo que el Paseo Wheelwright es para nosotros y hacia dónde deben apuntar las soluciones. Un texto para leer, discutir y sumarse.</p>
    <ol class="indice">
      <li><a href="#que-es">1 · Qué es el Paseo</a></li>
      <li><a href="#no-aceptamos">2 · Lo que no aceptamos</a></li>
      <li><a href="#soluciones">3 · Hacia dónde</a></li>
      <li><a href="#compromiso">4 · Nuestro compromiso</a></li>
    </ol>
  </div>
</header>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<main>
<section id="que-es">
  <div class="wrap">
    <p class="eyebrow">1 · Lo que el Paseo es</p>
    <h2>Para nosotros, el Paseo es…</h2>
    <ul class="decls">
      <li class="decl">
        <span class="pre">Primero</span>
        <h3>Un espacio público frente al mar</h3>
        <p>Uno de los pocos lugares de Valparaíso donde cualquier persona puede llegar al borde del agua sin pagar ni pedir permiso. Es de quienes lo usan todos los días y de quienes todavía no lo conocen.</p>
      </li>
      <li class="decl">
        <span class="pre">Segundo</span>
        <h3>Punto de conexión Valparaíso-Viña</h3>
        <p>Cerca de 1,7 kilómetros entre el Muelle Barón y Caleta Portales para caminar, pedalear o patinar lejos de los autos. Cuando se cierra, la única alternativa es la avenida España para ir entre Viña y Valparaíso.</p>
      </li>
      <li class="decl">
        <span class="pre">Tercero</span>
        <h3>Parte de la vida diaria</h3>
        <p>Ahí se trota temprano, se pasea al perro, se conversa y se mira el atardecer. No es un decorado para la foto: es rutina, es salud y es encuentro.</p>
      </li>
      <li class="decl">
        <span class="pre">Cuarto</span>
        <h3>Una infraestructura integral </h3>
        <p>Junto al Parque Barón y Caleta Portales forma un solo frente al mar. Hay que pensarlo, financiarlo y cuidarlo como tal.</p>
      </li>
    </ul>
  </div>
</section>

<section id="no-aceptamos">
  <div class="wrap">
    <div class="card no">
      <p class="eyebrow">2 · Lo que no aceptamos</p>
      <h2>Lo que no puede seguir pasando</h2>
      <ul>
        <li>
          <span class="x" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></span>
          <div>
            <h3>Dañar, olvidar y reparar tarde</h3>
            <p>Desde su inauguración en 2008 el paseo ha pasado por temporales, obras de emergencia y conservaciones, pero sin una mantención constante entre una y otra.</p>
          </div>
        </li>
        <li>
          <span class="x" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></span>
          <div>
            <h3>Cerrar sin dar alternativas</h3>
            <p>Rejas en un extremo, el otro abierto y sin aviso, y peatones y ciclistas devueltos a la avenida España. Cerrar no es cuidar.</p>
          </div>
        </li>
        <li>
          <span class="x" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></span>
          <div>
            <h3>Invertir sin pensar</h3>
            <p>La infraesctructura debe reconocer la dinamica marina; se debe pensar en una infraestructura dañable con reparaciones rápidas y no en una obra definitiva.</p>
          </div>
        </li>
        <li>
          <span class="x" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></span>
          <div>
            <h3>Obras que pelean con el mar</h3>
            <p>Muros que devuelven la ola, se llevan la arena y nos quitan la playa, y que igual terminan dañados.</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="soluciones">
  <div class="wrap">
    <p class="eyebrow">3 · Hacia dónde</p>
    <h2>Hacia dónde deben apuntar las soluciones</h2>
    <p class="sec-intro">No pedimos una obra en particular. Pedimos que cualquier solución, venga de donde venga, cumpla con estos principios.</p>
    <ol class="princ">
@foreach ($principios as $i => $p)
      <li class="p" id="p-{{ $p['id'] }}" style="--c: {{ $colores[$i % 3] }}">
        <h3>{{ $p['titulo'] }}</h3>
        <p>{{ $p['texto'] }}</p>
        <p class="vs"><b>En vez de</b>{{ $p['en_vez_de'] }}</p>
      </li>
@endforeach
    </ol>
  </div>
</section>

<div class="wrap"><div class="lane" role="presentation"></div></div>

<section id="compromiso">
  <div class="wrap">
    <p class="eyebrow">4 · Nuestro compromiso</p>
    <h2>Lo que ponemos de nuestra parte</h2>
    <div class="comp">
      <ol>
        <li><b>Registrar.</b> Recorrer el paseo y documentar su estado con fotos, fechas y datos.</li>
        <li><b>Preguntar.</b> Pedir información pública por la Ley 20.285 y publicar cada respuesta.</li>
        <li><b>Proponer.</b> Llevar ideas con fundamento técnico, no solo reclamos.</li>
        <li><b>Colaborar.</b> Trabajar con cualquier institución, vecino u organización que quiera recuperar el paseo.</li>
        <li><b>Insistir.</b> Seguir aquí hasta que el paseo esté abierto, seguro y cuidado todo el año.</li>
      </ol>
      <div class="card">
        <p class="cierre">Las olas no van a dejar de llegar. Lo que puede cambiar es cómo respondemos.</p>
        <p class="cierre-sub">Si compartes este manifiesto, súmate al movimiento o hazlo circular.</p>
        <div class="acciones">
          <button type="button" class="btn" data-abrir-contacto>Me sumo</button>
          <button type="button" class="btn sec" data-compartir>Compartir</button>
          <a class="btn sec" href="{{ route('paseo.index') }}#estado">Ver el estado del paseo</a>
        </div>
      </div>
    </div>
  </div>
</section>
</main>

<footer>
  <div class="wrap">
    <div class="lane" role="presentation"></div>
    <p><span class="name">Cuidemos el Paseo Wheelwright</span> · Valparaíso · <a href="https://www.instagram.com/cuidemospaseowheelwright/" target="_blank" rel="noopener noreferrer">Instagram @cuidemospaseowheelwright</a></p>
    <p>Manifiesto del movimiento Cuidemos el Paseo Wheelwright. Versión de noviembre de 2026. Los datos que menciona están detallados, con sus fuentes, en la <a href="{{ route('paseo.index') }}#cronograma">página principal</a>.</p>
  </div>
</footer>

<script>
// Compartir: menú nativo del teléfono o, si no existe, copia el enlace
(function () {
  var b = document.querySelector('[data-compartir]');
  if (!b) return;
  var url = location.href.split('#')[0];
  b.addEventListener('click', function () {
    if (navigator.share) {
      navigator.share({ title: document.title, url: url }).catch(function () {});
    } else if (navigator.clipboard) {
      navigator.clipboard.writeText(url).then(function () {
        b.textContent = 'Enlace copiado';
        setTimeout(function () { b.textContent = 'Compartir'; }, 2500);
      });
    }
  });
})();

// Marca en la barra la parte visible
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
