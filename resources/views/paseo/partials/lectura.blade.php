{{-- Estilos comunes de las páginas para leer y aprender (documentos y propuesta). Va después de base-head. --}}
<style>
/* Concept: una guía de lectura al borde del mar. Cada idea se presenta en capas: primero la frase simple,
   luego la cita textual con su página, y al final el original. Las palabras difíciles se tocan para entenderlas. */
section { padding-block: clamp(36px, 6vw, 72px); }
section h2 { font-size: clamp(1.7rem, 4.2vw, 2.5rem); font-weight: 800; font-stretch: 105%; line-height: 1.05; margin-top: 8px; }
.sec-intro { max-width: 64ch; color: var(--ink-2); margin-top: 14px; }
.card {
  background: var(--card); border: 1px solid var(--line); border-radius: 14px;
  padding: clamp(18px, 3vw, 26px); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
}
@media (max-width: 760px) { .card { -webkit-backdrop-filter: none; backdrop-filter: none; } }
.btn {
  display: inline-flex; align-items: center; gap: 8px; padding: 11px 18px; border-radius: 10px; border: 0; cursor: pointer;
  background: var(--btn-bg); color: var(--btn-fg); text-decoration: none; font: inherit; font-weight: 700; line-height: 1.2;
}
.btn small { font-weight: 500; opacity: .85; font-size: .82rem; }
.btn.sec { background: transparent; color: var(--ink); box-shadow: inset 0 0 0 2px var(--line); }
.btn.sec:hover { box-shadow: inset 0 0 0 2px var(--ink-2); }
.btn.off { background: transparent; color: var(--ink-2); border: 2px dashed var(--line); padding: 9px 16px; cursor: default; font-weight: 600; }
.acciones { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
.src { font-size: .86rem; color: var(--ink-2); }
.pill {
  display: inline-block; padding: 4px 11px; border-radius: 999px; font-family: var(--f-mono); font-size: .74rem;
  letter-spacing: .05em; text-transform: uppercase; background: var(--yellow); color: var(--on-yellow);
}

/* Portada con foto de drone: la imagen llega por --hero y --hero-m (móvil) */
.hero {
  position: relative; overflow: hidden; isolation: isolate;
  display: flex; align-items: flex-end;
  min-height: clamp(440px, 74vh, 720px);
  min-height: clamp(440px, 74svh, 720px);
  margin-inline: calc(-1 * max(16px, env(safe-area-inset-left, 0px)));
  padding-inline: max(16px, env(safe-area-inset-left, 0px));
  padding-block: clamp(36px, 7vw, 84px) clamp(40px, 6vw, 68px);
  background: #0A2540 var(--hero) center / cover no-repeat;
  color: #fff;
}
@media (max-width: 760px) { .hero { background-image: var(--hero-m); } }
.hero::after {
  content: ""; position: absolute; inset: 0; z-index: 0; pointer-events: none;
  background: linear-gradient(0deg, rgba(4, 20, 38, .94) 0%, rgba(4, 20, 38, .6) 52%, rgba(4, 20, 38, .12) 100%);
}
.hero .wrap { position: relative; z-index: 1; }
.hero .eyebrow { color: rgba(255, 255, 255, .88); }
.hero .eyebrow b { color: var(--yellow); font-weight: 500; }
.hero h1 {
  color: #fff; text-shadow: 0 2px 18px rgba(0, 0, 0, .35);
  font-size: clamp(2.5rem, 8.4vw, 5.2rem); font-weight: 900; font-stretch: 110%;
  line-height: .95; letter-spacing: -.01em; max-width: 13ch; margin-top: 14px;
}
.hero .lead { color: #fff; font-size: clamp(1.1rem, 2.4vw, 1.32rem); max-width: 56ch; margin-top: 20px; text-shadow: 0 1px 10px rgba(0, 0, 0, .4); }
.indice { list-style: none; margin: 26px 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: 8px; }
.indice a {
  display: inline-block; padding: 7px 14px; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: .95rem;
  color: #fff; background: rgba(255, 255, 255, .14); box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .35);
}
.indice a:hover { background: rgba(255, 255, 255, .26); }
.topnav a.otra { color: var(--blue); }

/* Cómo leer esta página: tres pasos numerados */
.guia-wrap { margin-top: clamp(-56px, -5vw, -30px); position: relative; z-index: 2; }
.guia-wrap .card { background: var(--card-solid); box-shadow: 0 16px 40px -24px rgba(10, 37, 64, .55); }
.guia-wrap h2 { font-size: clamp(1.25rem, 2.6vw, 1.55rem); }
.guia { list-style: none; margin: 16px 0 0; padding: 0; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px clamp(16px, 3vw, 30px); counter-reset: g; }
.guia li { counter-increment: g; position: relative; padding-left: 46px; color: var(--ink-2); }
.guia li::before {
  content: counter(g); position: absolute; left: 0; top: 0; width: 34px; height: 34px; border-radius: 50%;
  display: grid; place-items: center; background: var(--btn-bg); color: var(--btn-fg);
  font-family: var(--f-display); font-weight: 800; font-size: 1.05rem;
}
.guia b { display: block; font-family: var(--f-display); font-size: 1.06rem; color: var(--ink); margin-bottom: 2px; }
@media (max-width: 820px) { .guia { grid-template-columns: minmax(0, 1fr); } }

/* Citas textuales: siempre con su documento y su página */
.cita { margin: 0; padding: 12px 16px 12px 18px; border-left: 4px solid var(--blue); border-radius: 0 10px 10px 0; background: color-mix(in srgb, var(--card-solid) 72%, transparent); }
.cita blockquote { margin: 0; font-style: italic; }
.cita blockquote::before { content: "«"; }
.cita blockquote::after { content: "»"; }
.cita figcaption { margin-top: 8px; font-size: .86rem; color: var(--ink-2); display: flex; flex-wrap: wrap; gap: 2px 12px; }
.cita figcaption a { font-weight: 600; }
.citas { display: grid; gap: 12px; }

/* Recuadros */
.para-paseo {
  border-radius: 12px; padding: 14px 16px; background: color-mix(in srgb, var(--teal) 12%, var(--card-solid));
  border: 2px solid color-mix(in srgb, var(--teal) 55%, transparent);
}
.para-paseo h5, .para-paseo .rot { margin: 0 0 6px; font: 700 .78rem/1.2 var(--f-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--teal); }
.ojo { margin-top: 14px; padding: 10px 14px; border-radius: 10px; border-left: 4px solid var(--rust); background: color-mix(in srgb, var(--rust) 9%, var(--card-solid)); font-size: .96rem; }
.ojo b { color: var(--rust); }

/* Desplegables: para lo que no todos necesitan leer */
details.mas { margin-top: 12px; border-top: 1px dashed var(--line); padding-top: 10px; }
details.mas > summary {
  cursor: pointer; list-style: none; display: inline-flex; align-items: center; gap: 8px;
  font-weight: 700; color: var(--blue); padding: 4px 0;
}
details.mas > summary::-webkit-details-marker { display: none; }
details.mas > summary::before {
  content: ""; width: 9px; height: 9px; border-right: 2.5px solid currentColor; border-bottom: 2.5px solid currentColor;
  transform: rotate(-45deg); transition: transform .15s;
}
details.mas[open] > summary::before { transform: rotate(45deg); }
details.mas > :not(summary) { margin-top: 12px; }
@media (prefers-reduced-motion: reduce) { details.mas > summary::before { transition: none; } }

/* Filtros */
.chips { display: flex; flex-wrap: wrap; gap: 8px; margin: 22px 0 6px; }
.chip {
  font: inherit; font-size: .92rem; font-weight: 600; padding: 8px 15px; border-radius: 999px;
  border: 1px solid var(--line); background: var(--card); color: var(--ink); cursor: pointer;
}
.chip[aria-pressed="true"] { background: var(--btn-bg); color: var(--btn-fg); border-color: transparent; }

/* Diagramas en SVG: con desplazamiento horizontal en pantallas chicas para que el texto no se achique */
.diag-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; margin-top: 18px; border-radius: 12px; }
.diag-scroll svg { display: block; width: 100%; height: auto; }
.diag-hint { display: none; font-size: .84rem; color: var(--ink-2); margin-top: 6px; }
@media (max-width: 700px) { .diag-hint { display: block; } }
.svg-t { font-family: var(--f-body); fill: var(--ink); }
.svg-t2 { font-family: var(--f-body); fill: var(--ink-2); }
.svg-d { font-family: var(--f-display); font-weight: 800; fill: var(--ink); }
.svg-m { font-family: var(--f-mono); fill: var(--ink-2); }

/* Términos del glosario: subrayado punteado + un "?" que avisa que se pueden tocar */
.g-term { color: inherit; text-decoration: none; cursor: help; border-radius: 4px; }
.g-term .g-t { text-decoration: underline dotted var(--blue) 2px; text-underline-offset: 4px; }
.g-term .g-q {
  margin-left: .14em; padding: .1em .38em; border-radius: 999px;
  font: 700 .62em/1 var(--f-body); vertical-align: .5em;
  background: var(--blue); color: var(--card-solid);
}
.g-term:hover, .g-term[aria-expanded="true"] { background: color-mix(in srgb, var(--blue) 16%, transparent); }
.g-term:focus-visible { outline: 3px solid var(--yellow); outline-offset: 2px; }

/* Pop-up del término: junto a la palabra en escritorio, como hoja inferior en el teléfono */
.g-pop {
  position: absolute; z-index: 60; width: min(370px, calc(100vw - 24px));
  background: var(--card-solid); color: var(--ink); border: 1px solid var(--line); border-radius: 14px;
  box-shadow: 0 18px 44px -12px rgba(4, 20, 38, .5); padding: 16px 18px 14px;
}
.g-pop[hidden] { display: none; }
.g-pop:focus { outline: none; }
.g-pop:focus-visible { outline: 3px solid var(--yellow); }
.g-pop .k { font: 500 .72rem var(--f-mono); letter-spacing: .08em; text-transform: uppercase; color: var(--blue); }
.g-pop h3 { font-size: 1.16rem; font-weight: 800; line-height: 1.15; margin: 4px 34px 6px 0; }
.g-pop .e { margin-top: 10px; padding-top: 10px; border-top: 1px dashed var(--line); font-size: .95rem; color: var(--ink-2); }
.g-pop .e b { font: 500 .72rem var(--f-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--teal); margin-right: 4px; }
.g-pop .x {
  position: absolute; top: 8px; right: 8px; width: 36px; height: 36px; border: 0; border-radius: 50%;
  background: transparent; color: var(--ink-2); font-size: 1.5rem; line-height: 1; cursor: pointer;
}
.g-pop .x:hover { background: color-mix(in srgb, var(--ink) 8%, transparent); }
.g-pop .more { display: inline-block; margin-top: 10px; font-weight: 700; font-size: .94rem; }
.g-pop.sheet {
  position: fixed; left: 0 !important; right: 0; bottom: 0; top: auto !important; width: auto;
  border-radius: 18px 18px 0 0; max-height: 72vh; overflow: auto;
  padding: 18px max(18px, env(safe-area-inset-right, 0px)) max(18px, env(safe-area-inset-bottom, 0px)) max(18px, env(safe-area-inset-left, 0px));
}

/* Glosario al pie */
.g-tools { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 12px; margin-top: 20px; }
.g-tools label { font-weight: 700; }
.g-tools input {
  font: inherit; padding: 10px 14px; border-radius: 10px; border: 1px solid var(--line);
  background: var(--card-solid); color: var(--ink); width: min(100%, 360px);
}
.g-none { margin-top: 14px; color: var(--ink-2); }
.g-cat h3 { font-size: 1.2rem; margin: 26px 0 12px; }
.g-list { margin: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
.g-item { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 14px 16px; scroll-margin-top: 84px; }
.g-item:target { outline: 3px solid var(--yellow); outline-offset: 2px; }
.g-item dt { font-family: var(--f-display); font-weight: 800; font-size: 1.08rem; line-height: 1.2; }
.g-item dd { margin: 6px 0 0; }
.g-item .corto { font-weight: 600; }
.g-item .largo { margin-top: 6px; color: var(--ink-2); }
.g-item .ej { margin-top: 8px; font-size: .95rem; }
.g-item .ej b { font: 500 .72rem var(--f-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--teal); margin-right: 4px; }
.g-resto { margin-top: 26px; }
@media (max-width: 760px) { .g-list { grid-template-columns: minmax(0, 1fr); } }

/* Cierre */
.cierre-card { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); gap: clamp(18px, 4vw, 40px); align-items: center; }
.cierre-card .big { font-family: var(--f-display); font-weight: 800; font-stretch: 106%; font-size: clamp(1.4rem, 3.4vw, 2rem); line-height: 1.12; }
@media (max-width: 820px) { .cierre-card { grid-template-columns: minmax(0, 1fr); } }
</style>
