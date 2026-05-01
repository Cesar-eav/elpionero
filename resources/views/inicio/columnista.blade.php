<!DOCTYPE html>
<html lang="es">

<head>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7119582402031511"
     crossorigin="anonymous"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $columnista->nombre }} · El Pionero de Valparaíso</title>
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
    <div class="w-full mx-auto p-4">
        <x-header />
        <x-navbar />

        <div class="max-w-5xl mx-auto mt-6 space-y-6">

            {{-- Perfil del columnista --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-[#fc5648] via-[#eba81d] to-[#fc5648]"></div>
                <div class="flex flex-col sm:flex-row gap-6 p-6">
                    @if ($columnista->foto)
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <img src="{{ asset('storage/' . $columnista->foto) }}"
                                 alt="{{ $columnista->nombre }}"
                                 class="w-32 h-32 sm:w-40 sm:h-40 object-cover object-top rounded-xl shadow" />
                        </div>
                    @endif
                    <div class="flex flex-col justify-center">
                        <p class="text-xs text-[#fc5648] font-semibold uppercase tracking-widest mb-1">Columnista</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $columnista->nombre }}</h1>
                        @if ($columnista->bio)
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $columnista->bio }}</p>
                        @endif
                        @if ($columnista->email)
                            <p class="mt-2 text-xs text-gray-400">{{ $columnista->email }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Columnas del columnista --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-700 border-b border-gray-300 pb-2 mb-4">
                    Columnas de {{ $columnista->nombre }}
                    <span class="text-sm font-normal text-gray-400">({{ $articulos->count() }})</span>
                </h2>

                @if ($articulos->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        @foreach ($articulos as $articulo)
                            @php $impar = $loop->index % 2 === 0; @endphp
                            <a href="{{ url('articulo/' . $articulo->slug) }}"
                               class="group flex items-start gap-4 bg-white rounded-xl shadow hover:shadow-md transition-all duration-300 p-5
                                      border-l-4 {{ $impar ? 'border-[#fc5648]' : 'border-[#eba81d]' }}">

                                {{-- Número decorativo --}}
                                <span class="text-4xl font-black leading-none flex-shrink-0 w-10 text-right transition-colors duration-300
                                             {{ $impar ? 'text-[#fc5648]/20 group-hover:text-[#fc5648]/50' : 'text-[#eba81d]/30 group-hover:text-[#eba81d]/70' }}">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                {{-- Contenido --}}
                                <div class="flex-1 min-w-0">
                                    @if ($articulo->revista)
                                        <p class="text-xs font-semibold uppercase tracking-widest mb-1
                                                   {{ $impar ? 'text-[#fc5648]' : 'text-[#eba81d]' }}">
                                            {{ $articulo->revista->titulo }}
                                        </p>
                                    @endif
                                    <h3 class="font-bold text-gray-900 leading-snug line-clamp-3 mb-2 group-hover:text-[#fc5648] transition-colors duration-300
                                               text-sm md:text-base">
                                        {{ $articulo->titulo }}
                                    </h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="w-6 h-px {{ $impar ? 'bg-[#fc5648]' : 'bg-[#eba81d]' }}"></span>
                                        <span class="text-xs text-gray-400">
                                            {{ $articulo->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Flecha --}}
                                <span class="text-gray-200 group-hover:text-[#fc5648] transition-colors duration-300 text-lg leading-none flex-shrink-0 mt-1">→</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Este columnista aún no tiene columnas publicadas.</p>
                @endif
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('inicio.columnas') }}" class="text-sm text-[#fc5648] hover:underline">
                    ← Ver todas las columnas
                </a>
            </div>
        </div>

        <x-footer />
    </div>
</body>

</html>
