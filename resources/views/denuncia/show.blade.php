<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $denuncia->titulo }} - El Pionero de Valparaíso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] text-slate-900 font-sans antialiased">
    @include('components.navbar')

    <div class="max-w-3xl mx-auto px-4 py-8 md:py-12">

        <!-- Volver -->
        <a href="{{ route('denuncia.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-[#fc5648] transition-colors mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a denuncias
        </a>

        <article class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">

            <!-- Galería -->
            @php
                $imagenes = array_filter([$denuncia->imagen1, $denuncia->imagen2, $denuncia->imagen3]);
            @endphp

            @if (count($imagenes) > 0)
                <div class="grid {{ count($imagenes) === 1 ? 'grid-cols-1' : (count($imagenes) === 2 ? 'grid-cols-2' : 'grid-cols-3') }} gap-1 p-2">
                    @foreach ($imagenes as $idx => $imagen)
                        <div class="overflow-hidden rounded-2xl {{ count($imagenes) > 1 && $idx === 0 ? 'col-span-full' : '' }} aspect-video cursor-pointer"
                             onclick="abrirLightbox({{ $idx }})">
                            <img
                                src="/storage/{{ $imagen }}"
                                alt="Evidencia {{ $idx + 1 }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Contenido -->
            <div class="p-8 md:p-10">

                <!-- Badge verificado + fecha -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full border border-green-100">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        Verificado
                    </span>
                    <time class="text-xs text-slate-400 uppercase tracking-widest">
                        {{ $denuncia->approved_at?->diffForHumans() ?? $denuncia->created_at->diffForHumans() }}
                    </time>
                </div>

                <!-- Título -->
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-6">
                    {{ $denuncia->titulo }}
                </h1>

                <!-- Ubicación -->
                <div class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100 w-fit mb-8">
                    <svg class="w-4 h-4 text-[#fc5648]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-bold text-slate-700">{{ $denuncia->ubicacion }}</span>
                </div>

                <!-- Descripción completa -->
                <div class="text-slate-700 text-lg leading-relaxed mb-8 whitespace-pre-line">{{ $denuncia->descripcion }}</div>

                <!-- Pie: autor -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Enviado por</p>
                            <p class="text-sm font-bold text-slate-700">{{ $denuncia->nombre ?? 'Vecino Anónimo' }}</p>
                        </div>
                    </div>

                    <!-- Aviso 24 horas -->
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Revisado en menos de 24 hs
                    </div>
                </div>
            </div>
        </article>

        <!-- CTA -->
        <div class="mt-8 text-center">
            <a href="{{ route('denuncia.formulario') }}" class="inline-flex items-center gap-2 bg-[#fc5648] hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Enviar tu denuncia
            </a>
        </div>

    </div>

    <!-- Lightbox -->
    <div id="lb" class="fixed inset-0 bg-black/95 z-50 flex items-center justify-center" style="display:none!important">
        <button onclick="cerrarLightbox()" class="absolute top-4 right-4 text-white text-2xl w-11 h-11 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full transition-colors">✕</button>

        <button onclick="prevImg()" class="absolute left-3 md:left-6 text-white text-4xl w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full transition-colors" id="lb-prev">‹</button>

        <img id="lb-img" src="" alt="" class="max-w-[85vw] max-h-[85vh] object-contain rounded-xl shadow-2xl select-none">

        <button onclick="nextImg()" class="absolute right-3 md:right-6 text-white text-4xl w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full transition-colors" id="lb-next">›</button>

        <span id="lb-counter" class="absolute bottom-5 text-white/60 text-sm font-bold tracking-widest"></span>
    </div>

    <script>
        const lbImgs = @json(array_values($imagenes));
        let lbIdx = 0;

        function abrirLightbox(idx) {
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

        document.getElementById('lb').addEventListener('click', function(e) {
            if (e.target === this) cerrarLightbox();
        });

        document.addEventListener('keydown', e => {
            if (document.getElementById('lb').style.display === 'none!important') return;
            if (e.key === 'Escape') cerrarLightbox();
            if (e.key === 'ArrowLeft') prevImg();
            if (e.key === 'ArrowRight') nextImg();
        });
    </script>
</body>
</html>
