@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
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
        <!-- Encabezado -->
        <header class="text-center mb-1">
            <!-- Visible en pantallas medianas y grandes -->
            <img src="{{ asset('storage/portada.png') }}" class="w-full mx-auto mb-4 rounded shadow  md:block hidden" />

            <!-- Visible solo en móviles -->
            <img src="{{ asset('storage/logo_m.png') }}" class="w-full mx-auto mb-4 rounded shadow  block md:hidden" />
        </header>



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
                <!-- Sidebar izquierda -->
                <aside
                    class="w-full md:w-2/6 hidden md:block space-y-6 bg-gray-50 border border-gray-300 rounded-lg p-4 shadow-sm">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Noticias</h2>
                        <div class="mt-4 border-gray-300 border p-2">
                            <h4 class="text-md font-bold text-gray-700">Parque Barón</h4>
                            <p class="text-sm text-gray-700">
                                Aún no hay modelo de gestión para el funcionamiento de la Bodega Simón Bolivar.
                            </p>
                        </div>
                        <div class="mt-4 border-gray-300 border p-2">
                            <h4 class="text-md font-bold text-gray-700">Proponen ciclovía en Av. España</h4>
                            <a href="https://www.pucv.cl/pucv/investigadores-proponen" target="_blank">
                                <p class="text-sm text-gray-700">Académicos de la PUCV elaboran propuesta para mejorar
                                    la movilidad entre Viña del Mar
                                    y Valparaíso.
                                </p>
                            </a>
                        </div>

                        <div class="mt-2">
                            <a href="https://www.instagram.com/manos_.alarte/" target="_blank">
                                <img src="{{ asset('storage/manosalarte.jpeg') }}" alt="Anuncio Mediano"
                                    class="w-full rounded border shadow" /></a>
                        </div>

                        <div class="mt-4 border-gray-300 border p-2">
                            <h4 class="text-md font-bold text-gray-700">Dos buenas noticias para Valparaíso</h4>
                            <a href="https://www.pucv.cl/pucv/investigadores-proponen" target="_blank">
                                <p class="text-sm text-gray-700">Pronto se inagurará <a href="https://www.instagram.com/destinovalpo/" target="_blank" class="font-bold">Destino Valparaíso</a>, un proyecto
                                    que albergará el Museo del Inmigrante. Admeás,  <a href="https://www.instagram.com/bancoestado/reel/DNTO6l1x1_s/" target="_blank" class="font-bold">Banco Estado</a>  ha remodelado el edificio patrimonial de calle Prat, al cual se podrá acceder mediante un ascensor desde el Paseo Yugoslavo.
                                </p>
                            </a>
                        </div>

                </aside>

                <!-- Contenido principal -->

                <main class="w-full space-y-6 bg-gray-50 border border-gray-300 rounded-lg p-4 shadow-sm">

                    @if ($columnas->isNotEmpty())
                        @php
                            $destacada = $columnas->last();
                            $resto = $columnas->take(4);
                        @endphp
                        <div>
         
                            <img src="{{ asset('storage/publicidad_movil_2.png') }}" alt="Anuncio Mediano"
                                class="w-full rounded border shadow  block md:hidden" />
                        </div>
                        {{-- DESTACADA --}}
                        <section class="mt-4">
                            <div
                                class="border rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-shadow">
                                <a href="{{ url('articulo/' . $destacada->id) }}" class="flex flex-col md:flex-row">

                                                                        {{-- Imagen a la derecha, grande --}}
                                    @if ($destacada->imagen_autor)
                                        <div class="w-full md:w-1/3 bg-gray-100">
                                            <img src="{{ asset($destacada->imagen_autor) }}"
                                                alt="{{ $destacada->autor ?? 'Autor' }}"
                                                class="w-full h-64 md:h-full object-cover" />
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
                                            {!! nl2br(e(Str::limit($destacada->contenido, 300))) !!}
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
                                        class="flex border rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-shadow">
                                        <a href="{{ url('articulo/' . $articulo->id) }}" class="flex w-full">
                                            {{-- Texto --}}
                                            <div class="w-2/3 p-4 flex flex-col justify-center">
                                                <div class="text-xs md:text-sm text-gray-700 mb-1">
                                                    {{ $articulo->revista->titulo ?? '' }}
                                                </div>
                                                <h4 class="text-lg font-bold text-black mb-1 line-clamp-2">
                                                    {{ $articulo->titulo }}
                                                </h4>
                                                @if ($articulo->autor)
                                                    <div class="text-sm italic text-gray-600">{{ $articulo->autor }}
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- Imagen a la derecha --}}
                                            @if ($articulo->imagen_autor)
                                                <div class="w-1/3 bg-gray-100">
                                                    <img src="{{ asset($articulo->imagen_autor) }}"
                                                        alt="{{ $articulo->autor ?? 'Autor' }}"
                                                        class="w-full h-full object-cover">
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



            <footer class="bg-black text-white py-10 px-6 mt-12 font-sans">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
                    <!-- Título -->
                    <div class="text-4xl font-extrabold tracking-wide font-serif text-center md:text-left">
                        <div class="md:hidden block text-center text-white text-3xl mb-4">
                            <span class="text-[#fc5648]">RE</span><span class="text-[#eba81d]">VIS</span><span
                                class="text-white">TAS</span>
                        </div>

                        <div class="hidden md:block">
                            <span class="text-[#fc5648]">RE</span><br />
                            <span class="text-[#eba81d]">VIS</span><br />
                            <span class="text-white">TAS</span>
                        </div>
                    </div>

                    <!-- Descargas -->
                    <div class="flex gap-6 flex-wrap justify-center">
                        <!-- Revista de Mayo -->
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#eba81d] font-semibold mb-2 font-mono">Mayo</p>
                            <a href="{{ asset('storage/Ediciones/EPDV_MAYO_2025.pdf') }}"
                                target="_blank">
                                <img src="/storage/Portada_ED1.jpg" alt="Revista Mayo"
                                    class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                            </a>
                        </div>
                        <!-- Revista de Junio -->
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#eba81d] font-semibold mb-2 font-mono">Junio</p>
                            <a href="{{ asset('storage/Ediciones/EPDV_JUNIO_2025.pdf') }}"
                                target="_blank">
                                <img src="/storage/Portada_ED2.jpeg" alt="Revista Junio"
                                    class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                            </a>
                        </div>
                        <!-- Revista de Julio -->
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#eba81d] font-semibold mb-2 font-mono">Julio</p>
                            <a href="{{ asset('storage/Ediciones/EPDV_JULIO_2025.pdf') }}"
                                target="_blank">
                                <img src="/storage/Portada_ED3.jpeg" alt="Revista Julio"
                                    class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                            </a>
                        </div>

                        <!-- Revista de Julio -->
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#eba81d] font-semibold mb-2 font-mono">Septiembre</p>
                            <a href="{{ asset('storage/Ediciones/EPDV_SEPTIEMBRE_2025.pdf') }}"
                                target="_blank">
                                <img src="/storage/Portada_ED5.jpeg" alt="Revista Julio"
                                    class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                            </a>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="text-sm mt-6 md:mt-0 font-light text-center md:text-right">
                        <p class="text-gray-300">&copy; {{ date('Y') }} El Pionero de Valparaíso</p>
                        <p class="text-gray-400">Todos los derechos reservados</p>
                    </div>
                </div>
            </footer>





            <!-- Pie de página -->
            <footer class="text-center text-sm text-gray-600 mt-10 pt-4 border-t border-gray-300">

            </footer>


            <div class="fixed bottom-2 right-2 text-xs text-gray-400">1</div>
        </div>
</body>

</html>
