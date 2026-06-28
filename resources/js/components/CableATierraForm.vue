<template>
    <div class="min-h-screen bg-gray-100 py-6">
        <form @submit.prevent="submitForm" class="w-[90vw] mx-auto space-y-6">

            <!-- ═══ BLOQUE 1 — Barra superior ═══ -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button type="button" @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="form.publicado = !form.publicado"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-[#fc5648] focus:ring-offset-2"
                            :class="form.publicado ? 'bg-[#fc5648]' : 'bg-gray-200'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                :class="form.publicado ? 'translate-x-6' : 'translate-x-1'"/>
                        </button>
                        <span class="text-sm font-semibold transition-colors"
                            :class="form.publicado ? 'text-[#fc5648]' : 'text-gray-400'">
                            {{ form.publicado ? 'Publicado' : 'Borrador' }}
                        </span>
                    </div>
                </div>
                <button type="submit" :disabled="saving"
                    class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-gray-800 transition-colors disabled:opacity-60">
                    <svg v-if="saving" class="animate-spin h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    {{ saving ? 'Guardando…' : 'Guardar cambios' }}
                </button>
            </div>

            <!-- ═══ BLOQUE 2 — Título · Resumen · Portada ═══ -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Col 1: Título, Slug y meta -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Título</label>
                        <input v-model="form.titulo" @input="onTituloInput" type="text"
                            class="w-full text-base font-semibold border-0 border-b border-gray-200 focus:border-[#fc5648] focus:ring-0 pb-2 outline-none placeholder-gray-300"
                            placeholder="Título del artículo"/>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Slug</label>
                        <div class="flex items-center gap-1 border-b border-gray-200 focus-within:border-[#fc5648] pb-1">
                            <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">/cable-a-tierra/</span>
                            <input v-model="form.slug" @input="slugEdited = true" type="text"
                                class="flex-1 text-xs border-0 focus:ring-0 outline-none text-gray-600 min-w-0"/>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Autor</label>
                        <input v-model="form.autor" type="text"
                            class="w-full text-sm border-0 border-b border-gray-200 focus:border-[#fc5648] focus:ring-0 pb-1 outline-none placeholder-gray-300"
                            placeholder="Nombre del autor (opcional)"/>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Fecha</label>
                        <input v-model="form.fecha_publicacion" type="date"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-[#fc5648] focus:border-transparent"/>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Video YouTube</label>
                        <input v-model="form.video_youtube" type="text"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-[#fc5648] focus:border-transparent"
                            placeholder="URL de YouTube (opcional)"/>
                        <p v-if="youtubeEmbedUrl" class="text-[11px] text-green-600 mt-1">✓ Video detectado</p>
                        <p v-else-if="form.video_youtube" class="text-[11px] text-red-500 mt-1">URL no reconocida</p>
                    </div>
                </div>

                <!-- Col 2: Resumen -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Resumen</label>
                    <textarea v-model="form.resumen" rows="12"
                        class="flex-1 w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#fc5648] focus:border-transparent resize-none"
                        placeholder="Breve resumen del artículo…"></textarea>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[11px] text-gray-400">{{ resumenWords }} palabras</span>
                        <span class="text-[11px] font-medium"
                            :class="resumenCharsLeft < 0 ? 'text-red-500' : resumenCharsLeft < 150 ? 'text-amber-500' : 'text-gray-400'">
                            {{ form.resumen.length }} / 600
                            <span v-if="resumenCharsLeft <= 150" class="ml-1">({{ resumenCharsLeft < 0 ? 0 : resumenCharsLeft }} restantes)</span>
                        </span>
                    </div>
                </div>

                <!-- Col 3: Portadas -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Portada (móvil)</label>
                        <div v-if="imagePreview || (isEdit && articulo?.imagen)" class="mb-3">
                            <img :src="imagePreview || `/storage/${articulo.imagen}`"
                                class="w-full aspect-video rounded-xl object-cover"/>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex cursor-pointer">
                                <span class="bg-[#fff0ef] text-[#fc5648] text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-[#fde0de] transition-colors">
                                    {{ imagePreview || (isEdit && articulo?.imagen) ? 'Cambiar' : 'Elegir imagen' }}
                                </span>
                                <input ref="imageInput" type="file" accept="image/jpeg,image/jpg,image/png,image/gif"
                                    @change="handleImageChange" class="hidden"/>
                            </label>
                            <button v-if="imagePreview || (isEdit && articulo?.imagen)"
                                type="button" @click="removeImage"
                                class="text-xs text-gray-400 hover:text-red-500 transition-colors">Quitar</button>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Portada (desktop)</label>
                        <div v-if="imageDesktopPreview || (isEdit && articulo?.imagen_desktop)" class="mb-3">
                            <img :src="imageDesktopPreview || `/storage/${articulo.imagen_desktop}`"
                                class="w-full aspect-video rounded-xl object-cover"/>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex cursor-pointer">
                                <span class="bg-[#fff0ef] text-[#fc5648] text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-[#fde0de] transition-colors">
                                    {{ imageDesktopPreview || (isEdit && articulo?.imagen_desktop) ? 'Cambiar' : 'Elegir imagen' }}
                                </span>
                                <input ref="imageDesktopInput" type="file" accept="image/jpeg,image/jpg,image/png,image/gif"
                                    @change="handleImageDesktopChange" class="hidden"/>
                            </label>
                            <button v-if="imageDesktopPreview || (isEdit && articulo?.imagen_desktop)"
                                type="button" @click="removeImageDesktop"
                                class="text-xs text-gray-400 hover:text-red-500 transition-colors">Quitar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ BLOQUE 3 — Galería · Editor · Preview ═══ -->
            <div class="grid gap-6 grid-cols-1 lg:grid-cols-[210px_1fr_190px]">

                <!-- Galería (210px) -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 lg:sticky lg:top-20 lg:self-start lg:overflow-y-auto lg:max-h-[calc(100vh-120px)]">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Imágenes</span>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-gray-400">{{ imagenesContenido.length }}/20</span>
                            <label v-if="imagenesContenido.length < 20" title="Añadir imágenes"
                                class="cursor-pointer text-[#fc5648] hover:text-[#d94439] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"/>
                                </svg>
                                <input type="file" multiple
                                    accept="image/jpeg,image/jpg,image/png,image/gif"
                                    class="hidden"
                                    @change="onNewSlotFileChange"/>
                            </label>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mb-3 leading-relaxed">
                        El nº indica tras qué párrafo aparece, o vacío para automático
                    </p>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- Slots con imagen -->
                        <template v-for="(slot, i) in imagenesContenido" :key="'slot-' + i">
                            <div class="space-y-1">
                                <div class="relative rounded-lg overflow-hidden bg-gray-100" style="aspect-ratio:1/1">
                                    <img :src="slot.preview" class="w-full h-full object-cover"/>
                                    <div v-if="slot.marcarEliminar"
                                        class="absolute inset-0 bg-red-500 bg-opacity-70 flex items-center justify-center cursor-pointer p-1"
                                        @click="toggleSlotEliminar(i)">
                                        <span class="text-white text-[8px] font-bold text-center leading-tight">
                                            Se borrará · clic p/deshacer
                                        </span>
                                    </div>
                                    <button v-else type="button" @click="toggleSlotEliminar(i)"
                                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-4 h-4 flex items-center justify-center text-[9px] leading-none transition-colors">
                                        ✕
                                    </button>
                                </div>
                                <input v-model="slot.posicion" type="number" min="1"
                                    :max="cantidadParrafos || 999"
                                    placeholder="párrafo (auto)"
                                    class="w-full text-[10px] text-center border border-gray-200 rounded px-1 py-0.5 focus:ring-1 focus:ring-[#fc5648] focus:border-transparent"/>
                            </div>
                        </template>

                        <!-- Slots vacíos -->
                        <template v-for="n in emptySlots" :key="'empty-' + n">
                            <div class="space-y-1">
                                <label :for="'new-slot-' + n"
                                    class="rounded-lg border-2 border-dashed border-gray-300 hover:border-[#fc5648] transition-colors cursor-pointer flex items-center justify-center"
                                    style="aspect-ratio:1/1">
                                    <span class="text-gray-300 text-2xl select-none">+</span>
                                </label>
                                <input :id="'new-slot-' + n" type="file" multiple
                                    accept="image/jpeg,image/jpg,image/png,image/gif"
                                    class="hidden"
                                    @change="onNewSlotFileChange"/>
                                <div class="h-5"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Editor (flex-1) -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Contenido</span>
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] text-gray-400">{{ contenidoWords }} palabras</span>
                            <span class="text-[10px] font-medium"
                                :class="contenidoCharsLeft < 0 ? 'text-red-500' : contenidoCharsLeft < 5000 ? 'text-amber-500' : 'text-gray-400'">
                                {{ contenidoChars.toLocaleString('es-CL') }} / 50 000
                                <span v-if="contenidoCharsLeft <= 5000" class="ml-1">({{ contenidoCharsLeft < 0 ? 0 : contenidoCharsLeft }} restantes)</span>
                            </span>
                        </div>
                    </div>
                    <div ref="quillEditor" class="border border-gray-200 rounded-xl overflow-hidden"></div>
                </div>

                <!-- Preview (190px, solo desktop) -->
                <div class="hidden lg:flex flex-col bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sticky top-20 self-start overflow-y-auto max-h-[calc(100vh-120px)]">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Dónde quedarán</span>
                        <span class="text-[10px] text-gray-400">{{ blocksPreview.length }}</span>
                    </div>

                    <div class="space-y-0.5 flex-1 min-h-0">
                        <template v-for="(block, i) in blocksPreview" :key="'block-' + i">
                            <div class="flex items-start gap-1.5 py-1">
                                <span class="flex-shrink-0 w-5 h-5 rounded-full bg-[#fc5648] text-white text-[9px] font-bold flex items-center justify-center">
                                    {{ i + 1 > 9 ? '…' : i + 1 }}
                                </span>
                                <span class="text-[10px] text-gray-500 leading-snug">
                                    {{ block.length > 80 ? block.slice(0, 80) + '…' : block }}
                                </span>
                            </div>
                            <template v-if="computedImagesByBlock[i + 1]">
                                <div v-for="img in computedImagesByBlock[i + 1]" :key="'bi-' + i + '-' + img.idx"
                                    class="ml-6 my-1" :class="img.auto ? 'opacity-50' : ''">
                                    <img :src="img.preview" class="h-14 w-full object-cover rounded-lg"/>
                                    <span v-if="img.auto" class="text-[9px] text-gray-400">auto</span>
                                </div>
                            </template>
                        </template>

                        <template v-if="imagesAtEnd.length > 0">
                            <div class="flex items-center gap-1 pt-2 border-t border-gray-100 mt-2">
                                <span class="text-[10px] text-gray-400">↓ Al final del artículo</span>
                            </div>
                            <div v-for="img in imagesAtEnd" :key="'end-' + img.idx" class="mt-1">
                                <img :src="img.preview" class="h-14 w-full object-cover rounded-lg"/>
                            </div>
                        </template>
                    </div>

                    <p class="text-[9px] text-gray-400 mt-3 border-t border-gray-100 pt-3 leading-relaxed">
                        ↓ aparece después del bloque. Sin nº → automático.
                    </p>
                </div>

            </div>
        </form>
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
    emits: ['close', 'saved'],
    data() {
        return {
            form: {
                titulo: '',
                slug: '',
                autor: '',
                resumen: '',
                contenido: '',
                video_youtube: '',
                fecha_publicacion: new Date().toISOString().split('T')[0],
                publicado: true,
            },
            slugEdited: false,
            imageFile: null,
            imagePreview: null,
            imageDesktopFile: null,
            imageDesktopPreview: null,
            quillInstance: null,
            saving: false,
            imagenesContenido: [],
        };
    },
    computed: {
        isEdit() {
            return this.articulo !== null;
        },
        youtubeEmbedUrl() {
            const url = this.form.video_youtube?.trim();
            if (!url) return null;
            const match = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/);
            return match ? `https://www.youtube.com/embed/${match[1]}` : null;
        },
        cantidadParrafos() {
            const matches = this.form.contenido.match(/<\/(p|h[1-6]|li)>/gi);
            return matches ? matches.length : 0;
        },
        resumenWords() {
            const t = this.form.resumen.trim();
            return t ? t.split(/\s+/).length : 0;
        },
        resumenCharsLeft() {
            return 600 - this.form.resumen.length;
        },
        contenidoWords() {
            const text = this.form.contenido.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
            return text ? text.split(/\s+/).length : 0;
        },
        contenidoChars() {
            return this.form.contenido.replace(/<[^>]+>/g, '').length;
        },
        contenidoCharsLeft() {
            return 50000 - this.contenidoChars;
        },
        emptySlots() {
            const filled = this.imagenesContenido.length;
            if (filled >= 20) return 0;
            return Math.min(2, 20 - filled);
        },
        blocksPreview() {
            const html = this.form.contenido;
            if (!html) return [];
            const blocks = [];
            const re = /<(?:p|h[1-6]|li)(?:[^>]*)>([\s\S]*?)<\/(?:p|h[1-6]|li)>/gi;
            let match;
            while ((match = re.exec(html)) !== null) {
                const text = match[1].replace(/<[^>]+>/g, '').replace(/&nbsp;/g, ' ').trim();
                if (text) blocks.push(text);
            }
            return blocks;
        },
        computedImagesByBlock() {
            const total = this.cantidadParrafos;
            const result = {};
            const active = this.imagenesContenido.filter(img => img.preview && !img.marcarEliminar);
            const withPos = active.filter(img => img.posicion && Number(img.posicion) <= total);
            const autoImgs = active.filter(img => !img.posicion);

            withPos.forEach(img => {
                const p = Number(img.posicion);
                if (!result[p]) result[p] = [];
                result[p].push({ ...img, idx: this.imagenesContenido.indexOf(img), auto: false });
            });

            if (autoImgs.length > 0 && total > 0) {
                const intervalo = Math.floor(total / (autoImgs.length + 1));
                autoImgs.forEach((img, i) => {
                    const pos = Math.min((i + 1) * (intervalo || 1), total);
                    if (!result[pos]) result[pos] = [];
                    result[pos].push({ ...img, idx: this.imagenesContenido.indexOf(img), auto: true });
                });
            }

            return result;
        },
        imagesAtEnd() {
            const total = this.cantidadParrafos;
            const active = this.imagenesContenido.filter(img => img.preview && !img.marcarEliminar);
            const beyondEnd = active.filter(img => img.posicion && Number(img.posicion) > total);
            const autoNoBlocks = total === 0 ? active.filter(img => !img.posicion) : [];
            return [...beyondEnd, ...autoNoBlocks].map(img => ({
                ...img, idx: this.imagenesContenido.indexOf(img)
            }));
        },
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
                placeholder: 'Escriba el contenido del artículo…',
                modules: {
                    toolbar: {
                        container: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ header: 2 }, { header: 3 }],
                            ['blockquote'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['link', 'image'],
                            [{ align: [] }],
                            ['clean'],
                        ],
                        handlers: { image: this.handleQuillImageUpload },
                    },
                },
            });
            this.quillInstance.on('text-change', () => {
                this.form.contenido = this.quillInstance.root.innerHTML;
            });
        },

        handleQuillImageUpload() {
            // Guardar selección ANTES de abrir el file picker (pierde el foco)
            const range = this.quillInstance.getSelection() ?? { index: this.quillInstance.getLength(), length: 0 };
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/jpeg,image/jpg,image/png,image/gif');
            input.click();
            input.onchange = async () => {
                const file = input.files[0];
                if (!file) return;
                if (file.size > 5 * 1024 * 1024) { alert('La imagen no debe superar los 5MB'); return; }
                try {
                    const fd = new FormData();
                    fd.append('imagen', file);
                    const res = await axios.post('/api/cable-a-tierra/upload-imagen', fd, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                    });
                    this.quillInstance.insertEmbed(range.index, 'image', res.data.url, 'user');
                    this.quillInstance.setSelection(range.index + 1, 'silent');
                } catch (e) {
                    console.error('Error al subir imagen:', e);
                    alert('Error al subir imagen al servidor');
                }
            };
        },

        loadArticuloData() {
            this.form.titulo = this.articulo.titulo || '';
            this.form.slug = this.articulo.slug || '';
            this.form.autor = this.articulo.autor || '';
            this.form.resumen = this.articulo.resumen || '';
            this.form.video_youtube = this.articulo.video_youtube || '';
            this.form.fecha_publicacion = this.articulo.fecha_publicacion || new Date().toISOString().split('T')[0];
            this.form.publicado = this.articulo.publicado ?? true;
            this.slugEdited = true;

            if (this.articulo.contenido) {
                const { textoSolo, slots } = this.extraerImagenes(this.articulo.contenido);
                this.imagenesContenido = slots;
                this.quillInstance.root.innerHTML = textoSolo;
                this.form.contenido = textoSolo;
            }
        },

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
                        if (src) slots.push({ archivo: null, preview: src, posicion: n, url: src, marcarEliminar: false });
                    }
                    return `</${tag}>`;
                }
            );
            return { textoSolo, slots };
        },

        generarSlug(titulo) {
            return titulo
                .toLowerCase()
                .normalize('NFD')
                .replace(/[̀-ͯ]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        },

        onTituloInput() {
            if (!this.slugEdited) {
                this.form.slug = this.generarSlug(this.form.titulo);
            }
        },

        toggleSlotEliminar(i) {
            this.imagenesContenido[i].marcarEliminar = !this.imagenesContenido[i].marcarEliminar;
        },

        onNewSlotFileChange(event) {
            const files = Array.from(event.target.files);
            for (const file of files) {
                if (this.imagenesContenido.length >= 20) break;
                if (file.size > 5 * 1024 * 1024) {
                    alert(`"${file.name}" supera los 5 MB y fue omitida`);
                    continue;
                }
                const newSlot = { archivo: file, preview: null, posicion: null, url: null, marcarEliminar: false };
                this.imagenesContenido.push(newSlot);
                const idx = this.imagenesContenido.length - 1;
                const reader = new FileReader();
                reader.onload = e => { this.imagenesContenido[idx].preview = e.target.result; };
                reader.readAsDataURL(file);
            }
            event.target.value = '';
        },

        handleImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) { alert('Máximo 2MB'); this.$refs.imageInput.value = ''; return; }
            if (!['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].includes(file.type)) {
                alert('Solo JPG, PNG o GIF'); this.$refs.imageInput.value = ''; return;
            }
            this.imageFile = file;
            const r = new FileReader();
            r.onload = e => { this.imagePreview = e.target.result; };
            r.readAsDataURL(file);
        },

        removeImage() {
            this.imageFile = null;
            this.imagePreview = null;
            if (this.$refs.imageInput) this.$refs.imageInput.value = '';
        },

        handleImageDesktopChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) { alert('Máximo 2MB'); this.$refs.imageDesktopInput.value = ''; return; }
            if (!['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].includes(file.type)) {
                alert('Solo JPG, PNG o GIF'); this.$refs.imageDesktopInput.value = ''; return;
            }
            this.imageDesktopFile = file;
            const r = new FileReader();
            r.onload = e => { this.imageDesktopPreview = e.target.result; };
            r.readAsDataURL(file);
        },

        removeImageDesktop() {
            this.imageDesktopFile = null;
            this.imageDesktopPreview = null;
            if (this.$refs.imageDesktopInput) this.$refs.imageDesktopInput.value = '';
        },

        buildContenidoFinal() {
            const total = this.cantidadParrafos;
            const active = this.imagenesContenido.filter(s => s.url && !s.marcarEliminar);
            const withPos = active.filter(s => s.posicion).sort((a, b) => Number(a.posicion) - Number(b.posicion));
            const autoImgs = active.filter(s => !s.posicion);

            const byBlock = {};
            withPos.forEach(s => {
                const p = Number(s.posicion);
                if (!byBlock[p]) byBlock[p] = [];
                byBlock[p].push(s.url);
            });

            if (autoImgs.length > 0 && total > 0) {
                const intervalo = Math.floor(total / (autoImgs.length + 1));
                autoImgs.forEach((s, i) => {
                    const pos = Math.min((i + 1) * (intervalo || 1), total);
                    if (!byBlock[pos]) byBlock[pos] = [];
                    byBlock[pos].push(s.url);
                });
            }

            let n = 0;
            let result = this.form.contenido.replace(/<\/(p|h[1-6]|li)>/gi, match => {
                n++;
                const imgs = byBlock[n] || [];
                return match + imgs.map(url =>
                    `<img src="${url}" alt="" style="max-width:100%;height:auto;display:block;margin:1.5rem auto;">`
                ).join('');
            });

            if (autoImgs.length > 0 && total === 0) {
                result += autoImgs.map(s =>
                    `<img src="${s.url}" alt="" style="max-width:100%;height:auto;display:block;margin:1.5rem auto;">`
                ).join('');
            }

            return result;
        },

        async submitForm() {
            if (!this.form.titulo.trim()) { alert('El título es obligatorio'); return; }
            if (!this.form.resumen.trim()) { alert('El resumen es obligatorio'); return; }
            if (!this.form.contenido.trim() || this.form.contenido === '<p><br></p>') {
                alert('El contenido es obligatorio'); return;
            }
            if (!this.form.fecha_publicacion) { alert('La fecha es obligatoria'); return; }

            this.saving = true;
            try {
                for (const slot of this.imagenesContenido) {
                    if (slot.archivo && !slot.url && !slot.marcarEliminar) {
                        const fd = new FormData();
                        fd.append('imagen', slot.archivo);
                        const res = await axios.post('/api/cable-a-tierra/upload-imagen', fd, {
                            headers: { 'Content-Type': 'multipart/form-data' },
                        });
                        slot.url = res.data.url;
                    }
                }

                const contenidoFinal = this.buildContenidoFinal();

                const formData = new FormData();
                formData.append('titulo', this.form.titulo);
                formData.append('slug', this.form.slug);
                formData.append('autor', this.form.autor || '');
                formData.append('resumen', this.form.resumen);
                formData.append('contenido', contenidoFinal);
                formData.append('video_youtube', this.form.video_youtube || '');
                formData.append('fecha_publicacion', this.form.fecha_publicacion);
                formData.append('publicado', this.form.publicado ? '1' : '0');
                if (this.imageFile) formData.append('imagen', this.imageFile);
                if (this.imageDesktopFile) formData.append('imagen_desktop', this.imageDesktopFile);

                const url = this.isEdit ? `/api/cable-a-tierra/${this.articulo.id}` : '/api/cable-a-tierra';
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
        },
    },
};
</script>

<style scoped>
:deep(.ql-toolbar) {
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    border-color: #e5e7eb;
}
:deep(.ql-container) {
    min-height: 400px;
    font-size: 14px;
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
    border-color: #e5e7eb;
}
:deep(.ql-editor) { min-height: 400px; }
</style>
