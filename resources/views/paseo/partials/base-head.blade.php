{{-- Base compartida de las páginas del Paseo: fuentes, colores, barra superior, divisores y pie --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,500..900&family=IBM+Plex+Mono:wght@400;500&family=Source+Sans+3:wght@400;600;700&display=swap">
<style>
/* Concept: una página-paseo. Cielo celeste que se aclara hacia el horizonte; el cronograma es la ciclovía
   (carril azul con línea punteada); el amarillo de la ciclovía aparece solo en divisores y en el estado de hoy. */
:root {
  color-scheme: light;
  --sky-1: #79BFEA;
  --sky-2: #A9D9F4;
  --sky-3: #D5EEFB;
  --sky-4: #EEF8FD;
  --bg-grad: linear-gradient(180deg, var(--sky-1) 0, var(--sky-2) 620px, var(--sky-3) 1700px, var(--sky-4) 3400px);
  --ink: #0A2540;
  --ink-2: #2A4560;
  --card: rgba(255, 255, 255, .84);
  --card-solid: #FFFFFF;
  --line: rgba(10, 37, 64, .16);
  --blue: #1F66B3;
  --rust: #B4400F;
  --teal: #0E7C7B;
  --yellow: #F5C518;
  --on-yellow: #0A2540;
  --lane: #1F66B3;
  --lane-dash: #FFFFFF;
  --btn-bg: #0B3A66;
  --btn-fg: #FFFFFF;
  --cloud: rgba(255, 255, 255, .8);
  --water: #4A9FD8;
  --sand: #E9D6A8;
  --land: #C9D6E0;
  --arrow-wave: #0A2540;
  --arrow-sand: #FFFFFF;
  --wall: #F5F3EE;
  --wall-2: #E9E5DC;
  --f-display: "Archivo", "Arial Narrow", Arial, sans-serif;
  --f-body: "Source Sans 3", "Segoe UI", Arial, sans-serif;
  --f-mono: "IBM Plex Mono", ui-monospace, Consolas, monospace;
}
@media (prefers-color-scheme: dark) {
  :root:not([data-theme="light"]) {
    color-scheme: dark;
    --sky-1: #0A1B30;
    --sky-2: #10304F;
    --sky-3: #17476F;
    --sky-4: #1D5A87;
    --ink: #E6F2FB;
    --ink-2: #A9C4DA;
    --card: rgba(8, 24, 42, .76);
    --card-solid: #0C2238;
    --line: rgba(230, 242, 251, .18);
    --blue: #86BDF2;
    --rust: #FF9E73;
    --teal: #62D6D2;
    --lane: #2A6DB0;
    --lane-dash: #E6F2FB;
    --btn-bg: #86BDF2;
    --btn-fg: #07213A;
    --cloud: rgba(255, 255, 255, .1);
    --water: #2D77B5;
    --sand: #8D7A4D;
    --land: #26425A;
  --wall: #0B1A2B;
  --wall-2: #13283E;
  }
}
:root[data-theme="dark"] {
  color-scheme: dark;
  --sky-1: #0A1B30;
  --sky-2: #10304F;
  --sky-3: #17476F;
  --sky-4: #1D5A87;
  --ink: #E6F2FB;
  --ink-2: #A9C4DA;
  --card: rgba(8, 24, 42, .76);
  --card-solid: #0C2238;
  --line: rgba(230, 242, 251, .18);
  --blue: #86BDF2;
  --rust: #FF9E73;
  --teal: #62D6D2;
  --lane: #2A6DB0;
  --lane-dash: #E6F2FB;
  --btn-bg: #86BDF2;
  --btn-fg: #07213A;
  --cloud: rgba(255, 255, 255, .1);
  --water: #2D77B5;
  --sand: #8D7A4D;
  --land: #26425A;
  --wall: #0B1A2B;
  --wall-2: #13283E;
}

* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
@media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } }

body {
  margin: 0;
  background-color: var(--sky-4);
  background-image: var(--bg-grad);
  background-repeat: no-repeat;
  color: var(--ink);
  font-family: var(--f-body);
  font-size: 1.0625rem;
  line-height: 1.55;
  padding-left: max(16px, env(safe-area-inset-left, 0px));
  padding-right: max(16px, env(safe-area-inset-right, 0px));
}
.wrap { width: 100%; max-width: 1080px; margin-inline: auto; }
a { color: var(--blue); text-underline-offset: 3px; }
a:focus-visible, button:focus-visible { outline: 3px solid var(--yellow); outline-offset: 3px; border-radius: 6px; }
h1, h2, h3 { font-family: var(--f-display); text-wrap: balance; margin: 0; color: var(--ink); }
p { margin: 0; }

.eyebrow {
  font-family: var(--f-mono);
  font-size: .78rem;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--ink-2);
  font-weight: 500;
}

/* Barra de navegación fija */
html { scroll-padding-top: 72px; }
.topbar {
  position: sticky; top: 0; z-index: 50;
  margin-inline: calc(-1 * max(16px, env(safe-area-inset-left, 0px)));
  padding-inline: max(16px, env(safe-area-inset-left, 0px));
  background: var(--card); border-bottom: 1px solid var(--line);
  -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);
}
.topbar .wrap { display: flex; align-items: center; gap: 16px; min-height: 60px; }
@media (max-width: 980px) { .brand .full { display: none; } }
.brand {
  flex: none; display: flex; align-items: center; gap: 10px;
  font-family: var(--f-display); font-weight: 800; font-stretch: 105%; font-size: 1.05rem;
  color: var(--ink); text-decoration: none; line-height: 1.1;
}
.brand img { display: block; width: 44px; height: 44px; border-radius: 50%; }
.topnav {
  display: flex; gap: 4px; margin-left: auto; min-width: 0;
  overflow-x: auto; scrollbar-width: none; -webkit-overflow-scrolling: touch;
}
.topnav::-webkit-scrollbar { display: none; }
.topnav a {
  flex: none; padding: 6px 12px; border-radius: 999px; font-weight: 600; font-size: .92rem;
  color: var(--ink-2); text-decoration: none; white-space: nowrap;
}
.topnav a:hover { color: var(--ink); background: var(--card-solid); }
.topnav a[aria-current="true"] { background: var(--btn-bg); color: var(--btn-fg); }
@media (max-width: 560px) {
  .topbar .wrap { gap: 10px; }
}

.lane { height: 8px; border-radius: 4px; background: repeating-linear-gradient(90deg, var(--yellow) 0 28px, transparent 28px 48px); }

footer { padding-block: 26px 44px; }
footer p { font-size: .9rem; color: var(--ink-2); max-width: 70ch; margin-top: 16px; }
footer .name { font-family: var(--f-display); font-weight: 800; font-stretch: 105%; color: var(--ink); }
</style>

    @if (app()->environment('production'))
        <script type="text/javascript">
            (function(c, l, a, r, i, t, y) {
                c[a] = c[a] || function() {
                    (c[a].q = c[a].q || []).push(arguments)
                };
                t = l.createElement(r); t.async = 1;
                t.src = "https://www.clarity.ms/tag/" + i;
                y = l.getElementsByTagName(r)[0]; y.parentNode.insertBefore(t, y);
            })(window, document, "clarity", "script", "rsqwi6wyvd");
        </script>
    @endif
