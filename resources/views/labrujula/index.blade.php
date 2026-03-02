@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>La Brújula - Atractivos | El Pionero de Valparaíso</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (app()->environment('production'))
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
    @endif
</head>

<body class="bg-gray-100 text-gray-900 font-serif text-base">
    <div class="w-full mx-auto md:p-4">
        <x-header_labrujula />
        <x-navbar_labrujula />

        <div class="max-w-7xl mx-auto px-4">
            <section class="my-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                    🧭 La Brújula
                </h1>
                <p class="text-xl text-gray-700">
                    Descubre los mejores rincones y experiencias de Valparaíso
                </p>
            </section>

            <div class="bg-white rounded-lg shadow-lg p-6 mb-8 border-t-4 border-[#fc5648]">
                <form id="filterForm" action="{{ route('atractivos.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Bùsqueda por categoría</label>
                            <select id="categoryFilter" name="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#fc5648] outline-none bg-gray-50">
                                <option value="">Todas las categorías</option>
                                @foreach ($categorias as $cat)
                                    <option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>
                                        {{ $cat->icono }} {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Búsqueda por palabra</label>
                            <div class="flex">
                                <input type="text" id="searchFilter" name="search" value="{{ request('search') }}" 
                                       placeholder="Ascensor, puerta de colores, café" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-[#fc5648] outline-none">
                                <button type="submit" class="bg-[#fc5648] text-white px-5 py-2 rounded-r-lg hover:bg-[#d94439] transition">
                                    🔍
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">¿Qué hay cerca de mí? (GPS)</label>
                            <div class="flex">
                                <select name="rango" id="rango" class="w-full px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-[#fc5648] outline-none">
                                    <option value="100" @selected(request('rango') == 100)>100 mts</option>
                                    <option value="300" @selected(request('rango') == 300)>300 mts</option>
                                    <option value="500" @selected(request('rango') == 500)>500 mts</option>
                                    <option value="1000" @selected(request('rango') == 1000)>1 km</option>
                                    <option value="3000" @selected(request('rango') == 3000)>3 km</option>
                                    <option value="5000" @selected(request('rango') == 5000)>5 km</option>
                                    <option value="10000" @selected(request('rango') == 10000)>10 km</option>
                                </select>
                                
                                <input type="hidden" id="lat" name="lat" value="{{ request('lat') }}">
                                <input type="hidden" id="lng" name="lng" value="{{ request('lng') }}">

                                <button type="button" id="btn-gps" class="bg-gray-800 text-white px-5 py-2 rounded-r-lg hover:bg-black transition flex items-center gap-2">
                                    📍 <span>GPS</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <p class="text-xs text-gray-400 italic">El Pionero de Valparaíso</p>
                        @if(request()->anyFilled(['category', 'search', 'lat']))
                            <a href="{{ route('atractivos.index') }}" 
                               class="text-sm font-semibold text-gray-500 hover:text-[#fc5648] transition flex items-center gap-1 underline">
                                <span>✕</span> Borrar filtros
                            </a>
                        @endif
                    </div>
                </form>
           </div>

             <div class="atractivos-container">
                @if ($atractivos->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($atractivos as $atractivo)
                            <article class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col h-full">
                                <div class="relative">
                                    <a href="{{ route('atractivos.show', $atractivo->slug) }}" class="block overflow-hidden">
                                        @if ($atractivo->image)
                                            <img src="{{ asset('storage/' . $atractivo->image) }}"
                                                 alt="{{ $atractivo->title }}"
                                                 class="w-full h-56 object-cover transform hover:scale-105 transition-transform duration-500" />
                                        @else
                                            <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-4xl">📍</div>
                                        @endif
                                    </a>
                                    @if ($atractivo->categoria)
                                        <span class="absolute top-4 left-4 bg-[#fc5648] text-white text-[10px] uppercase tracking-widest font-bold px-3 py-1 rounded-full shadow-lg">
                                            {{ $atractivo->categoria->icono }} {{ $atractivo->categoria->nombre }}
                                        </span>
                                    @endif
                                </div>

                                <div class="p-5 flex-grow">
                                    @if(isset($atractivo->distancia))
                                        <div class="mb-2">
                                            <span class="bg-orange-100 text-[#fc5648] text-xs font-bold px-2 py-1 rounded border border-orange-200">
                                                A {{ number_format($atractivo->distancia / 1000, 2) }} km de ti
                                            </span>
                                        </div>
                                    @endif

                                    <h3 class="text-xl font-bold text-gray-900 mb-1 leading-tight">
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $atractivo->lat }},{{ $atractivo->lng }}"
                                           target="_blank" rel="noopener" class="text-gray-400 hover:text-[#fc5648] mr-1">
                                            📍
                                        </a>
                                        <a href="{{ route('atractivos.show', $atractivo->slug) }}" class="hover:text-[#fc5648] transition">
                                            {{ $atractivo->title }}
                                        </a>
                                    </h3>
                                                                        <a href="{{ route('atractivos.show', $atractivo->slug) }}" class="block overflow-hidden">

                                    <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                        {{ Str::limit(strip_tags($atractivo->description), 130) }}
                                    </p>
                                                                        </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-16 mb-12 flex justify-center">
                        {{ $atractivos->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-sm p-20 text-center border-2 border-dashed border-gray-200">
                        <div class="text-6xl mb-6">🕵️‍♂️</div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Sin resultados</h3>
                        <p class="text-gray-500 mb-6">No encontramos lugares cercanos o que coincidan con tu búsqueda.</p>
                        <a href="{{ route('atractivos.index') }}" class="bg-[#fc5648] text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-900 transition">
                            Ver toda La Brújula
                        </a>
                    </div>
                @endif
            </div> 
        </div>

        <x-footer />
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const btnGps = document.getElementById('btn-gps');
        const inputLat = document.getElementById('lat');
        const inputLng = document.getElementById('lng');

        // Lógica del botón GPS
        if (btnGps) {
            btnGps.addEventListener('click', function() {
                btnGps.disabled = true;
                btnGps.innerHTML = '⌛ Localizando...';

                if (!navigator.geolocation) {
                    alert("Tu navegador no soporta geolocalización.");
                    resetBtn();
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        inputLat.value = position.coords.latitude;
                        inputLng.value = position.coords.longitude;
                        
                        console.log("📍 Ubicación obtenida:", position.coords.latitude, position.coords.longitude);
                        
                        // Enviamos el formulario principal
                        filterForm.submit();
                    },
                    function(error) {
                        console.error("Error GPS:", error);
                        alert("No pudimos obtener tu ubicación.");
                        resetBtn();
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            });
        }

        function resetBtn() {
            btnGps.disabled = false;
            btnGps.innerHTML = '📍 GPS';
        }

        // Listener para cambio de categoría (envío automático)
        document.getElementById('categoryFilter').addEventListener('change', function() {
            filterForm.submit();
        });
    });
    </script>

    {{-- Consola Debug --}}
    @if(request()->filled(['lat', 'lng']))
        <script>
            const listaAtractivos = @json($atractivos->items());
            console.log("🗺️ Resultados de la búsqueda espacial:");
            if(listaAtractivos.length > 0) {
                console.table(listaAtractivos.map(i => ({
                    Nombre: i.title,
                    Distancia: i.distancia ? (i.distancia / 1000).toFixed(2) + ' km' : 'Original'
                })));
            } else {
                console.log("Cero resultados en el radio seleccionado.");
            }
        </script>
    @endif
</body>
</html>