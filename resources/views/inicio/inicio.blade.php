@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>El Pionero de Valparaíso</title>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7119582402031511"
     crossorigin="anonymous"></script>

    <script>
        // Forzar redirección si estamos en www
        if (window.location.hostname.startsWith('www.')) {
            window.location.replace(window.location.href.replace('://www.', '://'));
        }
    </script>
        
    <!-- SEO Meta Tags -->
    <meta name="description" content="El Pionero de Valparaíso - Revista digital con columnas y miradas diversas sobre la ciudad puerto" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:title" content="El Pionero de Valparaíso" />
    <meta property="og:description" content="Revista digital con columnas y miradas diversas sobre la ciudad puerto" />
    <meta property="og:image" content="{{ asset('storage/especiales/01_paseo_wWheelwright.jpg') }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ config('app.url') }}" />
    <meta property="twitter:title" content="El Pionero de Valparaíso" />
    <meta property="twitter:description" content="Revista digital con columnas y miradas diversas sobre la ciudad puerto" />
    <meta property="twitter:image" content="{{ asset('storage/Portada_Diciembre.jpg') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (app()->environment('production'))
        <script type="text/javascript">
            (function(c, l, a, r, i, t, y) {
                c[a] = c[a] || function() {
                    (c[a].q = c[a].q || []).push(arguments)
                };
                t = l.createElement(r);
                t.async = 1;
                t.src = "https://www.clarity.ms/tag/" + i;
                y = l.getElementsByTagName(r)[0];
                y.parentNode.insertBefore(t, y);
            })(window, document, "clarity", "script", "rsqwi6wyvd");
        </script>
    @endif
</head>

<body class="bg-gray-100 text-gray-900 font-serif text-base">
    <div class="w-full  mx-auto md:p-4">
        <x-header />

        <div>

            <x-navbar />

 <!-- BARRA PRINCIPAL: 3 paneles horizontales -->
<section
    id="ultimo-numero"
    x-data="{ visible: false }"
    x-init="setTimeout(() => visible = true, 100)"
    x-show="visible"
    x-transition
    class="relative overflow-hidden rounded-xl my-6 max-w-7xl mx-auto border border-gray-200 shadow-md bg-white"
>
  <div class="h-1 bg-gradient-to-r from-[#fc5648] via-[#eba81d] to-[#fc5648]"></div>

  <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-gray-200">

    <!-- PANEL 1: El Especial -->
    <div class="flex md:w-[42%]">
      <!-- Portada a full height -->
      <div class="relative shrink-0 w-28 md:w-40 overflow-hidden">
        <span class="absolute top-2 left-2 z-10 bg-[#fc5648] text-white text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded leading-tight shadow">
          Nuevo
        </span>
        <a href="{{ route('pdf.track', ['pdfName' => 'EPDV_ABRIL_2026_A.pdf', 'action' => 'view']) }}" target="_blank" rel="noopener" class="block h-full">
          <img
            src="{{ asset('storage/Ediciones/PORTADA_ABRIL_2026.jpg') }}"
            alt="Especial Paseo Wheelwright"
            class="h-full w-full hover:scale-105 transition-transform duration-300"
            loading="lazy"
          />
        </a>
      </div>
      <!-- Info -->
      <div class="flex flex-col gap-2 min-w-0 p-4 justify-center">
        <span class="text-[11px] font-semibold uppercase tracking-wider text-[#fc5648]">Edición · Abril 2026</span>
        <h2 class="text-base font-extrabold text-gray-900 leading-snug">¿Qué hacer con los rayados en Valparaíso?</h2>
        <p class="text-xs text-gray-500 line-clamp-2">Descarga gratis nuestra edición de abril 2026.</p>
        <div class="flex flex-wrap gap-2 mt-1">
          <a href="{{ route('pdf.track', ['pdfName' => 'EPDV_ABRIL_2026_A.pdf', 'action' => 'download']) }}"
             class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#fc5648] text-white text-xs font-semibold hover:bg-[#d94439] shadow-sm transition"
          >
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Descargar
          </a>
          <a href="{{ route('aportes') }}"
             class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 border border-green-300 text-green-800 text-xs font-semibold hover:bg-green-100 transition"
          >
            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
              <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Apóyanos
          </a>
        </div>
      </div>
    </div>

    <!-- PANEL 2: Secciones (Pindoor + Juegos) -->
    <div class="p-4 md:w-[30%] flex flex-col justify-center gap-2">
      <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Secciones</p>
      <a href="https://www.pindoor.cl" target="_blank"
         class="group flex items-center gap-3 p-3 rounded-xl border border-blue-100 bg-blue-50 hover:bg-blue-100 transition"
      >
        <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-xl shrink-0">🧭</div>
        <div class="min-w-0">
          <p class="text-sm font-bold text-gray-900 leading-tight">Pin<span class="text-[#fc5648]">door</span></p>
          <p class="text-xs text-gray-500 leading-tight truncate">Tesoros de Valparaíso</p>
        </div>
        <svg class="w-4 h-4 text-gray-400 ml-auto shrink-0 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
      <a href="{{ route('juegos.index') }}"
         class="group flex items-center gap-3 p-3 rounded-xl border border-purple-100 bg-purple-50 hover:bg-purple-100 transition"
      >
        <div class="w-10 h-10 rounded-lg bg-purple-600 flex items-center justify-center text-xl shrink-0">🕹️</div>
        <div class="min-w-0">
          <p class="text-sm font-bold text-gray-900 leading-tight">Juegos</p>
          <p class="text-xs text-gray-500 leading-tight truncate">Diversión y entretenimiento</p>
        </div>
        <svg class="w-4 h-4 text-gray-400 ml-auto shrink-0 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
    </div>

    <!-- PANEL 3: Newsletter -->
    <div class="p-4 md:w-[28%] flex flex-col justify-center gap-2 bg-gray-50">
      <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Newsletter</p>
      <p class="text-sm font-semibold text-gray-800">📬 Recibe los próximos números</p>
      <p class="text-xs text-gray-500">Suscríbete y los recibirás directo en tu correo.</p>
      <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex flex-col gap-2 mt-1">
        @csrf
        <input
          type="email"
          name="email"
          required
          placeholder="Tu correo electrónico"
          class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring focus:border-[#fc5648]"
        />
        <button
          type="submit"
          class="w-full px-4 py-2 rounded-lg bg-[#eba81d] text-black text-sm font-semibold hover:brightness-95 border border-amber-300 shadow-sm transition"
        >
          Suscribirse
        </button>
      </form>
      @if (session('success'))
        <p class="text-green-600 text-xs font-semibold">{{ session('success') }}</p>
      @endif
    </div>

  </div>
</section>

            <!-- Layout principal -->
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Sidebar izquierda -->
                <aside
                    class="w-full md:w-2/6 hidden md:block space-y-6 bg-gray-50 border border-gray-300 rounded-lg p-4 shadow-sm">
                    <div>
                <!-- MAIN: Últimas denuncias -->
                <main class="md:col-span-2 w-full">
                    <div class="flex items-center justify-between border-b pb-2 mb-5">
                        <h2 class="text-xl font-black text-slate-900">Últimas <span class="text-[#fc5648]">denuncias</span></h2>
                        <a href="{{ route('denuncia.index') }}" class="text-xs font-bold text-[#fc5648] hover:underline uppercase tracking-widest">Ver todas →</a>
                    </div>

                    @if($ultimasDenuncias->count())
                        <section class="grid grid-cols-1 gap-4">
                            @foreach($ultimasDenuncias as $d)
                                <a href="{{ route('denuncia.show', $d) }}" class="block group">
                                    <article class="flex gap-4 bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 p-3">
                                        <div class="w-24 h-24 shrink-0 rounded-xl overflow-hidden">
                                            <img src="/storage/{{ $d->imagen1 }}" alt="{{ $d->titulo }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <div class="flex flex-col justify-between flex-1 min-w-0">
                                            <div>
                                                <div class="flex items-center gap-1.5 mb-1">
                                                    <svg class="w-3 h-3 text-[#fc5648] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                    <span class="text-[10px] font-bold text-[#fc5648] uppercase tracking-wider truncate">{{ $d->ubicacion }}</span>
                                                </div>
                                                <h4 class="text-sm font-black text-slate-900 leading-tight line-clamp-1 group-hover:text-[#fc5648] transition-colors mb-1">{{ $d->titulo }}</h4>
                                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{{ Str::limit($d->descripcion, 100) }}</p>
                                            </div>
                                            <span class="text-[10px] text-slate-400 font-medium mt-1">{{ $d->approved_at?->diffForHumans() }}</span>
                                        </div>
                                    </article>
                                </a>
                            @endforeach
                        </section>
                    @else
                        <p class="text-sm text-slate-400 mt-4">No hay denuncias publicadas aún.</p>
                    @endif
                </main>


                        <div class="mt-2">
                            <a href="https://www.instagram.com/manos_.alarte/" target="_blank">
                                <img src="{{ asset('storage/manosalarte.png') }}" alt="Anuncio Mediano"
                                    class="w-full rounded border shadow" /></a>
                        </div>
                </aside>

                <!-- Contenido principal -->

                <main class="w-full space-y-6 bg-gray-50 border border-gray-300 rounded-lg md:p-4 p-2 shadow-sm">

                    @if ($columnas->isNotEmpty())
                        @php
                            $ordenados = $columnas->sortByDesc('created_at');
                            $resto = $ordenados->reject(fn($a) => $a->id === $destacada->id)->take(15);
                        @endphp
                        <div>
         
                            <img src="{{ asset('storage/publicidad_movil_2.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  block md:hidden" />
                        </div>
                        {{-- DESTACADA --}}
                        <section class="mt-4">
                            <a href="{{ url('articulo/' . $destacada->slug) }}"
                               class="group block md:flex md:flex-row rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 bg-white">

                                {{-- Imagen --}}
                                <div class="relative w-full aspect-[4/3] md:aspect-auto md:w-2/5 md:flex-shrink-0 overflow-hidden
                                    {{ ($destacada->columnista && $destacada->columnista->foto) ? 'bg-gray-100' : 'bg-gradient-to-br from-[#fc5648] to-[#eba81d]' }}">

                                    @if ($destacada->columnista && $destacada->columnista->foto)
                                        <img src="{{ asset('storage/' . $destacada->columnista->foto) }}"
                                            alt="{{ $destacada->columnista->nombre }}"
                                            class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500" />
                                    @endif

                                    {{-- Badge --}}
                                    <span class="absolute top-3 left-3 z-10 bg-[#fc5648] text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded shadow">
                                        Destacada
                                    </span>

                                    {{-- Gradiente + texto encima (solo mobile) --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent md:hidden"></div>
                                    <div class="absolute bottom-0 inset-x-0 p-4 md:hidden">
                                        <p class="text-[#eba81d] text-xs font-semibold uppercase tracking-wider mb-2">
                                            {{ $destacada->revista->titulo ?? '' }}
                                        </p>
                                        <h3 class="text-white text-xl font-bold leading-tight mb-2 drop-shadow-lg">
                                            {{ $destacada->titulo }}
                                        </h3>
                                        @if ($destacada->autor || ($destacada->columnista && $destacada->columnista->nombre))
                                            <p class="text-gray-300 text-xs italic">
                                                Por {{ $destacada->autor ?: $destacada->columnista->nombre }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Texto (solo desktop) --}}
                                <div class="hidden md:flex md:w-3/5 flex-col justify-center px-8 py-8 border-l-4 border-[#fc5648]">
                                    <div class="h-1 w-10 bg-[#eba81d] rounded-full mb-4"></div>
                                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-3">
                                        {{ $destacada->revista->titulo ?? '' }}
                                    </p>
                                    <h3 class="text-3xl font-bold text-black leading-tight mb-4">
                                        {{ $destacada->titulo }}
                                    </h3>
                                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-4 mb-5">
                                        {{ Str::limit(strip_tags($destacada->contenido), 280) }}
                                    </p>
                                    @if ($destacada->autor || ($destacada->columnista && $destacada->columnista->nombre))
                                        <div class="flex items-center gap-2 mt-auto">
                                            <span class="w-6 h-px bg-[#fc5648]"></span>
                                            <p class="text-sm italic text-[#fc5648] font-medium">
                                                {{ $destacada->autor ?: $destacada->columnista->nombre }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                            </a>
                        </section>
                        <div>
                            <img src="{{ asset('storage/cafe.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  md:block hidden" />
                            <img src="{{ asset('storage/publicidad_movil_1.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  block md:hidden" />
                        </div>
                        {{-- RESTO (tarjetas más pequeñas) --}}
                        @if ($resto->isNotEmpty())
                            <section class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mt-6">
                                @foreach ($resto as $articulo)
                                    <a href="{{ url('articulo/' . $articulo->slug) }}"
                                       class="group block rounded-xl overflow-hidden shadow hover:shadow-lg transition-all duration-300">

                                        {{-- Div imagen: relative para texto encima --}}
                                        <div class="relative aspect-square md:aspect-[10/7] overflow-hidden
                                                    {{ ($articulo->columnista && $articulo->columnista->foto) ? 'bg-gray-100' : 'bg-gradient-to-br from-[#fc5648] to-[#eba81d]' }}">

                                            @if ($articulo->columnista && $articulo->columnista->foto)
                                                <img src="{{ asset('storage/' . $articulo->columnista->foto) }}"
                                                    alt="{{ $articulo->columnista->nombre }}"
                                                    class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                                            @endif

                                            {{-- Gradiente + texto (mobile y desktop) --}}
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>
                                            <div class="absolute bottom-0 inset-x-0 p-2.5 md:p-3">
                                                <div class="w-5 h-0.5 bg-[#eba81d] mb-1.5 rounded-full"></div>
                                                <h4 class="text-white text-xs md:text-sm font-bold leading-tight line-clamp-3 md:line-clamp-2 mb-1 drop-shadow">
                                                    {{ $articulo->titulo }}
                                                </h4>
                                                @if ($articulo->columnista)
                                                    <p class="text-[#eba81d] text-[10px] italic leading-tight truncate">
                                                        {{ $articulo->columnista->nombre }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </section>
                        @endif
                    @else
                        <p>No hay artículos en esta revista.</p>
                    @endif
                        <div>
                            <img src="{{ asset('storage/publicidad_desk_2.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  md:block hidden" />
                            <img src="{{ asset('storage/publicidad_movil_2.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  block md:hidden" />
                        </div>
                </main>
            </div>



            <!-- Footer -->
            <x-footer />

            <div class="fixed bottom-2 right-2 text-xs text-gray-400">1</div>
        </div>
</body>

</html>
