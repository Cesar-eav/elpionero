<!DOCTYPE html>
<html lang="es">

<head>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7119582402031511"
     crossorigin="anonymous"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>El Pionero de Valparaíso</title>
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
    <div class="w-full  mx-auto p-4">
        <x-header />

        <div>

            <x-navbar />

            <!-- Newsletter inteligente con Alpine.js -->
            <section x-data="{
                abierto: localStorage.getItem('newsletterOculto') !== 'true',
                successVisible: true
            }" x-show="abierto" x-transition
                class="relative bg-white border border-gray-300 shadow-md rounded-lg my-8 p-4 md:p-6 flex flex-col md:flex-row items-center justify-between gap-4 max-w-7xl mx-auto"
                x-init="@if (session('success')) setTimeout(() => successVisible = false, 4000); @endif">
                <!-- Botón cerrar -->
                <button
                    @click="
            abierto = false;
            localStorage.setItem('newsletterOculto', 'true');
        "
                    class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl font-bold">
                    &times;
                </button>

                <!-- Título y texto -->
                <div class="text-center md:text-left">
                    <h2 class="text-xl font-bold text-gray-800">📬 Newsletter</h2>
                    <p class="text-gray-600 text-sm">Suscríbete y recibe las columnas más recientes de El Pionero en tu
                        correo.</p>
                </div>

                <!-- Formulario -->
                <form method="POST" action="{{ route('newsletter.subscribe') }}"
                    class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                    @csrf
                    <input type="email" name="email" required placeholder="Tu correo electrónico"
                        class="w-full sm:w-72 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-[#fc5648]" />
                    <button type="submit"
                        class="bg-[#fc5648] text-white px-5 py-2 rounded hover:bg-[#d94439] transition-colors">
                        Suscribirse
                    </button>
                </form>

                <!-- Mensaje de éxito -->
                @if (session('success'))
                    <div x-show="successVisible" x-transition
                        class="absolute bottom-[-1.5rem] left-4 text-green-600 text-sm mt-2 font-semibold">
                        {{ session('success') }}
                    </div>
                @endif
            </section>



            <!-- Layout principal -->
            <div class="flex flex-col md:flex-row gap-6">


                <!-- Contenido principal -->

                <main class="w-full space-y-6 bg-gray-50 border border-gray-300 rounded-lg p-4 shadow-sm">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Columnas de Opinión</h2>
@if ($columnas->isNotEmpty())
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mt-4">
        @foreach ($columnas as $articulo)
            <div class="group rounded-xl overflow-hidden shadow hover:shadow-lg transition-all duration-300 bg-black">

                {{-- Link al artículo: solo la imagen --}}
                <a href="{{ url('articulo/' . $articulo->slug) }}" class="block">
                    <div class="relative aspect-square md:aspect-[10/7] overflow-hidden">

                        @if ($articulo->columnista && $articulo->columnista->foto)
                            <img src="{{ asset('storage/' . $articulo->columnista->foto) }}"
                                 alt="{{ $articulo->columnista->nombre }}"
                                 class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105" />
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#fc5648] to-[#eba81d]"></div>
                        @endif

                        <div class="absolute inset-0 pointer-events-none"
                             style="background: linear-gradient(to top, rgba(0,0,0,0.85), rgba(0,0,0,0.25), transparent);">
                        </div>

                        <div class="absolute bottom-0 left-0 right-0 p-3">
                            <div style="width:20px; height:2px; background:#eba81d;" class="mb-1.5"></div>
                            <p class="text-white/70 uppercase tracking-wider mb-1"
                               style="font-size:10px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $articulo->revista->titulo ?? '' }}
                            </p>
                            <h4 class="text-white font-bold leading-snug line-clamp-3 md:line-clamp-2 text-xs md:text-md">
                                {{ $articulo->titulo }}
                            </h4>
                        </div>
                    </div>
                </a>

                {{-- Link al columnista: barra inferior --}}
                @if ($articulo->columnista)
                    <a href="{{ route('columnista.show', $articulo->columnista->id) }}"
                       class="flex items-center gap-2 px-3 py-2 bg-gray-900 hover:bg-gray-800 transition-colors">
                        <span style="width:14px; height:1px; background:#eba81d; flex-shrink:0;"></span>
                        <p class="italic text-xs truncate" style="color:#eba81d;">
                            {{ $articulo->columnista->nombre }}
                        </p>
                    </a>
                @endif

            </div>
        @endforeach
    </div>
@else
    <p>No hay artículos en esta revista.</p>
@endif
                    </div>


                </main>
            </div>



            <x-footer />



            <!-- Pie de página -->
            <footer class="text-center text-sm text-gray-600 mt-10 pt-4 border-t border-gray-300">

            </footer>


            <div class="fixed bottom-2 right-2 text-xs text-gray-400">1</div>
        </div>
</body>

</html>
