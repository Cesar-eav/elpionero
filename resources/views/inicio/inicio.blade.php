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
        <a href="{{ route('pdf.track', ['pdfName' => '01_paseo_wWheelwright.pdf', 'action' => 'view']) }}" target="_blank" rel="noopener" class="block h-full">
          <img
            src="{{ asset('storage/especiales/01_paseo_wWheelwright.jpg') }}"
            alt="Especial Paseo Wheelwright"
            class="h-full w-full hover:scale-105 transition-transform duration-300"
            loading="lazy"
          />
        </a>
      </div>
      <!-- Info -->
      <div class="flex flex-col gap-2 min-w-0 p-4 justify-center">
        <span class="text-[11px] font-semibold uppercase tracking-wider text-[#fc5648]">Especial · Paseo Wheelwright</span>
        <h2 class="text-base font-extrabold text-gray-900 leading-snug">Entre el potencial y el olvido</h2>
        <p class="text-xs text-gray-500 line-clamp-2">Descarga gratis nuestro primer especial sobre uno de los paseos más emblemáticos de Valparaíso.</p>
        <div class="flex flex-wrap gap-2 mt-1">
          <a href="{{ route('pdf.track', ['pdfName' => '01_paseo_wWheelwright.pdf', 'action' => 'download']) }}"
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
                 <!-- MAIN: Noticias dinámicas -->
                <main class="md:col-span-2 w-full">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Últimas noticias</h2>

                {{-- {{$noticias }} --}}

                    @if ($noticias->count())
                        {{-- Opcional: destacar la primera noticia --}}
                        @php
                            $destacada = $noticias->first();
                            $resto = $noticias;
                        @endphp

                        {{-- Resto en grilla --}}
                        @if($resto->count())
                            <section class="grid grid-cols-1 sm:grid-cols-1 gap-6 mt-6">
                                @foreach ($resto as $n)
                                    <a href="{{ route('noticia.show', $n->slug) }}" class="block">
                                        <article class="flex flex-col border rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-shadow">
                                            <div class="flex flex-col h-full">
                                                @if($n->imagen)
                                                    <img src="{{ asset('storage/' . $n->imagen) }}"
                                                         alt="{{ $n->titulo }}"
                                                         class="w-full h-48 object-cover">
                                                @endif
                                                <div class="p-4 flex-1 flex flex-col">
                                                    <div class="text-xs text-gray-500 mb-1">
                                                        {{ $n->fecha_publicacion?->format('d/m/Y') ?? '' }}
                                                    </div>
                                                    <h4 class="text-lg font-bold text-black mb-1 line-clamp-2">
                                                        {{ $n->titulo }}
                                                    </h4>
                                                    <p class="text-sm text-gray-700 text-justify mt-2 flex-1">
                                                        {{ Str::limit($n->resumen ?? strip_tags($n->cuerpo), 250) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </article>
                                    </a>
                                @endforeach
                            </section>
                        @endif
{{--
                        <div class="mt-6">
                            {{ $noticias->links() }}
                        </div> --}}
                    @else
                        <p class="mt-4 text-gray-600">No hay noticias publicadas aún.</p>
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
                            $destacada = $ordenados->first();
                            $resto = $ordenados->slice(1,6);
                        @endphp
                        <div>
         
                            <img src="{{ asset('storage/publicidad_movil_2.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  block md:hidden" />
                        </div>
                        {{-- DESTACADA --}}
                        <section class="mt-4">
                            <div
                                class="border rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-shadow">
                                <a href="{{ url('articulo/' . $destacada->slug) }}" class="flex flex-col md:flex-row">

                                    {{-- Imagen a la derecha, grande --}}
                                    @if ($destacada->columnista && $destacada->columnista->foto)
                                        <div class="w-full md:w-1/3 bg-gray-100">
                                            <img src="{{ asset('storage/' . $destacada->columnista->foto) }}"
                                                alt="{{ $destacada->columnista->nombre }}"
                                                class="w-full h-48 md:h-full object-cover" />
                                        </div>
                                    @endif
                                    {{-- Texto --}}
                                    <div class="w-full md:w-2/3 p-5 flex flex-col justify-center">
                                        <div class="text-sm md:text-base text-gray-700 mb-2">
                                            {{ $destacada->revista->titulo ?? '' }}
                                        </div>
                                        <h3 class="text-2xl md:text-3xl font-bold text-black leading-tight mb-3">
                                            {{ $destacada->titulo }}
                                        </h3>
                                        @if ($destacada->autor)
                                            <p class="text-sm md:text-base italic text-gray-600">Por
                                                {{ $destacada->autor }}</p>
                                        @endif
                                        <p class="text-gray-800 text-base leading-relaxed">
                                            {{ Str::limit(strip_tags($destacada->contenido), 300) }}
                                        </p>
                                    </div>

                                </a>
                            </div>
                        </section>
                        <div>
                            <img src="{{ asset('storage/cafe.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  md:block hidden" />
                            <img src="{{ asset('storage/publicidad_movil_1.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  block md:hidden" />
                        </div>
                        {{-- RESTO (tarjetas más pequeñas) --}}
                        @if ($resto->isNotEmpty())
                            <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                @foreach ($resto as $articulo)
                                    <div
                                        class="flex rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-shadow">
                                        <a href="{{ url('articulo/' . $articulo->slug) }}" class="flex w-full">
                                            {{-- Texto --}}
                                            <div class="w-4/5 p-4 flex flex-col justify-center">
                                                 <div class="text-xs md:text-sm text-gray-700 mb-1">
                                                    {{ $articulo->revista->titulo ?? '' }}
                                                </div>
                                                 {{-- line-clamp-4 --}}
                                                <h4 class="md:text-lg text-xs font-bold text-black mb-1">
                                                    {{ $articulo->titulo }}
                                                </h4>
                                                @if ($articulo->columnista)
                                                    <div class="md:text-sm text-xs italic text-gray-600">{{ $articulo->columnista->nombre }}
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- Imagen a la derecha --}}
                                            @if ($articulo->columnista && $articulo->columnista->foto)
                                                <div class="w-1/5 bg-gray-100">
                                                    <img src="{{ asset('storage/' . $articulo->columnista->foto) }}"
                                                        alt="{{ $articulo->columnista->nombre }}"
                                                        class="w-32 h-full object-cover">
                                                </div>
                                            @endif
                                        </a>
                                    </div>
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
