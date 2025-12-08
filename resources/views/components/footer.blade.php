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

            <!-- Revista de Septiembre -->
            <div class="flex flex-col items-center text-center">
                <p class="text-[#eba81d] font-semibold mb-2 font-mono">Septiembre</p>
                <a href="{{ asset('storage/Ediciones/EPDV_SEPTIEMBRE_2025.pdf') }}"
                    target="_blank">
                    <img src="/storage/Portada_ED5.jpeg" alt="Revista Septiembre"
                        class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                </a>
            </div>

            <!-- Revista de Octubre -->
            <div class="flex flex-col items-center text-center">
                <p class="text-[#eba81d] font-semibold mb-2 font-mono">Octubre</p>
                <a href="{{ asset('storage/Ediciones/EPDV_OCTUBRE_2025.pdf') }}"
                    target="_blank">
                    <img src="/storage/Portada_ED5.jpeg" alt="Revista Octubre"
                        class="w-24 h-auto rounded shadow-md filter grayscale hover:grayscale-0 hover:scale-105 transition duration-300">
                </a>
            </div>

            <!-- Revista de Noviembre -->
            <div class="flex flex-col items-center text-center">
                <p class="text-[#eba81d] font-semibold mb-2 font-mono">Noviembre</p>
                <a href="{{ asset('storage/Ediciones/EPDV_NOVIEMBRE_2025.pdf') }}"
                    target="_blank">
                    <img src="/storage/Portada_ED5.jpeg" alt="Revista Noviembre"
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
