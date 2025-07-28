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

</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:pl-3 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        {{-- @if (Route::has('login')) --}}
        {{-- <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav> --}}
        {{-- @endif --}}
    </header>

    <div class="fixed bottom-0 left-0 w-full py-2 px-4 md:hidden z-50">
        <a href="https://drive.google.com/file/d/1Dj_RuAkSLy-0vzvLMseaw1ggPaakpEQY/view?usp=sharing" download
            target="_blank"
            class="block w-full text-center bg-red-600 hover:bg-blue-700 text-white font-bold py-2 rounded">
            Descargar Revista
        </a>
    </div>

    <div class="fixed top-0 right-0 w-40 py-2 px-4 z-50">
        <a href="{{ route('contacto.formulario') }}"
            class="block w-full text-center bg-black hover:bg-blue-700 text-white font-bold py-2 rounded">
            Contáctanos
        </a>
    </div>

    <div class="bg-gray-100 min-h-screen py-8">
        <div class="container mx-auto px-4 md:px-8 lg:px-12">
            <div class="lg:flex lg:items-start lg:justify-between">

                <div class="flex mb-8 lg:mb-0 lg:w-1/3  justify-center ">
                    <img src="/storage/pionero_grande.png" alt="Logo El Pionero de Valparaíso"
                        class="logo w-60 md:w-full md:max-w-full h-auto">
                    {{-- <p >Decidimos tomar la consigna "Menos postales, más realidad", creadad por la Agrupación de Usuarios de Ascensores, por signifcar una aspiración</p> --}}
                </div>

                <div class="lg:w-2/3 lg:ml-8">
                    <h1
                        class="md:text-center text-center text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-4">
                        El Pionero de Valparaíso</h1>
                    <h2 class="text-xl md:text-2xl text-gray-600 mb-6">¡Menos postales, más realidad!</h2>

                    <div class="mb-8 text-gray-700">
                        <p class="mb-4">El Pionero de Valparaíso nace como un esfuerzo ciudadano por abordar los
                            desafíos que enfrenta nuestra querida ciudad puerto. Cansados de la retórica vacía y las
                            soluciones superficiales, buscamos ser un catalizador para el debate profundo y la propuesta
                            de ideas concretas que realmente impacten en la calidad de vida de sus habitantes.</p>
                        <p class="mb-4">Creemos firmemente en el potencial de Valparaíso, en su riqueza cultural, su
                            historia y su gente.
                            Sin embargo, reconocemos los problemas que nos aquejan: el descuido de los espacios
                            públicos, el transporte público, el deterioro del patrimonio, el empleo precario
                            y muchos otros. Nuestra revista mensual se dedicará a explorar estos temas con rigor,
                            buscando voces diversas y, sobre todo, enfocándonos en las posibles vías de solución.</p>

                        <p>Con la convicción de que <strong>un cambio de rumbo es esencial para construir un camino de
                                progreso que traiga calidad de vida</strong>, aspiramos a ser un espacio donde las ideas
                            se traduzcan en acciones concretas. Invitamos a expertos y académicos,
                            a líderes comunitarios y a la voz de cada habitante de Valparaíso a unirse a este diálogo
                            constructivo para edificar
                            colectivamente un futuro más próspero y equitativo para nuestra querida ciudad.</p>
                    </div>

                    <div class="flex bg-gray-200 border border-gray-300 rounded-md p-6 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Revista Nº1</h3>
                            <div
                                class="w-32 h-48 bg-gray-400 rounded-md shadow-md flex items-center justify-center text-white font-bold">
                                <a href="https://drive.google.com/file/d/1b304pV29d66y29Ib36fhY589WE-2fIJn/view?usp=sharing"
                                    target="_blank">
                                    <img src="/storage/Portada_ED1.jpg">
                                </a>
                            </div>
                        </div>

                        <div class="px-2">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Revista Nº2</h3>
                            <div
                                class="w-32 h-48 bg-gray-400 rounded-md shadow-md flex items-center justify-center text-white font-bold">
                                <a href="https://drive.google.com/file/d/1qTuBM4XDMgUnSHh9mKFtj4OeNmjvQ2pd/view?usp=sharing"
                                    target="_blank">
                                    <img src="/storage/Portada_ED2.jpeg">
                                </a>
                            </div>
                        </div>

                        <div class="px-2">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Revista Nº3</h3>
                            <div
                                class="w-32 h-48 bg-gray-400 rounded-md shadow-md flex items-center justify-center text-white font-bold">
                                <a href="https://drive.google.com/file/d/1Dj_RuAkSLy-0vzvLMseaw1ggPaakpEQY/view?usp=sharing"
                                    target="_blank">
                                    <img src="/storage/Portada_ED3.jpeg">
                                </a>
                            </div>
                        </div>


                    </div>

                    <div class="mt-8 text-center lg:text-left hidden md:block">
                        <a href="https://drive.google.com/file/d/1Dj_RuAkSLy-0vzvLMseaw1ggPaakpEQY/view?usp=sharing" 
                            target="_blank"
                            class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-full shadow-md transition duration-300">Descargar
                            Edición Actual</a>
                        <a href="#"
                            class="inline-block ml-4 text-gray-700 hover:text-gray-900 font-medium">Archivo de
                            Revistas</a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        const slider = document.getElementById('imageSlider');
        let counter = 0;
        const slideWidth = slider.children[0].offsetWidth; // Get width of first image

        function nextSlide() {
            counter++;
            if (counter >= slider.children.length) {
                counter = 0;
            }
            slider.style.transform = `translateX(-${counter * slideWidth}px)`;
        }

        function prevSlide() {
            counter--;
            if (counter < 0) {
                counter = slider.children.length - 1;
            }
            slider.style.transform = `translateX(-${counter * slideWidth}px)`;
        }

        // Auto slide (optional)
        setInterval(nextSlide, 5000);
    </script>


    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>
