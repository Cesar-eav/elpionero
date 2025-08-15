<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>El Pionero de Valparaíso</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->

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

<body class="bg-gray-100 flex items-center lg:justify-center min-h-screen flex-col">


    <div class="w-full  mx-auto p-4">
        <!-- Encabezado -->
        <header class="text-center mb-1">
            <!-- Visible en pantallas medianas y grandes -->
            <img src="{{ asset('storage/portada.png') }}" class="w-full mx-auto mb-4 rounded shadow  md:block hidden" />

            <!-- Visible solo en móviles -->
            <img src="{{ asset('storage/logo_m.png') }}" class="w-full mx-auto mb-4 rounded shadow  block md:hidden" />
        </header>


        <x-navbar />

        <div class="bg-gray-100 min-h-screen">
            <div class="container mx-auto ">

                <aside class="w-full md:w-2/6 pace-y-6 bg-gray-50 border border-gray-300 rounded-lg p-4 shadow-sm">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Noticias</h2>
                        <div class="mt-4 border-gray-300 border p-2">
                            <h4 class="text-md font-bold text-gray-700 mb-2" >Parque Barón</h4>
                            <p class="text-sm text-gray-700">
                                Aún no hay modelo de gestión para el funcionamiento de la Bodega Simón Bolivar.
                            </p>
                        </div>
                        <div class="mt-4 border-gray-300 border p-2">
                            <h4 class="text-md font-bold text-gray-700 mb-2">Proponen ciclovía en Av. España</h4>
                            <a href="https://www.pucv.cl/pucv/investigadores-proponen" target="_blank">
                                <p class="text-sm text-gray-700">Académicos de la PUCV elaboran propuesta para mejorar
                                    la movilidad entre Viña del Mar
                                    y Valparaíso.
                                </p>
                            </a>
                        </div>



                        <div class="mt-4 border-gray-300 border p-2">
                            <h4 class="text-md font-bold text-gray-700 mb-2">Dos buenas noticias para Valparaíso</h4>
                            <a href="https://www.pucv.cl/pucv/investigadores-proponen" target="_blank">
                                <p class="text-sm text-gray-700">Pronto se inagurará <a
                                        href="https://www.instagram.com/destinovalpo/" target="_blank"
                                        class="font-bold">Destino Valparaíso</a>, un proyecto
                                    que albergará el Museo del Inmigrante. Admeás, <a
                                        href="https://www.instagram.com/bancoestado/reel/DNTO6l1x1_s/" target="_blank"
                                        class="font-bold">Banco Estado</a> ha remodelado el edificio patrimonial de
                                    calle Prat, al cual se podrá acceder mediante un ascensor desde el Paseo Yugoslavo.
                                </p>
                            </a>
                        </div>

                                                <div class="mt-4 border-gray-300 border p-2">
                            <h4 class="text-md font-bold text-gray-700 mb-2">Ruta Fiscalización Ascensores</h4>
                                <p class="text-sm text-gray-700">La agrupación <a
                                        href="https://www.ascenval.cl/" target="_blank"
                                        class="font-bold">Ascenval</a> realizará una nueva ruta de fiscalización este domingo 17 a las 16:00 hrs. El punto de encuentro es la estación baja del ascensor Polanco.
                                   
                                </p>
                        </div>
                <div class="mt-2">
                    <a href="https://www.instagram.com/manos_.alarte/" target="_blank">
                        <img src="{{ asset('storage/manosalarte.jpeg') }}" alt="Anuncio Mediano"
                            class="w-full rounded border shadow" /></a>
                </div>
                </aside>



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


</body>

</html>
