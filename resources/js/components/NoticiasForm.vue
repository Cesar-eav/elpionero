<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="$emit('close')">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4 pb-3 border-b">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ isEdit ? 'Editar Noticia' : 'Crear Nueva Noticia' }}
                </h3>
                <button
                    @click="$emit('close')"
                    class="text-gray-400 hover:text-gray-500"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm">
                <div class="space-y-4">
                    <!-- Título -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Título <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.titulo"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ingrese el título de la noticia"
                        />
                    </div>

                    <!-- Resumen -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Resumen
                        </label>
                        <textarea
                            v-model="form.resumen"
                            rows="3"
                            maxlength="500"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Resumen breve de la noticia (opcional, máximo 500 caracteres)"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ form.resumen ? form.resumen.length : 0 }}/500 caracteres
                        </p>
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Imagen
                        </label>

                        <!-- Preview de imagen existente -->
                        <div v-if="imagePreview || (isEdit && noticia?.imagen)" class="mb-3">
                            <img
                                :src="imagePreview || `/storage/${noticia.imagen}`"
                                alt="Preview"
                                class="w-48 h-48 object-cover rounded border"
                            />
                            <button
                                type="button"
                                @click="removeImage"
                                class="mt-2 text-sm text-red-600 hover:text-red-800"
                            >
                                Eliminar imagen
                            </button>
                        </div>

                        <!-- Input de archivo -->
                        <input
                            ref="imageInput"
                            type="file"
                            accept="image/jpeg,image/jpg,image/png,image/gif"
                            @change="handleImageChange"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB
                        </p>
                    </div>

                    <!-- Fecha de Publicación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha de Publicación <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.fecha_publicacion"
                            type="date"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <!-- Cuerpo (Editor Quill) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Contenido <span class="text-red-500">*</span>
                        </label>
                        <div ref="quillEditor" class="bg-white border border-gray-300 rounded-md"></div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="saving"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:bg-blue-300"
                    >
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
    name: 'NoticiasForm',
    props: {
        noticia: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            form: {
                titulo: '',
                resumen: '',
                cuerpo: '',
                fecha_publicacion: new Date().toISOString().split('T')[0]
            },
            imageFile: null,
            imagePreview: null,
            quillInstance: null,
            saving: false
        };
    },
    computed: {
        isEdit() {
            return this.noticia !== null;
        }
    },
    mounted() {
        this.initQuill();
        if (this.isEdit) {
            this.loadNoticiaData();
        }
    },
    methods: {
        initQuill() {
            this.quillInstance = new Quill(this.$refs.quillEditor, {
                theme: 'snow',
                placeholder: 'Escriba el contenido de la noticia...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            // Sincronizar contenido con el formulario
            this.quillInstance.on('text-change', () => {
                this.form.cuerpo = this.quillInstance.root.innerHTML;
            });
        },
        loadNoticiaData() {
            this.form = {
                titulo: this.noticia.titulo || '',
                resumen: this.noticia.resumen || '',
                cuerpo: this.noticia.cuerpo || '',
                fecha_publicacion: this.noticia.fecha_publicacion || new Date().toISOString().split('T')[0]
            };

            // Cargar contenido en Quill
            if (this.noticia.cuerpo) {
                this.quillInstance.root.innerHTML = this.noticia.cuerpo;
            }
        },
        handleImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validar tamaño (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('La imagen no debe superar los 2MB');
                this.$refs.imageInput.value = '';
                return;
            }

            // Validar tipo
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Solo se permiten imágenes JPG, PNG o GIF');
                this.$refs.imageInput.value = '';
                return;
            }

            this.imageFile = file;

            // Crear preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        removeImage() {
            this.imageFile = null;
            this.imagePreview = null;
            if (this.$refs.imageInput) {
                this.$refs.imageInput.value = '';
            }
        },
        submitForm() {
            // Validación básica
            if (!this.form.titulo.trim()) {
                alert('El título es obligatorio');
                return;
            }

            if (!this.form.cuerpo.trim() || this.form.cuerpo === '<p><br></p>') {
                alert('El contenido es obligatorio');
                return;
            }

            if (!this.form.fecha_publicacion) {
                alert('La fecha de publicación es obligatoria');
                return;
            }

            this.saving = true;

            // Preparar FormData para enviar archivo
            const formData = new FormData();
            formData.append('titulo', this.form.titulo);
            formData.append('resumen', this.form.resumen || '');
            formData.append('cuerpo', this.form.cuerpo);
            formData.append('fecha_publicacion', this.form.fecha_publicacion);

            if (this.imageFile) {
                formData.append('imagen', this.imageFile);
            }

            const url = this.isEdit ? `/api/noticias/${this.noticia.id}` : '/api/noticias';
            const method = this.isEdit ? 'post' : 'post'; // Usamos POST con _method para PUT

            if (this.isEdit) {
                formData.append('_method', 'PUT');
            }

            axios.post(url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(() => {
                    alert(this.isEdit ? 'Noticia actualizada exitosamente' : 'Noticia creada exitosamente');
                    this.$emit('saved');
                })
                .catch(error => {
                    console.error('Error al guardar noticia:', error);
                    if (error.response?.data?.errors) {
                        const errors = Object.values(error.response.data.errors).flat();
                        alert('Errores de validación:\n' + errors.join('\n'));
                    } else {
                        alert('Error al guardar la noticia');
                    }
                })
                .finally(() => {
                    this.saving = false;
                });
        }
    }
};
</script>

<style scoped>
/* Estilos para el editor Quill */
:deep(.ql-container) {
    min-height: 200px;
    font-size: 14px;
}

:deep(.ql-editor) {
    min-height: 200px;
}
</style>
