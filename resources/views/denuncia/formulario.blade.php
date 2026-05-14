<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Denuncia - El Pionero de Valparaíso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 antialiased">
    @include('components.navbar')

    <div class="max-w-4xl mx-auto px-4 py-12 md:py-16">

        <!-- Banner de Ayuda -->
        <div class="bg-amber-50 border border-amber-200 p-5 mb-10 rounded-2xl flex items-start gap-4 shadow-sm">
            <div class="bg-amber-500 p-2 rounded-lg shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-amber-900 leading-tight">Compromiso Editorial</h4>
                <p class="text-amber-800 text-sm mt-1">
                    Cada reporte es verificado por nuestro equipo en un plazo máximo de <span class="font-bold underline italic">24 horas</span> antes de su publicación oficial.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100">
            <!-- Header del Formulario -->
            <div class="bg-[#fc5648] py-10 px-8 text-center md:text-left md:flex md:items-center md:justify-between border-b border-red-400">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Enviar Reporte</h1>
                    <p class="text-red-100 font-medium mt-1">Tu voz ayuda a mejorar Valparaíso</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-8 md:p-12">
                <!-- Errores de Laravel -->
                @if ($errors->any())
                    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-xl p-5 mb-8 animate-pulse">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <p class="font-bold uppercase tracking-wider text-xs">Atención</p>
                        </div>
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>— {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div id="error-msg" class="hidden bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-xl p-5 mb-8 font-medium"></div>

                <form id="denuncia-form" class="grid grid-cols-1 md:grid-cols-2 gap-8" enctype="multipart/form-data">
                    @csrf

                    <!-- Columna Izquierda -->
                    <div class="space-y-6">
                        <div class="group">
                            <label for="titulo" class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2 group-focus-within:text-[#fc5648] transition-colors">Título del reporte <span class="text-red-500">*</span></label>
                            <input type="text" id="titulo" name="titulo" placeholder="Ej: Luminarias apagadas en Cerro Alegre" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#fc5648]/10 focus:border-[#fc5648] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                        </div>

                        <div class="group">
                            <label for="ubicacion" class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2 group-focus-within:text-[#fc5648] transition-colors">Ubicación exacta <span class="text-red-500">*</span></label>
                            <input type="text" id="ubicacion" name="ubicacion" placeholder="Ej: Calle Prat con Urriola" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#fc5648]/10 focus:border-[#fc5648] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                        </div>

                        <div class="group">
                            <label for="nombre" class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2 group-focus-within:text-[#fc5648] transition-colors">Tu nombre <span class="text-slate-400 font-normal lowercase italic">(opcional)</span></label>
                            <input type="text" id="nombre" name="nombre" placeholder="Para darte créditos en la nota" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#fc5648]/10 focus:border-[#fc5648] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="space-y-6">
                        <div class="group h-full flex flex-col">
                            <label for="descripcion" class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2 group-focus-within:text-[#fc5648] transition-colors">Detalles del suceso <span class="text-red-500">*</span></label>
                            <textarea id="descripcion" name="descripcion" rows="7" placeholder="Cuéntanos lo que viste..." required class="w-full flex-grow px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#fc5648]/10 focus:border-[#fc5648] focus:bg-white outline-none transition-all placeholder:text-slate-400 resize-none"></textarea>
                            <p class="text-[10px] text-slate-400 mt-2 italic">Mínimo 20 caracteres para una descripción válida.</p>
                        </div>
                    </div>

                    <!-- Sección de Imágenes (Full width) -->
                    <div class="md:col-span-2 mt-4">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-3">Evidencia fotográfica <span class="text-red-500">*</span></label>
                        
                        <div id="drop-zone" class="relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-[2rem] p-10 text-center hover:border-[#fc5648] hover:bg-red-50/30 transition-all duration-300">
                            <input type="file" id="file-selector" name="imagenes[]" accept="image/*" multiple class="sr-only">
                            
                            <div class="space-y-3">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto group-hover:scale-110 group-hover:bg-[#fc5648] group-hover:text-white transition-all duration-500 text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-slate-900 font-bold">Haz clic o arrastra fotos aquí</p>
                                    <p class="text-slate-500 text-sm">Puedes subir hasta un máximo de <span class="text-[#fc5648] font-bold">3 imágenes</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <div id="previews-container" class="mt-8 grid grid-cols-2 md:grid-cols-3 gap-6" style="display:none"></div>
                        <p id="file-count-msg" class="text-center font-bold text-[#fc5648] text-xs uppercase tracking-tighter mt-4" style="display:none"></p>
                    </div>

                    <!-- Botón Enviar -->
                    <div class="md:col-span-2 mt-6">
                        <button type="submit" id="submit-btn" class="w-full bg-[#fc5648] hover:bg-slate-900 text-white font-black uppercase tracking-widest py-5 rounded-[1.5rem] shadow-xl shadow-red-100 hover:shadow-slate-300 transition-all duration-300 active:scale-95 flex items-center justify-center gap-3">
                            Enviar Reporte Ahora
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        </button>
                        <p class="text-center text-slate-400 text-[10px] mt-4 font-medium uppercase tracking-widest">Al enviar, aceptas nuestros términos de colaboración vecinal</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script se mantiene igual funcionalmente, solo ajusto la renderización de previews para que coincida con el nuevo estilo -->
    <script>
        const fileSelector = document.getElementById('file-selector');
        const previewsBox  = document.getElementById('previews-container');
        const countMsg     = document.getElementById('file-count-msg');
        const errorMsg     = document.getElementById('error-msg');
        const submitBtn    = document.getElementById('submit-btn');
        const dropZone     = document.getElementById('drop-zone');
        let selectedFiles  = [];

        dropZone.onclick = () => fileSelector.click();

        fileSelector.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            addFiles(files);
            this.value = "";
        });

        function addFiles(files) {
            files.forEach(file => {
                if (selectedFiles.length >= 3) return;
                if (!file.type.startsWith('image/')) return;
                selectedFiles.push(file);
            });
            renderPreviews();
        }

        function renderPreviews() {
            previewsBox.innerHTML = '';
            if (selectedFiles.length === 0) {
                previewsBox.style.display = 'none';
                countMsg.style.display = 'none';
                return;
            }

            previewsBox.style.display = 'grid';
            countMsg.style.display = 'block';
            countMsg.textContent = `${selectedFiles.length} de 3 imágenes seleccionadas`;

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'group relative aspect-video rounded-2xl overflow-hidden shadow-md border border-slate-100';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <button type="button" onclick="removeFile(${index})" class="absolute top-2 right-2 bg-rose-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all">✕</button>
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-10 transition-opacity pointer-events-none"></div>
                    `;
                    previewsBox.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        window.removeFile = function(index) {
            selectedFiles.splice(index, 1);
            renderPreviews();
        };

        function showError(msg) {
            errorMsg.textContent = msg;
            errorMsg.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(() => errorMsg.classList.add('hidden'), 6000);
        }

        document.getElementById('denuncia-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const titulo = document.getElementById('titulo').value.trim();
            const ubicacion = document.getElementById('ubicacion').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();

            if (!titulo) { showError("Debes ingresar un título."); return; }
            if (!ubicacion) { showError("La ubicación es fundamental."); return; }
            if (descripcion.length < 20) { showError("Danos un poco más de detalle (mín. 20 caracteres)."); return; }
            if (selectedFiles.length === 0) { showError("Necesitamos al menos una imagen para validar el reporte."); return; }

            const fd = new FormData(this);
            fd.delete('imagenes[]');
            fd.append('imagen1', selectedFiles[0]);
            if (selectedFiles[1]) fd.append('imagen2', selectedFiles[1]);
            if (selectedFiles[2]) fd.append('imagen3', selectedFiles[2]);

            submitBtn.disabled = true;
            submitBtn.innerHTML = `Subiendo... <svg class="animate-spin h-5 w-5 text-white inline ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

            try {
                const response = await fetch("{{ route('denuncia.store') }}", {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                if (response.redirected || response.ok) {
                    window.location.href = "{{ route('denuncia.gracias') }}";
                    return;
                }

                const result = await response.json();
                showError(result.errors ? Object.values(result.errors).flat().join(' ') : (result.message || "Error en el servidor."));
                submitBtn.disabled = false;
                submitBtn.textContent = "Enviar Reporte Ahora";
            } catch (err) {
                showError("Error de conexión. Revisa tu internet.");
                submitBtn.disabled = false;
                submitBtn.textContent = "Enviar Reporte Ahora";
            }
        });
    </script>
</body>
</html>