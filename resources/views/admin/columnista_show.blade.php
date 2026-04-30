<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('columnistas.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $columnista->nombre }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Tarjeta perfil -->
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-[#fc5648] via-[#eba81d] to-[#fc5648]"></div>
                <div class="p-6 flex flex-col sm:flex-row gap-6 items-start">
                    <!-- Foto -->
                    <div class="shrink-0">
                        @if($columnista->foto)
                            <img
                                src="{{ asset($columnista->foto) }}"
                                alt="{{ $columnista->nombre }}"
                                class="w-28 h-28 rounded-full object-cover ring-4 ring-gray-100 shadow"
                            />
                        @else
                            <div class="w-28 h-28 rounded-full bg-gray-200 flex items-center justify-center text-3xl text-gray-400 shadow">
                                {{ mb_substr($columnista->nombre, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- Datos -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $columnista->nombre }}</h3>
                            @if($columnista->participa_proximo_numero)
                                <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-semibold">Próximo número</span>
                            @endif
                        </div>

                        @if($columnista->email)
                            <p class="text-sm text-gray-500 mb-3">
                                <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $columnista->email }}
                            </p>
                        @endif

                        @if($columnista->bio)
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $columnista->bio }}</p>
                        @else
                            <p class="text-sm text-gray-400 italic">Sin biografía registrada.</p>
                        @endif

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('columnistas.index') }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
                                Volver al listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artículos del columnista -->
            <div class="bg-white shadow-sm rounded-xl p-6">
                <h4 class="text-base font-bold text-gray-800 mb-4 border-b pb-2">
                    Artículos publicados
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $columnista->articulos->count() }})</span>
                </h4>

                @if($columnista->articulos->isNotEmpty())
                    <ul class="divide-y divide-gray-100">
                        @foreach($columnista->articulos->sortByDesc('created_at') as $articulo)
                            <li class="py-3 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $articulo->titulo }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $articulo->revista->titulo ?? '—' }}
                                        @if($articulo->created_at)
                                            · {{ $articulo->created_at->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ url('articulo/' . $articulo->slug) }}"
                                   target="_blank"
                                   class="shrink-0 text-xs text-[#fc5648] hover:underline font-semibold">
                                    Ver →
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-400 italic">Este columnista no tiene artículos publicados.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
