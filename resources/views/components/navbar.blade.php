<!-- Barra de navegación -->
<div 
    x-data="{ open: false }" 
    class="flex w-full font-mono items-center justify-between bg-black border border-gray-300 text-[#fc5648] rounded-md px-6 py-3 mb-8 sticky top-0 z-50 text-lg font-semibold">

    <!-- Logo o título -->
    <a href="{{ url('/') }}" class="hover:text-white transition-colors flex items-center gap-1">
        El Pionero
    </a>

    <!-- Navegación escritorio -->
    <nav class="hidden md:flex gap-4">
        <a href="{{ url('columnas') }}" class="hover:text-white transition-colors">Columnas</a>
        <a href="{{ url('nosotros') }}" class="hover:text-white transition-colors">Nosotros</a>
        <a href="#" class="hover:text-white transition-colors">Rincón Wanderino</a>
        <a href="#" class="hover:text-white transition-colors">Editoriales</a>
        <a href="{{ route('contacto.formulario') }}" class="hover:text-white transition-colors">Contáctanos</a>

    </nav>

    <!-- Botón hamburguesa (solo en móvil) -->
    <button @click="open = !open" class="md:hidden text-[#fc5648] hover:text-white focus:outline-none">
        <!-- Ícono hamburguesa -->
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <!-- Ícono cerrar -->
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Menú móvil -->
    <div 
        x-show="open"
        x-transition
        class="absolute top-full left-0 w-full bg-black text-[#fc5648] border-t border-gray-300 md:hidden flex flex-col p-4 space-y-2">
        <a href="{{ url('/previsualizar-revista/pdf') }}" class="hover:text-white transition-colors">Inicio</a>
        <a href="{{ route('contacto.formulario') }}" class="hover:text-white transition-colors">Contáctanos</a>
        <a href="{{ url('nosotros') }}" class="hover:text-white transition-colors">Nosotros</a>
        <a href="#" class="hover:text-white transition-colors">Rincón Wanderino</a>
        <a href="#" class="hover:text-white transition-colors">Editoriales</a>
    </div>
</div>
