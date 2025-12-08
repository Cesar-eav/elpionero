<!-- Barra de navegación -->
<div class="flex w-full font-mono items-center justify-between bg-black border border-gray-300 text-[#fc5648] rounded-md px-6 py-3 md:mb-8 mb-0 sticky top-0 z-50 text-lg font-semibold">

    <!-- Logo o título -->
    <a href="{{ url('/') }}" class="hover:text-white transition-colors flex items-center gap-1">
        Inicio
    </a>

    <!-- Navegación escritorio -->
    <nav class="hidden md:flex gap-4">
        <a href="{{ url('columnas') }}" class="hover:text-white transition-colors">Columnas</a>
        <a href="{{ url('editoriales') }}" class="hover:text-white transition-colors">Editoriales</a>
        <a href="{{ url('noticias') }}" class="hover:text-white transition-colors">Noticias</a>
        <a href="{{ url('entrevistas') }}" class="hover:text-white transition-colors">Entrevistas</a>
        <a href="{{ url('revistas-lista') }}" class="hover:text-white transition-colors">Revistas</a>
        <a href="{{ url('nosotros') }}" class="hover:text-white transition-colors">Nosotros</a>
    </nav>

    <!-- Botón hamburguesa (solo en móvil) -->
    <button id="mobile-menu-button" class="md:hidden text-[#fc5648] hover:text-white focus:outline-none">
        <!-- Ícono hamburguesa -->
        <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <!-- Ícono cerrar -->
        <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Menú móvil -->
    <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-black text-[#fc5648] border-t border-gray-300 md:hidden flex-col p-4 space-y-2">
        <a href="{{ url('columnas') }}" class="hover:text-white transition-colors">Columnas</a>
        <a href="{{ url('editoriales') }}" class="hover:text-white transition-colors">Editoriales</a>
        <a href="{{ url('noticias') }}" class="hover:text-white transition-colors">Noticias</a>
        <a href="{{ url('entrevistas') }}" class="hover:text-white transition-colors">Entrevistas</a>
        <a href="{{ url('revistas-lista') }}" class="hover:text-white transition-colors">Revistas</a>
        <a href="{{ url('nosotros') }}" class="hover:text-white transition-colors">Nosotros</a>
    </div>
</div>

<script>
    // JavaScript puro para el menú móvil
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        if (menuButton && mobileMenu && menuIcon && closeIcon) {
            menuButton.addEventListener('click', function() {
                // Toggle del menú
                const isOpen = !mobileMenu.classList.contains('hidden');

                if (isOpen) {
                    // Cerrar menú
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('flex');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                } else {
                    // Abrir menú
                    mobileMenu.classList.remove('hidden');
                    mobileMenu.classList.add('flex');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                }
            });

            // Cerrar el menú al hacer clic fuera de él
            document.addEventListener('click', function(event) {
                const isClickInside = menuButton.contains(event.target) || mobileMenu.contains(event.target);

                if (!isClickInside && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('flex');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });
        }
    });
</script>
