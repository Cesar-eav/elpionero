<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Columnas - {{ now()->format('d de F de Y') }}</title>
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
            <img src="{{ asset('storage/portada.png') }}" alt="Portada de Periódico"
                class="w-full  mx-auto mb-4 rounded shadow" />

        </header>
        <div>

            <x-navbar />


            <!-- Layout principal -->
            <div class="flex flex-col md:flex-row gap-6">


                <!-- Contenido principal -->
 
                <main class="w-full space-y-6 bg-gray-50 border border-gray-300 rounded-lg p-4 shadow-sm">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Columnas de Opinión</h2>

                        @if ($columnas->isNotEmpty())
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
    @foreach ($columnas as $articulo)
        <div class="flex flex-col border rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-shadow">
            <a href="{{ url('articulo/' . $articulo->id) }}" class="flex flex-row h-full">
                
                <!-- Texto -->
                <div class="flex flex-col justify-center p-4 w-2/3">
                    <h4 class="text-lg font-bold text-black mb-2">
                        {{ $articulo->titulo }}
                    </h4>
                    @if ($articulo->autor)
                        <div class="text-sm italic text-gray-600 flex items-center">
                            Por: {{ $articulo->autor }}
                        </div>
                    @endif
                </div>

                <!-- Imagen a la derecha -->
                @if ($articulo->imagen_autor)
                    <div class="w-1/3 flex items-center justify-center bg-gray-100">
                        <img src="{{ asset($articulo->imagen_autor) }}"
                             alt="{{ $articulo->autor ?? 'Autor' }}"
                             class="w-full h-full object-cover" />
                    </div>
                @endif

            </a>
        </div>
    @endforeach
</div>

                        @else
                            <p>No hay artículos en esta revista.</p>
                        @endif
                    </div>

  
                </main>
            </div>



            <footer class="bg-black text-white py-10 px-6 mt-12 font-sans">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
                    <!-- Título -->
                    <div class="text-4xl font-extrabold tracking-wide font-serif text-center md:text-left">
                        <div class="md:hidden block text-center text-white text-3xl mb-4">REVISTAS</div>
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
                            <a href="https://drive.google.com/file/d/1b304pV29d66y29Ib36fhY589WE-2fIJn/view?usp=sharing"
                                target="_blank">
                                <img src="/storage/Portada_ED1.jpg" alt="Revista Mayo"
                                    class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                            </a>
                        </div>
                        <!-- Revista de Junio -->
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#eba81d] font-semibold mb-2 font-mono">Junio</p>
                            <a href="https://drive.google.com/file/d/1qTuBM4XDMgUnSHh9mKFtj4OeNmjvQ2pd/view?usp=sharing"
                                target="_blank">
                                <img src="/storage/Portada_ED2.jpeg" alt="Revista Junio"
                                    class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                            </a>
                        </div>
                        <!-- Revista de Julio -->
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#eba81d] font-semibold mb-2 font-mono">Julio</p>
                            <a href="https://drive.google.com/file/d/1Dj_RuAkSLy-0vzvLMseaw1ggPaakpEQY/view?usp=sharing"
                                target="_blank">
                                <img src="/storage/Portada_ED3.jpeg" alt="Revista Julio"
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
