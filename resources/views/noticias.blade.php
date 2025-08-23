<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>El Pionero de Valparaíso</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
</head>

@php
    use Illuminate\Support\Str;
@endphp

<body class="bg-gray-100 flex items-center lg:justify-center min-h-screen flex-col">
    <div class="w-full mx-auto p-4">
        <!-- Encabezado -->
        <header class="text-center mb-1">
            <img src="{{ asset('storage/portada.png') }}" class="w-full mx-auto mb-4 rounded shadow md:block hidden" />
            <img src="{{ asset('storage/logo_m.png') }}" class="w-full mx-auto mb-4 rounded shadow block md:hidden" />
        </header>

        <x-navbar />

        <div class="bg-gray-100 w-full">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- ASIDE -->
                <aside class="md:col-span-1 w-full space-y-6 bg-gray-50 border border-gray-300 rounded-lg p-4 shadow-sm">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Noticias</h2>

                        <div class="mt-4 border-gray-300 border p-3 rounded">
                            <h4 class="text-md font-bold text-gray-700 mb-1">Parque Barón</h4>
                            <p class="text-sm text-gray-700">
                                Aún no hay modelo de gestión para el funcionamiento de la Bodega Simón Bolivar.
                            </p>
                        </div>

                        <div class="mt-4 border-gray-300 border p-3 rounded">
                            <h4 class="text-md font-bold text-gray-700 mb-1">Proponen ciclovía en Av. España</h4>
                            <a href="https://www.pucv.cl/pucv/investigadores-proponen" target="_blank" class="block">
                                <p class="text-sm text-gray-700">
                                    Académicos de la PUCV elaboran propuesta para mejorar la movilidad entre Viña del Mar y Valparaíso.
                                </p>
                            </a>
                        </div>

                        <div class="mt-4 border-gray-300 border p-3 rounded">
                            <h4 class="text-md font-bold text-gray-700 mb-1">Dos buenas noticias para Valparaíso</h4>
                            <p class="text-sm text-gray-700">
                                Pronto se inaugurará
                                <a href="https://www.instagram.com/destinovalpo/" target="_blank" class="font-bold">Destino Valparaíso</a>,
                                que albergará el Museo del Inmigrante. Además,
                                <a href="https://www.instagram.com/bancoestado/reel/DNTO6l1x1_s/" target="_blank" class="font-bold">Banco Estado</a>
                                ha remodelado el edificio patrimonial de calle Prat, con acceso mediante ascensor desde el Paseo Yugoslavo.
                            </p>
                        </div>

                        <div class="mt-4 border-gray-300 border p-3 rounded">
                            <h4 class="text-md font-bold text-gray-700 mb-1">Ruta Fiscalización Ascensores</h4>
                            <p class="text-sm text-gray-700">
                                La agrupación <a href="https://www.ascenval.cl/" target="_blank" class="font-bold">Ascenval</a>
                                realizará una nueva ruta de fiscalización este domingo 17 a las 16:00 hrs. Punto de encuentro: estación baja del ascensor Polanco.
                            </p>
                        </div>

                        <div class="mt-2">
                            <a href="https://www.instagram.com/manos_.alarte/" target="_blank">
                                <img src="{{ asset('storage/manosalarte.jpeg') }}" alt="Anuncio Mediano"
                                     class="w-full rounded border shadow" />
                            </a>
                        </div>
                    </div>
                </aside>

                <!-- MAIN: Noticias dinámicas -->
                <main class="md:col-span-2 w-full">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Últimas noticias</h2>

                {{$noticias }}

                    @if ($noticias->count())
                        {{-- Opcional: destacar la primera noticia --}}
                        @php
                            $destacada = $noticias->first();
                            $resto = $noticias;
                        @endphp

                        {{-- Destacada --}}
                        {{-- <section class="mt-4">
                            <div 
                               class="block border rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-shadow">
                                <div class="flex flex-col md:flex-row">
                       
                                    <div class="w-full md:w-1/3 bg-gray-100">
                                        @if($destacada->imagen)
                                            <img src="{{ asset('storage/' . $destacada->imagen) }}"
                                                 alt="Imagen de {{ $destacada->titulo }}"
                                                 class="w-full h-56 md:h-full object-cover">
                                        @else
                                            <div class="w-full h-56 md:h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                <span class="text-gray-500 text-sm">Sin imagen</span>
                                            </div>
                                        @endif
                                    </div>
                   
                                    <div class="w-full md:w-2/3 p-5">
                                        <div class="text-sm text-gray-600 mb-2">
                                            {{ $destacada->fecha_publicacion?->format('d/m/Y') ?? '' }}
                                        </div>
                                        <h3 class="text-2xl md:text-3xl font-bold text-black leading-snug mb-2">
                                            {{ $destacada->titulo }}
                                        </h3>
                                        @if($destacada->resumen)
                                            <p class="text-gray-700">{{ Str::limit($destacada->resumen, 220) }}</p>
                                        @else
                                            <p class="text-gray-700">{{ Str::limit(strip_tags($destacada->cuerpo), 220) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </section> --}}

                        {{-- Resto en grilla --}}
                        @if($resto->count())
                            <section class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
                                @foreach ($resto as $n)
                                    <article class="flex flex-col border rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-shadow">
                                        <div class="flex flex-col h-full">
                                            {{-- @if($n->imagen)
                                                <img src="{{ asset('storage/' .$n->imagen) }}"
                                                     alt="Imagen de {{ $n->titulo }}"
                                                     class="w-full h-44 object-cover">
                                            @else
                                                <div class="w-full h-30 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                    <span class="text-gray-500 text-xs">Sin imagen</span>
                                                </div>
                                            @endif --}}

                                            <div class="p-4 flex-1 flex flex-col">
                                                <div class="text-xs text-gray-500 mb-1">
                                                    {{ $n->fecha_publicacion?->format('d/m/Y') ?? '' }}
                                                </div>
                                                <h4 class="text-lg font-bold text-black mb-1 line-clamp-2">
                                                    {{ $n->titulo }}
                                                </h4>
                                                <p class="text-sm text-gray-700 line-clamp-3">
                                                    {{ Str::limit($n->resumen ?: strip_tags($n->cuerpo), 140) }}
                                                </p>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </section>
                        @endif

                        <div class="mt-6">
                            {{ $noticias->links() }}
                        </div>
                    @else
                        <p class="mt-4 text-gray-600">No hay noticias publicadas aún.</p>
                    @endif
                </main>
            </div>
        </div>

        <footer class="bg-black text-white py-10 px-6 mt-12 font-sans w-full">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
                <div class="text-4xl font-extrabold tracking-wide font-serif text-center md:text-left">
                    <div class="md:hidden block text-center text-white text-3xl mb-4">
                        <span class="text-[#fc5648]">RE</span><span class="text-[#eba81d]">VIS</span><span class="text-white">TAS</span>
                    </div>
                    <div class="hidden md:block">
                        <span class="text-[#fc5648]">RE</span><br />
                        <span class="text-[#eba81d]">VIS</span><br />
                        <span class="text-white">TAS</span>
                    </div>
                </div>

                <div class="flex gap-6 flex-wrap justify-center">
                    <div class="flex flex-col items-center text-center">
                        <p class="text-[#eba81d] font-semibold mb-2 font-mono">Mayo</p>
                        <a href="https://drive.google.com/file/d/1b304pV29d66y29Ib36fhY589WE-2fIJn/view?usp=sharing" target="_blank">
                            <img src="/storage/Portada_ED1.jpg" alt="Revista Mayo"
                                 class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                        </a>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <p class="text-[#eba81d] font-semibold mb-2 font-mono">Junio</p>
                        <a href="https://drive.google.com/file/d/1qTuBM4XDMgUnSHh9mKFtj4OeNmjvQ2pd/view?usp=sharing" target="_blank">
                            <img src="/storage/Portada_ED2.jpeg" alt="Revista Junio"
                                 class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                        </a>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <p class="text-[#eba81d] font-semibold mb-2 font-mono">Julio</p>
                        <a href="https://drive.google.com/file/d/1Dj_RuAkSLy-0vzvLMseaw1ggPaakpEQY/view?usp=sharing" target="_blank">
                            <img src="/storage/Portada_ED3.jpeg" alt="Revista Julio"
                                 class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                        </a>
                    </div>
                </div>

                <div class="text-sm mt-6 md:mt-0 font-light text-center md:text-right">
                    <p class="text-gray-300">&copy; {{ date('Y') }} El Pionero de Valparaíso</p>
                    <p class="text-gray-400">Todos los derechos reservados</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
