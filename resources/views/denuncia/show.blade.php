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
                        <div class="overflow-hidden rounded-2xl {{ count($imagenes) > 1 && $idx === 0 ? 'col-span-full' : '' }} aspect-video">
                            <img
                                src="/storage/{{ $imagen }}"
                                alt="Evidencia {{ $idx + 1 }}"
                                class="w-full h-full object-cover"
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
</body>
</html>
