<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="$emit('close')">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4 pb-3 border-b">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ isEdit ? 'Editar Cable a Tierra' : 'Crear Nuevo Cable a Tierra' }}
                </h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitForm">
                <div class="space-y-4">

                    <!-- Título -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                        <input v-model="form.titulo" type="text" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ingrese el título del artículo"/>
                    </div>

                    <!-- Autor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Autor (opcional)</label>
                        <input v-model="form.autor" type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nombre del autor"/>
                    </div>

                    <!-- Resumen -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Resumen <span class="text-red-500">*</span></label>
                        <textarea v-model="form.resumen" required rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Breve resumen del artículo"></textarea>
                    </div>

                    <!-- Imágenes de portada -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Móvil -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen Móvil</label>
                            <div v-if="imagePreview || (isEdit && articulo?.imagen)" class="mb-2">
                                <img :src="imagePreview || `/storage/${articulo.imagen}`" class="w-full h-40 object-cover rounded border"/>
                                <button type="button" @click="removeImage" class="mt-1 text-xs text-red-600 hover:text-red-800">Eliminar imagen</button>
                            </div>
                            <input ref="imageInput" type="file" accept="image/jpeg,image/jpg,image/png,image/gif"
                                @change="handleImageChange"
                                class="w-full text-sm border border-gray-300 rounded-md px-2 py-1.5"/>
                            <p class="text-xs text-gray-500 mt-1">Vertical/Cuadrada. Máx 2MB</p>
                        </div>
                        <!-- Desktop -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen Desktop</label>
                            <div v-if="imageDesktopPreview || (isEdit && articulo?.imagen_desktop)" class="mb-2">
                                <img :src="imageDesktopPreview || `/storage/${articulo.imagen_desktop}`" class="w-full h-40 object-cover rounded border"/>
                                <button type="button" @click="removeImageDesktop" class="mt-1 text-xs text-red-600 hover:text-red-800">Eliminar imagen</button>
                            </div>
                            <input ref="imageDesktopInput" type="file" accept="image/jpeg,image/jpg,image/png,image/gif"
                                @change="handleImageDesktopChange"
                                class="w-full text-sm border border-gray-300 rounded-md px-2 py-1.5"/>
                            <p class="text-xs text-gray-500 mt-1">Horizontal/Panorámica. Máx 2MB</p>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Publicación <span class="text-red-500">*</span></label>
                        <input v-model="form.fecha_publicacion" type="date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"/>
                    </div>

                    <!-- Editor Quill -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contenido <span class="text-red-500">*</span></label>
                        <div ref="quillEditor" class="bg-white border border-gray-300 rounded-md"></div>
                        <p class="text-xs text-gray-400 mt-1">
                            Párrafos detectados: <strong>{{ cantidadParrafos }}</strong>
                        </p>
                    </div>

                    <!-- ── Panel de imágenes entre párrafos ── -->
                    <div class="rounded-md border border-indigo-200 bg-indigo-50 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-indigo-800">🖼️ Imágenes entre párrafos</h4>
                            <button type="button" @click="agregarSlot"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-1.5 px-4 rounded-md transition-colors">
                                + Añadir imagen
                            </button>
                        </div>

                        <p v-if="imagenesContenido.length === 0" class="text-sm text-gray-500 italic">
                            Haz clic en "+ Añadir imagen" para insertar imágenes entre los párrafos del artículo.
                        </p>

                        <div v-else class="space-y-3">
                            <div v-for="(slot, i) in imagenesContenido" :key="i"
                                class="flex items-start gap-4 bg-white border border-indigo-100 rounded-md p-3">

                                <!-- Columna izquierda: preview o selector de archivo -->
                                <div class="flex-shrink-0 w-36">
                                    <img v-if="slot.preview" :src="slot.preview"
                                        class="w-36 h-24 object-cover rounded border border-gray-200"/>
                                    <label v-else :for="'slot-file-' + i"
                                        class="flex w-36 h-24 cursor-pointer items-center justify-center rounded border-2 border-dashed border-indigo-300 bg-indigo-50 text-center text-xs text-indigo-500 hover:bg-indigo-100">
                                        Clic para<br>elegir imagen
                                    </label>
                                    <input :id="'slot-file-' + i" type="file"
                                        accept="image/jpeg,image/jpg,image/png,image/gif"
                                        class="hidden"
                                        @change="onSlotFileChange(i, $event)"/>
                                    <button v-if="slot.preview" type="button"
                                        @click="limpiarSlotArchivo(i)"
                                        class="mt-1 block text-xs text-indigo-600 hover:underline">
                                        Cambiar imagen
                                    </button>
                                </div>

                                <!-- Columna derecha: selector de párrafo -->
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Insertar después del párrafo
                                    </label>
                                    <input v-model.number="slot.posicion" type="number" min="1"
                                        :max="cantidadParrafos || 999"
                                        class="w-24 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"/>
                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ cantidadParrafos > 0 ? `Hay ${cantidadParrafos} párrafos` : 'Escribe el contenido para ver el conteo' }}
                                    </p>
                                    <p v-if="slot.url" class="mt-1 text-xs text-green-600 font-medium">✓ Imagen lista</p>
                                    <p v-else-if="slot.preview" class="mt-1 text-xs text-amber-600">Se subirá al guardar</p>
                                    <p v-else class="mt-1 text-xs text-gray-400">Selecciona una imagen</p>
                                </div>

                                <!-- Botón eliminar -->
                                <button type="button" @click="eliminarSlot(i)"
                                    class="flex-shrink-0 text-red-400 hover:text-red-600 transition-colors"
                                    title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Botones guardar/cancelar -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="$emit('close')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit" :disabled="saving"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:bg-blue-300">
                        {{ saving ? 'Guardando...' : (isEdit ? 'Actualizar' : 'Crear') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

export default {
    name: 'CableATierraForm',
    props: {
        articulo: { type: Object, default: null }
    },
    data() {
        return {
            form: {
                titulo: '',
                autor: '',
                resumen: '',
                contenido: '',
                fecha_publicacion: new Date().toISOString().split('T')[0]
            },
            imageFile: null,
            imagePreview: null,
            imageDesktopFile: null,
            imageDesktopPreview: null,
            quillInstance: null,
            saving: false,
            // Cada slot: { archivo, preview, posicion, url }
            imagenesContenido: []
        };
    },
    computed: {
        isEdit() {
            return this.articulo !== null;
        },
        cantidadParrafos() {
            const matches = this.form.contenido.match(/<\/(p|h[1-6]|li)>/gi);
            return matches ? matches.length : 0;
        }
    },
    mounted() {
        this.initQuill();
        if (this.isEdit) {
            this.loadArticuloData();
        }
    },
    methods: {
        initQuill() {
            this.quillInstance = new Quill(this.$refs.quillEditor, {
                theme: 'snow',
                placeholder: 'Escriba el contenido del artículo...',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        [{ align: [] }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            this.quillInstance.on('text-change', () => {
                this.form.contenido = this.quillInstance.root.innerHTML;
            });
        },

        loadArticuloData() {
            this.form.titulo = this.articulo.titulo || '';
            this.form.autor = this.articulo.autor || '';
            this.form.resumen = this.articulo.resumen || '';
            this.form.fecha_publicacion = this.articulo.fecha_publicacion || new Date().toISOString().split('T')[0];

            if (this.articulo.contenido) {
                // Separar el texto de las imágenes embebidas entre párrafos
                const { textoSolo, slots } = this.extraerImagenes(this.articulo.contenido);
                this.imagenesContenido = slots;
                this.quillInstance.root.innerHTML = textoSolo;
                this.form.contenido = textoSolo;
            }
        },

        // Extrae <img> que estén inmediatamente después de tags de cierre de bloque
        extraerImagenes(html) {
            const slots = [];
            let n = 0;
            const textoSolo = html.replace(
                /<\/(p|h[1-6]|li)>((?:\s*<img\b[^>]*\/?>)+)/gi,
                (_, tag, imgGroup) => {
                    n++;
                    const imgRe = /<img\b[^>]*\/?>/gi;
                    let m;
                    while ((m = imgRe.exec(imgGroup)) !== null) {
                        const src = (m[0].match(/src="([^"]+)"/i) || [])[1];
                        if (src) slots.push({ archivo: null, preview: src, posicion: n, url: src });
                    }
                    return `</${tag}>`;
                }
            );
            return { textoSolo, slots };
        },

        // Añadir un nuevo slot vacío
        agregarSlot() {
            this.imagenesContenido.push({ archivo: null, preview: null, posicion: 1, url: null });
        },

        // Archivo seleccionado en un slot
        onSlotFileChange(i, event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) {
                alert('La imagen no debe superar los 5MB');
                event.target.value = '';
                return;
            }
            this.imagenesContenido[i].archivo = file;
            this.imagenesContenido[i].url = null;
            const reader = new FileReader();
            reader.onload = e => { this.imagenesContenido[i].preview = e.target.result; };
            reader.readAsDataURL(file);
        },

        limpiarSlotArchivo(i) {
            this.imagenesContenido[i].archivo = null;
            this.imagenesContenido[i].preview = null;
            this.imagenesContenido[i].url = null;
            // Limpiar input oculto por id
            const input = document.getElementById('slot-file-' + i);
            if (input) input.value = '';
        },

        eliminarSlot(i) {
            this.imagenesContenido.splice(i, 1);
        },

        // Fusiona el texto de Quill con las imágenes del panel según su posición
        buildContenidoFinal() {
            const slotsListos = [...this.imagenesContenido]
                .filter(s => s.url)
                .sort((a, b) => a.posicion - b.posicion);

            if (slotsListos.length === 0) return this.form.contenido;

            let n = 0;
            let si = 0;
            return this.form.contenido.replace(/<\/(p|h[1-6]|li)>/gi, match => {
                n++;
                let extra = '';
                while (si < slotsListos.length && slotsListos[si].posicion === n) {
                    extra += `<img src="${slotsListos[si].url}" alt="" style="max-width:100%;height:auto;display:block;margin:1.5rem auto;">`;
                    si++;
                }
                return match + extra;
            });
        },

        // ── Portada móvil ──
        handleImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) { alert('Máximo 2MB'); this.$refs.imageInput.value = ''; return; }
            if (!['image/jpeg','image/jpg','image/png','image/gif'].includes(file.type)) {
                alert('Solo JPG, PNG o GIF'); this.$refs.imageInput.value = ''; return;
            }
            this.imageFile = file;
            const r = new FileReader();
            r.onload = e => { this.imagePreview = e.target.result; };
            r.readAsDataURL(file);
        },
        removeImage() {
            this.imageFile = null; this.imagePreview = null;
            if (this.$refs.imageInput) this.$refs.imageInput.value = '';
        },

        // ── Portada desktop ──
        handleImageDesktopChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) { alert('Máximo 2MB'); this.$refs.imageDesktopInput.value = ''; return; }
            if (!['image/jpeg','image/jpg','image/png','image/gif'].includes(file.type)) {
                alert('Solo JPG, PNG o GIF'); this.$refs.imageDesktopInput.value = ''; return;
            }
            this.imageDesktopFile = file;
            const r = new FileReader();
            r.onload = e => { this.imageDesktopPreview = e.target.result; };
            r.readAsDataURL(file);
        },
        removeImageDesktop() {
            this.imageDesktopFile = null; this.imageDesktopPreview = null;
            if (this.$refs.imageDesktopInput) this.$refs.imageDesktopInput.value = '';
        },

        // ── Guardar ──
        async submitForm() {
            if (!this.form.titulo.trim())     { alert('El título es obligatorio'); return; }
            if (!this.form.resumen.trim())    { alert('El resumen es obligatorio'); return; }
            if (!this.form.contenido.trim() || this.form.contenido === '<p><br></p>') {
                alert('El contenido es obligatorio'); return;
            }
            if (!this.form.fecha_publicacion) { alert('La fecha es obligatoria'); return; }

            this.saving = true;
            try {
                // 1. Subir imágenes del panel que aún no tienen URL
                for (const slot of this.imagenesContenido) {
                    if (slot.archivo && !slot.url) {
                        const fd = new FormData();
                        fd.append('imagen', slot.archivo);
                        const res = await axios.post('/api/cable-a-tierra/upload-imagen', fd, {
                            headers: { 'Content-Type': 'multipart/form-data' }
                        });
                        slot.url = res.data.url;
                    }
                }

                // 2. Fusionar texto + imágenes
                const contenidoFinal = this.buildContenidoFinal();

                // 3. Enviar
                const formData = new FormData();
                formData.append('titulo', this.form.titulo);
                formData.append('autor', this.form.autor || '');
                formData.append('resumen', this.form.resumen);
                formData.append('contenido', contenidoFinal);
                formData.append('fecha_publicacion', this.form.fecha_publicacion);
                if (this.imageFile)        formData.append('imagen', this.imageFile);
                if (this.imageDesktopFile) formData.append('imagen_desktop', this.imageDesktopFile);

                const url = this.isEdit
                    ? `/api/cable-a-tierra/${this.articulo.id}`
                    : '/api/cable-a-tierra';
                if (this.isEdit) formData.append('_method', 'PUT');

                await axios.post(url, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
                alert(this.isEdit ? 'Artículo actualizado exitosamente' : 'Artículo creado exitosamente');
                this.$emit('saved');
            } catch (error) {
                console.error('Error al guardar:', error);
                if (error.response?.data?.errors) {
                    alert('Errores:\n' + Object.values(error.response.data.errors).flat().join('\n'));
                } else {
                    alert('Error al guardar el artículo');
                }
            } finally {
                this.saving = false;
            }
        }
    }
};
</script>

<style scoped>
:deep(.ql-container) { min-height: 200px; font-size: 14px; }
:deep(.ql-editor)    { min-height: 200px; }
</style>
