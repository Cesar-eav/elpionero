<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denuncia - El Pionero de Valparaíso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('components.navbar')

    <div class="max-w-3xl mx-auto px-4 py-10">

        <!-- Aviso de tiempo de aprobación -->
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-8 rounded-lg flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-semibold text-amber-800">Tiempo de revisión</p>
                <p class="text-amber-700 text-sm mt-0.5">
                    Tu denuncia será revisada por nuestro equipo editorial. El tiempo máximo de aprobación es de <strong>24 horas</strong>.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-[#fc5648] py-6 px-8">
                <h1 class="text-2xl font-bold text-white">Enviar Denuncia</h1>
                <p class="text-red-100 text-sm mt-1">Ayúdanos a informar lo que ocurre en tu comunidad</p>
            </div>

            <div class="p-8">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg p-4 mb-6">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('denuncia.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Nombre (opcional) -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                            Tu nombre <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            placeholder="Puedes mantener el anonimato"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#fc5648] focus:border-transparent"
                        />
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <label for="ubicacion" class="block text-sm font-medium text-gray-700 mb-1">
                            Ubicación del hecho <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="ubicacion"
                            name="ubicacion"
                            value="{{ old('ubicacion') }}"
                            required
                            placeholder="Ej: Av. Argentina 123, Valparaíso"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#fc5648] focus:border-transparent @error('ubicacion') border-red-400 @enderror"
                        />
                        @error('ubicacion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción de la denuncia <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            required
                            rows="5"
                            placeholder="Describe en detalle lo que ocurrió, cuándo y cómo..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#fc5648] focus:border-transparent @error('descripcion') border-red-400 @enderror"
                        >{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imágenes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Imágenes <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal ml-1">(mínimo 1, máximo 3 — JPG, PNG, GIF — máx. 5MB c/u)</span>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Imagen 1 -->
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Imagen 1 <span class="text-red-500">*</span></label>
                                <div id="preview1-container" class="hidden mb-2">
                                    <img id="preview1" src="" alt="Preview 1" class="w-full h-32 object-cover rounded-lg border" />
                                </div>
                                <input
                                    type="file"
                                    id="imagen1"
                                    name="imagen1"
                                    required
                                    accept="image/jpeg,image/jpg,image/png,image/gif"
                                    onchange="previewImage(this, 'preview1', 'preview1-container')"
                                    class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-[#fc5648] file:text-white hover:file:bg-red-600 @error('imagen1') border border-red-400 rounded-lg p-1 @enderror"
                                />
                                @error('imagen1')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Imagen 2 -->
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Imagen 2 <span class="text-gray-400">(opcional)</span></label>
                                <div id="preview2-container" class="hidden mb-2">
                                    <img id="preview2" src="" alt="Preview 2" class="w-full h-32 object-cover rounded-lg border" />
                                </div>
                                <input
                                    type="file"
                                    id="imagen2"
                                    name="imagen2"
                                    accept="image/jpeg,image/jpg,image/png,image/gif"
                                    onchange="previewImage(this, 'preview2', 'preview2-container')"
                                    class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300"
                                />
                            </div>

                            <!-- Imagen 3 -->
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Imagen 3 <span class="text-gray-400">(opcional)</span></label>
                                <div id="preview3-container" class="hidden mb-2">
                                    <img id="preview3" src="" alt="Preview 3" class="w-full h-32 object-cover rounded-lg border" />
                                </div>
                                <input
                                    type="file"
                                    id="imagen3"
                                    name="imagen3"
                                    accept="image/jpeg,image/jpg,image/png,image/gif"
                                    onchange="previewImage(this, 'preview3', 'preview3-container')"
                                    class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Botón enviar -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full bg-[#fc5648] hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors"
                        >
                            Enviar Denuncia
                        </button>
                        <p class="text-xs text-gray-400 text-center mt-3">
                            Al enviar aceptas que la información sea revisada por nuestro equipo editorial antes de su publicación.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('denuncia.index') }}" class="text-[#fc5648] hover:underline text-sm">
                Ver denuncias publicadas
            </a>
        </div>
    </div>

    <script>
        function previewImage(input, previewId, containerId) {
            const file = input.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('La imagen no debe superar los 5MB');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
                document.getElementById(containerId).classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
