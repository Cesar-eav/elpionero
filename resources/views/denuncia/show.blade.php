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

    @php
        $imagenes = array_values(array_filter([$denuncia->imagen1, $denuncia->imagen2, $denuncia->imagen3, $denuncia->imagen4 ?? null]));
    @endphp

    <div class="h-[calc(100vh-56px)] hidden md:flex flex-col px-6 py-4 max-w-7xl mx-auto">

        <!-- Volver (desktop) -->
        <a href="{{ route('denuncia.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-[#fc5648] transition-colors mb-4 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver a denuncias
        </a>

        <!-- Layout dos columnas -->
        <div class="flex gap-6 flex-1 min-h-0">

            <!-- Columna izquierda: galería -->
            <div class="w-1/2 flex flex-col gap-3 min-h-0">
                <div class="relative flex-1 overflow-hidden rounded-2xl bg-slate-100 cursor-zoom-in"
                     onclick="abrirLightbox(carruselIdx)">
                    <img id="img-principal"
                         src="{{ count($imagenes) ? '/storage/'.$imagenes[0] : '' }}"
                         class="w-full h-full object-cover transition-all duration-300" alt="Evidencia principal">
                    <div class="absolute top-3 right-3 bg-black/40 text-white rounded-full p-1.5 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    </div>
                    @if(count($imagenes) > 1)
                    <button onclick="event.stopPropagation();cambiarImg(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center transition-colors text-xl">‹</button>
                    <button onclick="event.stopPropagation();cambiarImg(1)"  class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center transition-colors text-xl">›</button>
                    @endif
                </div>
                @if(count($imagenes) > 1)
                <div class="flex gap-2 h-20 shrink-0">
                    @foreach($imagenes as $idx => $img)
                    <button onclick="seleccionarImg({{ $idx }})" id="thumb-{{ $idx }}"
                            class="thumb flex-1 overflow-hidden rounded-xl border-2 transition-all duration-200 {{ $idx === 0 ? 'border-[#fc5648]' : 'border-transparent opacity-60 hover:opacity-100' }}">
                        <img src="/storage/{{ $img }}" class="w-full h-full object-cover" alt="Miniatura {{ $idx+1 }}">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Columna derecha: contenido -->
            <div class="w-1/2 flex flex-col min-h-0 bg-white rounded-2xl border border-slate-100 shadow-sm">

                <!-- Scrollable -->
                <div class="flex-1 overflow-y-auto p-8">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full border border-green-100">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Verificado
                        </span>
                        <time class="text-xs text-slate-400 uppercase tracking-widest">
                            {{ $denuncia->approved_at?->diffForHumans() ?? $denuncia->created_at->diffForHumans() }}
                        </time>
                        <span class="text-[12px] font-black text-slate-600 leading-tight group-hover:text-[#fc5648] transition-colors line-clamp-2">
                            Fecha: {{ $denuncia->created_at->format('d-m-Y') }}
                        </span>
                    </div>

                    <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-4">{{ $denuncia->titulo }}</h1>

                    <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl border border-slate-100 w-fit mb-5">
                        <svg class="w-4 h-4 text-[#fc5648] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm font-bold text-slate-700">{{ $denuncia->ubicacion }}</span>
                    </div>

                    <div class="text-slate-600 text-base leading-relaxed whitespace-pre-line">{{ $denuncia->descripcion }}</div>
                </div>

                <!-- Pie fijo -->
                <div class="shrink-0 px-8 pb-6 pt-4 border-t border-slate-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <p class="text-[9px] uppercase tracking-widest text-slate-400 font-bold">Enviado por</p>
                                <p class="text-sm font-bold text-slate-700">{{ $denuncia->nombre ?? 'Vecino Anónimo' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Revisado en menos de 24 hs
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="https://wa.me/?text={{ urlencode($denuncia->titulo . ' — ' . request()->url()) }}" target="_blank"
                           class="flex-1 flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1ebe5d] text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.122.554 4.118 1.523 5.847L.057 23.882l6.198-1.442A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.882a9.87 9.87 0 01-5.031-1.378l-.36-.214-3.733.869.936-3.42-.235-.372A9.878 9.878 0 012.118 12C2.118 6.534 6.534 2.118 12 2.118c5.465 0 9.882 4.416 9.882 9.882 0 5.465-4.417 9.882-9.882 9.882z"/></svg>
                            WhatsApp
                        </a>
                        <button onclick="copiarLink()" id="btn-copiar"
                                class="flex-1 flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-sm transition-colors">
                            <svg id="icon-copiar" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span id="txt-copiar">Copiar enlace</span>
                        </button>
                        <a href="{{ route('denuncia.formulario') }}"
                           class="flex-1 flex items-center justify-center gap-1.5 bg-[#fc5648] hover:bg-red-600 text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Denunciar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MÓVIL: layout original apilado -->
    <div class="md:hidden px-4 py-8">
        <a href="{{ route('denuncia.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-[#fc5648] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver
        </a>

        <div class="space-y-3 mb-4">
            <div class="relative overflow-hidden rounded-2xl aspect-video bg-slate-100 cursor-zoom-in" onclick="abrirLightbox(carruselIdx)">
                <img id="img-principal-m" src="{{ count($imagenes) ? '/storage/'.$imagenes[0] : '' }}" class="w-full h-full object-cover" alt="Principal">
                @if(count($imagenes) > 1)
                <button onclick="event.stopPropagation();cambiarImgM(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-9 h-9 flex items-center justify-center text-xl">‹</button>
                <button onclick="event.stopPropagation();cambiarImgM(1)"  class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-9 h-9 flex items-center justify-center text-xl">›</button>
                @endif
            </div>
            @if(count($imagenes) > 1)
            <div class="flex gap-2 h-16">
                @foreach($imagenes as $idx => $img)
                <button onclick="seleccionarImgM({{ $idx }})" id="thumb-m-{{ $idx }}"
                        class="thumb-m flex-1 overflow-hidden rounded-xl border-2 transition-all {{ $idx===0?'border-[#fc5648]':'border-transparent opacity-60' }}">
                    <img src="/storage/{{ $img }}" class="w-full h-full object-cover" alt="">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full border border-green-100">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Verificado
                </span>
                
                <time class="text-xs text-slate-400 uppercase tracking-widest self-center">{{ $denuncia->approved_at?->diffForHumans() ?? $denuncia->created_at->diffForHumans() }}</time>
            </div>
            <h1 class="text-2xl font-black text-slate-900 mb-4">{{ $denuncia->titulo }}</h1>
            <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl border border-slate-100 w-fit mb-4">
                <svg class="w-4 h-4 text-[#fc5648]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm font-bold text-slate-700">{{ $denuncia->ubicacion }}</span>
            </div>
            <span class="text-[12px] font-black mb-5 text-slate-600 leading-tight group-hover:text-[#fc5648] transition-colors line-clamp-2">
                Fecha: {{ $denuncia->created_at->format('d-m-Y') }}
            </span>
            <div class="text-slate-600 text-base leading-relaxed whitespace-pre-line mb-6">{{ $denuncia->descripcion }}</div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-700">{{ $denuncia->nombre ?? 'Vecino Anónimo' }}</p>
                </div>
                <div class="flex items-center gap-1 text-xs text-slate-400">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    &lt; 24 hs
                </div>
            </div>
            <div class="flex gap-2">
                <a href="https://wa.me/?text={{ urlencode($denuncia->titulo . ' — ' . request()->url()) }}" target="_blank"
                   class="flex-1 flex items-center justify-center gap-1.5 bg-[#25D366] text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.122.554 4.118 1.523 5.847L.057 23.882l6.198-1.442A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.882a9.87 9.87 0 01-5.031-1.378l-.36-.214-3.733.869.936-3.42-.235-.372A9.878 9.878 0 012.118 12C2.118 6.534 6.534 2.118 12 2.118c5.465 0 9.882 4.416 9.882 9.882 0 5.465-4.417 9.882-9.882 9.882z"/></svg>
                    WhatsApp
                </a>
                <button onclick="copiarLink()" id="btn-copiar-m"
                        class="flex-1 flex items-center justify-center gap-1.5 bg-slate-100 text-slate-700 font-bold py-2.5 rounded-xl text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Copiar
                </button>
            </div>
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
        function copiarLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = document.getElementById('btn-copiar');
                const txt = document.getElementById('txt-copiar');
                const icon = document.getElementById('icon-copiar');
                btn.classList.replace('bg-slate-100', 'bg-green-100');
                btn.classList.replace('text-slate-700', 'text-green-700');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
                txt.textContent = '¡Copiado!';
                setTimeout(() => {
                    btn.classList.replace('bg-green-100', 'bg-slate-100');
                    btn.classList.replace('text-green-700', 'text-slate-700');
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>';
                    txt.textContent = 'Copiar enlace';
                }, 2500);
            });
        }
    </script>

    <script>
        const imgs = @json($imagenes);
        let carruselIdx = 0;

        // Desktop
        function seleccionarImg(idx) {
            carruselIdx = idx;
            document.getElementById('img-principal').src = '/storage/' + imgs[idx];
            document.querySelectorAll('.thumb').forEach((t, i) => {
                t.classList.toggle('border-[#fc5648]', i === idx);
                t.classList.toggle('opacity-60', i !== idx);
                t.classList.toggle('opacity-100', i === idx);
            });
        }
        function cambiarImg(dir) { seleccionarImg((carruselIdx + dir + imgs.length) % imgs.length); }

        // Móvil
        function seleccionarImgM(idx) {
            carruselIdx = idx;
            document.getElementById('img-principal-m').src = '/storage/' + imgs[idx];
            document.querySelectorAll('.thumb-m').forEach((t, i) => {
                t.classList.toggle('border-[#fc5648]', i === idx);
                t.classList.toggle('opacity-60', i !== idx);
            });
        }
        function cambiarImgM(dir) { seleccionarImgM((carruselIdx + dir + imgs.length) % imgs.length); }

        // Lightbox
        function abrirLightbox(idx) {
            carruselIdx = idx;
            actualizarLightbox();
            document.getElementById('lb').style.cssText = 'display:flex!important';
            document.body.style.overflow = 'hidden';
        }

        function cerrarLightbox() {
            document.getElementById('lb').style.cssText = 'display:none!important';
            document.body.style.overflow = '';
        }

        function actualizarLightbox() {
            document.getElementById('lb-img').src = '/storage/' + imgs[carruselIdx];
            document.getElementById('lb-counter').textContent = (carruselIdx + 1) + ' / ' + imgs.length;
            document.getElementById('lb-prev').style.display = imgs.length > 1 ? 'flex' : 'none';
            document.getElementById('lb-next').style.display = imgs.length > 1 ? 'flex' : 'none';
        }

        function prevImg() { carruselIdx = (carruselIdx - 1 + imgs.length) % imgs.length; actualizarLightbox(); }
        function nextImg() { carruselIdx = (carruselIdx + 1) % imgs.length; actualizarLightbox(); }

        document.getElementById('lb').addEventListener('click', function(e) {
            if (e.target === this) cerrarLightbox();
        });

        document.addEventListener('keydown', e => {
            if (document.getElementById('lb').style.cssText.includes('none')) return;
            if (e.key === 'Escape') cerrarLightbox();
            if (e.key === 'ArrowLeft') prevImg();
            if (e.key === 'ArrowRight') nextImg();
        });
    </script>
</body>
</html>
