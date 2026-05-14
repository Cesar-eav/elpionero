<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denuncias - El Pionero de Valparaíso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('components.navbar')

    <div class="max-w-5xl mx-auto px-4 py-10">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Denuncias</h1>
                <p class="text-gray-500 text-sm mt-1">Denuncias ciudadanas verificadas por nuestro equipo editorial</p>
            </div>
            <a
                href="{{ route('denuncia.formulario') }}"
                class="inline-flex items-center gap-2 bg-[#fc5648] hover:bg-red-600 text-white font-medium py-2.5 px-5 rounded-lg transition-colors self-start sm:self-auto"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Enviar denuncia
            </a>
        </div>

        <!-- Aviso de tiempo -->
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-8 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-amber-800 text-sm">
                Las denuncias son revisadas por nuestro equipo editorial. El tiempo máximo de aprobación es de <strong>24 horas</strong>.
            </p>
        </div>

        <!-- Lista de denuncias -->
        @if ($denuncias->isEmpty())
            <div class="text-center py-20">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-lg">No hay denuncias publicadas aún.</p>
                <a href="{{ route('denuncia.formulario') }}" class="text-[#fc5648] hover:underline text-sm mt-2 inline-block">
                    Sé el primero en enviar una denuncia
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($denuncias as $denuncia)
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Imágenes -->
                        @php
                            $imagenes = array_filter([$denuncia->imagen1, $denuncia->imagen2, $denuncia->imagen3]);
                        @endphp
                        @if (count($imagenes) > 0)
                            <div class="grid {{ count($imagenes) === 1 ? 'grid-cols-1' : (count($imagenes) === 2 ? 'grid-cols-2' : 'grid-cols-3') }} gap-0.5">
                                @foreach ($imagenes as $imagen)
                                    <div class="{{ count($imagenes) === 1 ? 'h-64' : 'h-48' }} overflow-hidden">
                                        <img
                                            src="/storage/{{ $imagen }}"
                                            alt="Imagen de la denuncia"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Contenido -->
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $denuncia->ubicacion }}</span>
                                </div>
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $denuncia->approved_at?->diffForHumans() ?? $denuncia->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-gray-800 leading-relaxed">{{ $denuncia->descripcion }}</p>

                            @if ($denuncia->nombre)
                                <p class="text-sm text-gray-400 mt-3">Enviada por: {{ $denuncia->nombre }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Paginación -->
            @if ($denuncias->hasPages())
                <div class="mt-8">
                    {{ $denuncias->links() }}
                </div>
            @endif
        @endif
    </div>
</body>
</html>
