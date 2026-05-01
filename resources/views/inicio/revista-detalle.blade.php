<!DOCTYPE html>
<html lang="es">
<head>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7119582402031511" crossorigin="anonymous"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $revista->titulo }} · El Pionero de Valparaíso</title>
    <meta name="description" content="{{ Str::limit($revista->descripcion, 160) }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (app()->environment('production'))
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y)})(window,document,"clarity","script","rsqwi6wyvd");
        </script>
    @endif
</head>

<body class="bg-gray-100 text-gray-900 font-serif text-base">
<div class="w-full mx-auto p-4">
    <x-header />
    <x-navbar />

    <div class="max-w-5xl mx-auto mt-6 space-y-8">

        {{-- Cabecera de la revista --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-[#fc5648] via-[#eba81d] to-[#fc5648]"></div>

            <div class="flex flex-col sm:flex-row gap-0">
                {{-- Portada --}}
                @if ($revista->portada)
                    <div class="sm:w-48 flex-shrink-0">
                        <img src="{{ asset('storage/' . $revista->portada) }}"
                             alt="Portada {{ $revista->titulo }}"
                             class="w-full h-64 sm:h-full object-cover object-top" />
                    </div>
                @endif

                {{-- Info --}}
                <div class="flex flex-col justify-center p-6 sm:p-8 flex-1">
                    <p class="text-xs text-[#fc5648] font-semibold uppercase tracking-widest mb-2">
                        Edición · {{ $revista->fecha_publicacion?->translatedFormat('F Y') ?? '' }}
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight mb-3">
                        {{ $revista->titulo }}
                    </h1>
                    @if ($revista->descripcion)
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $revista->descripcion }}</p>
                    @endif
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-px bg-[#eba81d] inline-block"></span>
                            {{ $articulos->count() }} {{ Str::plural('columna', $articulos->count()) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Listado de artículos --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-700 border-b border-gray-300 pb-2 mb-4">
                Columnas de esta edición
            </h2>

            @if ($articulos->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                    @foreach ($articulos as $articulo)
                        @php $impar = $loop->index % 2 === 0; @endphp
                        <a href="{{ url('articulo/' . $articulo->slug) }}"
                           class="group flex items-stretch bg-white rounded-xl shadow hover:shadow-md transition-all duration-300 overflow-hidden
                                  border-l-4 {{ $impar ? 'border-[#fc5648]' : 'border-[#eba81d]' }}">

                            {{-- Foto columnista en B&N --}}
                            <div class="relative w-24 flex-shrink-0 bg-gray-200 overflow-hidden">
                                @if ($articulo->columnista && $articulo->columnista->foto)
                                    <img src="{{ asset('storage/' . $articulo->columnista->foto) }}"
                                         alt="{{ $articulo->columnista->nombre ?? '' }}"
                                         class="w-full h-full object-cover object-top grayscale group-hover:grayscale-0 transition-all duration-500" />
                                @else
                                    <div class="w-full h-full bg-gradient-to-b from-gray-300 to-gray-400"></div>
                                @endif
                                {{-- Número correlativo --}}
                                <span class="absolute bottom-1.5 right-1.5 text-xs font-black text-white/80 leading-none"
                                      style="text-shadow: 0 1px 3px rgba(0,0,0,0.6);">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            {{-- Contenido --}}
                            <div class="flex-1 min-w-0 p-4 flex flex-col justify-center gap-1">
                                @if ($articulo->columnista)
                                    <p class="text-xs font-semibold uppercase tracking-widest
                                               {{ $impar ? 'text-[#fc5648]' : 'text-[#eba81d]' }}">
                                        {{ $articulo->columnista->nombre }}
                                    </p>
                                @endif
                                <h3 class="font-bold text-gray-900 leading-snug line-clamp-3 group-hover:text-[#fc5648] transition-colors duration-300 text-sm md:text-base">
                                    {{ $articulo->titulo }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="w-5 h-px {{ $impar ? 'bg-[#fc5648]' : 'bg-[#eba81d]' }}"></span>
                                    <span class="text-xs text-gray-400">{{ $articulo->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center pr-3 text-gray-300 group-hover:text-[#fc5648] transition-colors duration-300 text-lg flex-shrink-0">→</div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">Esta edición aún no tiene columnas publicadas.</p>
            @endif
        </div>

        <div class="text-center">
            <a href="{{ route('revistas.lista') }}" class="text-sm text-[#fc5648] hover:underline">
                ← Ver todas las ediciones
            </a>
        </div>
    </div>

    <x-footer />
</div>
</body>
</html>
