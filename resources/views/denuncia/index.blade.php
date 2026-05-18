<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Denuncias - El Pionero de Valparaíso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .denuncia-card:hover .image-overlay { opacity: 1; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-slate-900 font-sans antialiased">
    @include('components.navbar')

    <main class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Header Compacto y Moderno -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-10 gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900">
                    Monitor <span class="text-[#fc5648]">Territorial</span>
                </h1>
                <p class="text-slate-500 font-medium">Valparaíso: Reportes de infraestructura y comunidad en tiempo real.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex bg-white rounded-xl p-1 shadow-sm border border-slate-200">
                    <button class="px-4 py-2 bg-slate-100 rounded-lg text-xs font-bold uppercase tracking-wider text-slate-600">Recientes</button>
                    <button class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-slate-600">Mapa</button>
                </div>
                <a href="{{ route('denuncia.formulario') }}" 
                   class="bg-slate-900 hover:bg-[#fc5648] text-white px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Nuevo Reporte
                </a>
            </div>
        </div>

        @if ($denuncias->isEmpty())
            <!-- Estado vacío optimizado -->
            <div class="bg-white rounded-[3rem] p-20 text-center border border-slate-200 shadow-xl">
                <div class="text-6xl mb-4">⚓</div>
                <h2 class="text-2xl font-bold">Todo despejado en el puerto</h2>
                <p class="text-slate-500">No hay denuncias pendientes de revisión en este momento.</p>
            </div>
        @else
            <!-- Grilla de Denuncias -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach ($denuncias as $denuncia)
                    <article class="denuncia-card bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 overflow-hidden group">
                        
                        <div class="flex flex-col h-full">
                            <!-- Header de la Card: Ubicación y Tiempo -->
                            <div class="p-6 pb-0 flex justify-between items-center">
                                <div class="flex items-center gap-2 bg-[#fc5648]/10 text-[#fc5648] px-3 py-1.5 rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    <span class="text-xs font-black uppercase tracking-widest">{{ $denuncia->ubicacion }}</span>

                                </div>

                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter italic">
                                    Hace {{ $denuncia->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>

                            <!-- Layout de Contenido Mixto -->
                            <div class="p-6 flex flex-col md:flex-row gap-6 h-full">
                                
                                <!-- Columna Izquierda: Imágenes -->
                                @php
                                    $imgs = array_values(array_filter([$denuncia->imagen1, $denuncia->imagen2, $denuncia->imagen3]));
                                    $imgsJson = json_encode($imgs);
                                @endphp
                                <div class="md:w-5/12 flex flex-col gap-2">
                                    <div class="relative h-48 rounded-2xl overflow-hidden shadow-inner cursor-pointer"
                                         onclick="abrirLightbox({{ $imgsJson }}, 0)">
                                        <img src="/storage/{{ $denuncia->imagen1 }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Principal">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                                            <span class="text-white text-[10px] font-bold uppercase">Ver fotos</span>
                                        </div>
                                    </div>
                                    @if(count($imgs) > 1)
                                    <div class="grid grid-cols-2 gap-2">
                                        @if(isset($imgs[1]))
                                        <div class="h-20 rounded-xl overflow-hidden border border-slate-100 cursor-pointer"
                                             onclick="abrirLightbox({{ $imgsJson }}, 1)">
                                            <img src="/storage/{{ $imgs[1] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" alt="E2">
                                        </div>
                                        @endif
                                        @if(isset($imgs[2]))
                                        <div class="h-20 rounded-xl overflow-hidden border border-slate-100 cursor-pointer relative"
                                             onclick="abrirLightbox({{ $imgsJson }}, 2)">
                                            <img src="/storage/{{ $imgs[2] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" alt="E3">
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <!-- Columna Derecha: Texto y Acciones -->
                                <div class="md:w-7/12 flex flex-col justify-between">
                                    <a href="{{ route('denuncia.show', $denuncia) }}" >
                                    <div>
                                        <h2 class="text-xl font-black text-slate-900 leading-tight mb-3 group-hover:text-[#fc5648] transition-colors line-clamp-2">
                                            {{ $denuncia->titulo }}
                                        </h2>
                                         
                                        <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4">
                                            {{ $denuncia->descripcion }}
                                        </p>
                                    </div>
                                </a>

                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3">
                                            <p class="text-xs font-bold text-slate-500">
                                                Por <span class="text-slate-800">{{ $denuncia->nombre ?? 'Vecino Anónimo' }}</span>
                                            </p>
                                        </div>
                                        
                                        <span class="text-[10px] font-black text-slate-600 pr-10 leading-tight ">
                                        🗓️ Fecha: {{ $denuncia->created_at->format('d-m-Y') }}
                                        </span>
                                        <a href="{{ route('denuncia.show', $denuncia) }}" 
                                           class="w-full block text-center bg-slate-50 hover:bg-[#fc5648] hover:text-white text-slate-900 font-bold py-3 rounded-xl text-xs uppercase tracking-widest transition-all">
                                            Analizar Reporte Completo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Paginación Estilizada -->
            @if ($denuncias->hasPages())
                <div class="mt-16 mb-20 flex justify-center">
                    <nav class="inline-flex bg-white rounded-2xl shadow-sm border border-slate-200 p-2 overflow-hidden">
                        {{ $denuncias->links() }}
                    </nav>
                </div>
            @endif
        @endif
    </main>

    <!-- Floating Action Button (Mobile Only) -->
    <a href="{{ route('denuncia.formulario') }}"
       class="md:hidden fixed bottom-6 right-6 w-16 h-16 bg-[#fc5648] text-white rounded-full flex items-center justify-center shadow-2xl z-50 animate-bounce">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
    </a>

    <!-- Lightbox -->
    <div id="lb" class="fixed inset-0 bg-black/95 z-50 flex items-center justify-center" style="display:none!important">
        <button onclick="cerrarLightbox()" class="absolute top-4 right-4 text-white text-2xl w-11 h-11 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full transition-colors">✕</button>
        <button onclick="prevImg()" id="lb-prev" class="absolute left-3 md:left-6 text-white text-4xl w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full transition-colors">‹</button>
        <img id="lb-img" src="" alt="" class="max-w-[85vw] max-h-[85vh] object-contain rounded-xl shadow-2xl select-none">
        <button onclick="nextImg()" id="lb-next" class="absolute right-3 md:right-6 text-white text-4xl w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full transition-colors">›</button>
        <span id="lb-counter" class="absolute bottom-5 text-white/60 text-sm font-bold tracking-widest"></span>
    </div>

    <script>
        let lbImgs = [], lbIdx = 0;

        function abrirLightbox(imgs, idx) {
            lbImgs = imgs;
            lbIdx = idx;
            actualizarLightbox();
            document.getElementById('lb').style.cssText = 'display:flex!important';
            document.body.style.overflow = 'hidden';
        }

        function cerrarLightbox() {
            document.getElementById('lb').style.cssText = 'display:none!important';
            document.body.style.overflow = '';
        }

        function actualizarLightbox() {
            document.getElementById('lb-img').src = '/storage/' + lbImgs[lbIdx];
            document.getElementById('lb-counter').textContent = (lbIdx + 1) + ' / ' + lbImgs.length;
            document.getElementById('lb-prev').style.display = lbImgs.length > 1 ? 'flex' : 'none';
            document.getElementById('lb-next').style.display = lbImgs.length > 1 ? 'flex' : 'none';
        }

        function prevImg() { lbIdx = (lbIdx - 1 + lbImgs.length) % lbImgs.length; actualizarLightbox(); }
        function nextImg() { lbIdx = (lbIdx + 1) % lbImgs.length; actualizarLightbox(); }

        document.getElementById('lb').addEventListener('click', e => { if (e.target === document.getElementById('lb')) cerrarLightbox(); });
        document.addEventListener('keydown', e => {
            if (document.getElementById('lb').style.display.includes('none')) return;
            if (e.key === 'Escape') cerrarLightbox();
            if (e.key === 'ArrowLeft') prevImg();
            if (e.key === 'ArrowRight') nextImg();
        });
    </script>
</body>
</html>