<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="$emit('close')">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4 pb-3 border-b">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ isEdit ? 'Editar Cable a Tierra' : 'Crear Nuevo Cable a Tierra' }}
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
                            placeholder="Ingrese el título del artículo"
                        />
                    </div>

                    <!-- Autor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Autor (opcional)
                        </label>
                        <input
                            v-model="form.autor"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nombre del autor (opcional)"
                        />
                    </div>

                    <!-- Resumen -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Resumen <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="form.resumen"
                            required
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Breve resumen del artículo"
                        ></textarea>
                    </div>

                    <!-- Imágenes en grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Imagen Móvil -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Imagen Móvil
                            </label>

                            <!-- Preview de imagen existente -->
                            <div v-if="imagePreview || (isEdit && articulo?.imagen)" class="mb-3">
                                <img
                                    :src="imagePreview || `/storage/${articulo.imagen}`"
                                    alt="Preview Móvil"
                                    class="w-full h-48 object-cover rounded border"
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
                                Vertical/Cuadrada. Máximo: 2MB
                            </p>
                        </div>

                        <!-- Imagen Desktop -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Imagen Desktop
                            </label>

                            <!-- Preview de imagen desktop existente -->
                            <div v-if="imageDesktopPreview || (isEdit && articulo?.imagen_desktop)" class="mb-3">
                                <img
                                    :src="imageDesktopPreview || `/storage/${articulo.imagen_desktop}`"
                                    alt="Preview Desktop"
                                    class="w-full h-48 object-cover rounded border"
                                />
                                <button
                                    type="button"
                                    @click="removeImageDesktop"
                                    class="mt-2 text-sm text-red-600 hover:text-red-800"
                                >
                                    Eliminar imagen
                                </button>
                            </div>

                            <!-- Input de archivo desktop -->
                            <input
                                ref="imageDesktopInput"
                                type="file"
                                accept="image/jpeg,image/jpg,image/png,image/gif"
                                @change="handleImageDesktopChange"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                Horizontal/Panorámica. Máximo: 2MB
                            </p>
                        </div>
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

                    <!-- Contenido (Editor Quill) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Contenido del Artículo <span class="text-red-500">*</span>
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
    name: 'CableATierraForm',
    props: {
        articulo: {
            type: Object,
            default: null
        }
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
            saving: false
        };
    },
    computed: {
        isEdit() {
            return this.articulo !== null;
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
                this.form.contenido = this.quillInstance.root.innerHTML;
            });
        },
        loadArticuloData() {
            this.form = {
                titulo: this.articulo.titulo || '',
                autor: this.articulo.autor || '',
                resumen: this.articulo.resumen || '',
                contenido: this.articulo.contenido || '',
                fecha_publicacion: this.articulo.fecha_publicacion || new Date().toISOString().split('T')[0]
            };

            // Cargar contenido en Quill
            if (this.articulo.contenido) {
                this.quillInstance.root.innerHTML = this.articulo.contenido;
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
        handleImageDesktopChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validar tamaño (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('La imagen no debe superar los 2MB');
                this.$refs.imageDesktopInput.value = '';
                return;
            }

            // Validar tipo
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Solo se permiten imágenes JPG, PNG o GIF');
                this.$refs.imageDesktopInput.value = '';
                return;
            }

            this.imageDesktopFile = file;

            // Crear preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageDesktopPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        removeImageDesktop() {
            this.imageDesktopFile = null;
            this.imageDesktopPreview = null;
            if (this.$refs.imageDesktopInput) {
                this.$refs.imageDesktopInput.value = '';
            }
        },
        submitForm() {
            // Validación básica
            if (!this.form.titulo.trim()) {
                alert('El título es obligatorio');
                return;
            }

            if (!this.form.resumen.trim()) {
                alert('El resumen es obligatorio');
                return;
            }

            if (!this.form.contenido.trim() || this.form.contenido === '<p><br></p>') {
                alert('El contenido es obligatorio');
                return;
            }

            if (!this.form.fecha_publicacion) {
                alert('La fecha de publicación es obligatoria');
                return;
            }

            this.saving = true;

            // Preparar FormData para enviar archivos
            const formData = new FormData();
            formData.append('titulo', this.form.titulo);
            formData.append('autor', this.form.autor || '');
            formData.append('resumen', this.form.resumen);
            formData.append('contenido', this.form.contenido);
            formData.append('fecha_publicacion', this.form.fecha_publicacion);

            if (this.imageFile) {
                formData.append('imagen', this.imageFile);
            }

            if (this.imageDesktopFile) {
                formData.append('imagen_desktop', this.imageDesktopFile);
            }

            const url = this.isEdit ? `/api/cable-a-tierra/${this.articulo.id}` : '/api/cable-a-tierra';

            if (this.isEdit) {
                formData.append('_method', 'PUT');
            }

            axios.post(url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(() => {
                    alert(this.isEdit ? 'Artículo actualizado exitosamente' : 'Artículo creado exitosamente');
                    this.$emit('saved');
                })
                .catch(error => {
                    console.error('Error al guardar artículo:', error);
                    if (error.response?.data?.errors) {
                        const errors = Object.values(error.response.data.errors).flat();
                        alert('Errores de validación:\n' + errors.join('\n'));
                    } else {
                        alert('Error al guardar el artículo');
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
