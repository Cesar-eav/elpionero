{{--
  Glosario al pie + pop-up de los términos. Debe incluirse DESPUÉS de todo el contenido de la página:
  lista primero las palabras que se marcaron al renderizar lo de arriba (PaseoGlosario::usadosYReiniciar).
--}}
@use('App\Support\PaseoGlosario', 'G')
@use('Illuminate\Support\Str')
@php
    $usados = G::usadosYReiniciar();
    $deEstaPagina = G::porCategoria($usados);
    $resto = G::porCategoria($usados, true);
    $nResto = array_sum(array_map('count', $resto));
    $item = fn ($t) => $t + ['buscar' => Str::lower(Str::ascii($t['termino'] . ' ' . $t['corto']))];
@endphp
<section id="glosario">
  <div class="wrap">
    <p class="eyebrow">Glosario</p>
    <h2>Las palabras difíciles, explicadas</h2>
    <p class="sec-intro">Las {{ count($usados) }} palabras técnicas que aparecen en esta página, ordenadas por tema. En el texto están @if ($usados){{ G::boton($usados[0], 'subrayadas así') }}@else subrayadas @endif: tócalas para ver su explicación sin perder dónde ibas.</p>

    <div class="g-tools" role="search">
      <label for="g-buscar">Buscar una palabra</label>
      <input type="search" id="g-buscar" placeholder="Ej.: socavón, addendum, SERVIU" autocomplete="off">
    </div>
    <p class="g-none" id="g-none" role="status" hidden>No encontramos esa palabra en el glosario.</p>

@foreach ($deEstaPagina as $cat => $terminos)
    <div class="g-cat">
      <h3>{{ G::CATEGORIAS[$cat] }}</h3>
      <dl class="g-list">
@foreach (array_map($item, $terminos) as $t)
        <div class="g-item" id="g-{{ $t['id'] }}" data-buscar="{{ $t['buscar'] }}">
          <dt>{{ $t['termino'] }}</dt>
          <dd>
            <p class="corto">{{ $t['corto'] }}</p>
            <p class="largo">{{ $t['largo'] }}</p>
@if (! empty($t['ejemplo']))
            <p class="ej"><b>En el Paseo</b>{{ $t['ejemplo'] }}</p>
@endif
          </dd>
        </div>
@endforeach
      </dl>
    </div>
@endforeach

@if ($nResto)
    <details class="mas g-resto" id="g-resto">
      <summary>Ver el resto del glosario ({{ $nResto }} palabras más)</summary>
@foreach ($resto as $cat => $terminos)
      <div class="g-cat">
        <h3>{{ G::CATEGORIAS[$cat] }}</h3>
        <dl class="g-list">
@foreach (array_map($item, $terminos) as $t)
          <div class="g-item" id="g-{{ $t['id'] }}" data-buscar="{{ $t['buscar'] }}">
            <dt>{{ $t['termino'] }}</dt>
            <dd>
              <p class="corto">{{ $t['corto'] }}</p>
              <p class="largo">{{ $t['largo'] }}</p>
@if (! empty($t['ejemplo']))
              <p class="ej"><b>En el Paseo</b>{{ $t['ejemplo'] }}</p>
@endif
            </dd>
          </div>
@endforeach
        </dl>
      </div>
@endforeach
    </details>
@endif
  </div>
</section>

<div class="g-pop" id="g-pop" role="dialog" aria-labelledby="g-pop-t" tabindex="-1" hidden>
  <button type="button" class="x" aria-label="Cerrar la explicación">×</button>
  <p class="k">Glosario</p>
  <h3 id="g-pop-t"></h3>
  <p class="c"></p>
  <p class="e"><b>En el Paseo</b><span></span></p>
  <a class="more" href="#glosario">Ver en el glosario →</a>
</div>

@php G::usadosYReiniciar(); /* el ejemplo de arriba volvió a marcar un término */ @endphp
<script type="application/json" id="g-datos">@json(G::paraPopup())</script>
<script>
(function () {
  var datos = JSON.parse(document.getElementById('g-datos').textContent);
  var pop = document.getElementById('g-pop');
  var tit = pop.querySelector('h3'), cor = pop.querySelector('.c'), ej = pop.querySelector('.e'),
      ejTxt = ej.querySelector('span'), mas = pop.querySelector('.more');
  var movil = window.matchMedia('(max-width: 640px)');
  var actual = null, fijo = false, tEntrar = null, tSalir = null;

  function abrir(a, fijar) {
    var id = a.getAttribute('data-term'), d = datos[id];
    if (!d) return;
    if (actual && actual !== a) actual.setAttribute('aria-expanded', 'false');
    actual = a; fijo = fijar;
    tit.textContent = d.t;
    cor.textContent = d.c;
    ejTxt.textContent = d.e;
    ej.hidden = !d.e;
    mas.setAttribute('href', '#g-' + id);
    a.setAttribute('aria-expanded', 'true');
    a.setAttribute('aria-controls', 'g-pop');
    pop.hidden = false;
    ubicar();
    if (fijar) pop.focus({ preventScroll: true });
  }

  function cerrar(volverAlTermino) {
    if (!actual) return;
    var a = actual;
    pop.hidden = true;
    a.setAttribute('aria-expanded', 'false');
    actual = null; fijo = false;
    if (volverAlTermino) a.focus({ preventScroll: true });
  }

  // Bajo la palabra (o sobre ella si no cabe); en el teléfono, hoja fija abajo
  function ubicar() {
    if (!actual) return;
    pop.classList.toggle('sheet', movil.matches);
    if (movil.matches) { pop.style.left = ''; pop.style.top = ''; return; }
    var r = actual.getBoundingClientRect(), w = pop.offsetWidth, h = pop.offsetHeight;
    var ancho = document.documentElement.clientWidth;
    var left = Math.min(Math.max(12, r.left), ancho - w - 12);
    var top = r.bottom + 10;
    if (top + h > window.innerHeight - 12 && r.top - h - 10 > 12) top = r.top - h - 10;
    pop.style.left = (left + window.scrollX) + 'px';
    pop.style.top = (top + window.scrollY) + 'px';
  }

  document.addEventListener('click', function (e) {
    var a = e.target.closest('.g-term');
    if (a) {
      e.preventDefault();
      if (actual === a && fijo) cerrar(false); else abrir(a, true);
      return;
    }
    if (actual && !pop.contains(e.target)) cerrar(false);
  });
  pop.querySelector('.x').addEventListener('click', function () { cerrar(true); });
  mas.addEventListener('click', function () { cerrar(false); abrirDetallesDe(mas.getAttribute('href')); });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && actual) { e.preventDefault(); cerrar(true); }
  });
  pop.addEventListener('focusout', function (e) {
    var a = e.relatedTarget;
    if (fijo && a && !pop.contains(a) && a !== actual) cerrar(false);
  });
  window.addEventListener('resize', ubicar);

  // Con mouse: vista previa al detenerse sobre la palabra; se cierra al salir (un clic la deja fija)
  document.addEventListener('pointerover', function (e) {
    if (e.pointerType !== 'mouse' || !e.target.closest) return;
    var a = e.target.closest('.g-term');
    if (a) {
      clearTimeout(tSalir);
      if (!fijo && actual !== a) {
        clearTimeout(tEntrar);
        tEntrar = setTimeout(function () { if (!fijo) abrir(a, false); }, 450);
      }
    } else if (pop.contains(e.target)) {
      clearTimeout(tSalir);
    }
  });
  document.addEventListener('pointerout', function (e) {
    if (e.pointerType !== 'mouse' || !e.target.closest) return;
    if (e.target.closest('.g-term') || pop.contains(e.target)) {
      clearTimeout(tEntrar);
      if (!fijo && actual) tSalir = setTimeout(function () { if (!fijo) cerrar(false); }, 300);
    }
  });

  // Si el enlace lleva a una palabra dentro del glosario plegado, lo abre
  function abrirDetallesDe(hash) {
    var el = hash && hash.length > 1 ? document.getElementById(hash.slice(1)) : null;
    var det = el ? el.closest('details') : null;
    if (det) det.open = true;
  }
  window.addEventListener('hashchange', function () { abrirDetallesDe(location.hash); });
  abrirDetallesDe(location.hash);

  // Buscador del glosario (sin tildes ni mayúsculas)
  var q = document.getElementById('g-buscar');
  var items = document.querySelectorAll('.g-item');
  var cats = document.querySelectorAll('.g-cat');
  var resto = document.getElementById('g-resto');
  var nada = document.getElementById('g-none');
  function normal(s) { return s.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, ''); }
  q.addEventListener('input', function () {
    var t = normal(q.value.trim()), hay = 0;
    items.forEach(function (it) {
      var ok = !t || it.getAttribute('data-buscar').indexOf(t) !== -1;
      it.hidden = !ok;
      if (ok) hay++;
    });
    cats.forEach(function (c) { c.hidden = !c.querySelector('.g-item:not([hidden])'); });
    if (t && resto) resto.open = true;
    nada.hidden = hay > 0;
  });
})();
</script>
