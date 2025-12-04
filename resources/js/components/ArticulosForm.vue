<template>
    <div class="articulos-form-container">
        <!-- Modal backdrop -->
        <div
            v-if="show"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeModal"
        >
            <!-- Modal -->
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ editMode ? 'Editar Artículo' : 'Nuevo Artículo' }}
                    </h3>
                    <button
                        @click="closeModal"
                        class="text-gray-400 hover:text-gray-500"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulario -->
                <form @submit.prevent="submitForm" enctype="multipart/form-data">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.titulo }"
                            />
                            <p v-if="errors.titulo" class="mt-1 text-sm text-red-600">{{ errors.titulo[0] }}</p>
                        </div>

                        <!-- Columnista -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Columnista <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.columnista_id"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.columnista_id }"
                            >
                                <option value="">Selecciona un columnista</option>
                                <option v-for="columnista in columnistas" :key="columnista.id" :value="columnista.id">
                                    {{ columnista.nombre }}
                                </option>
                            </select>
                            <p v-if="errors.columnista_id" class="mt-1 text-sm text-red-600">{{ errors.columnista_id[0] }}</p>
                        </div>

                        <!-- Revista -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Revista <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.revista_id"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.revista_id }"
                            >
                                <option value="">Selecciona una revista</option>
                                <option v-for="revista in revistas" :key="revista.id" :value="revista.id">
                                    {{ revista.titulo }}
                                </option>
                            </select>
                            <p v-if="errors.revista_id" class="mt-1 text-sm text-red-600">{{ errors.revista_id[0] }}</p>
                        </div>

                        <!-- Sección -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Sección
                            </label>
                            <input
                                v-model="form.seccion"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                        <!-- Contenido -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Contenido <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                v-model="form.contenido"
                                required
                                rows="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.contenido }"
                            ></textarea>
                            <p v-if="errors.contenido" class="mt-1 text-sm text-red-600">{{ errors.contenido[0] }}</p>
                        </div>

                        <!-- Imagen del autor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Imagen del Autor
                            </label>
                            <input
                                @change="handleFileUpload"
                                type="file"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <p v-if="errors.imagen_autor" class="mt-1 text-sm text-red-600">{{ errors.imagen_autor[0] }}</p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ loading ? 'Guardando...' : (editMode ? 'Actualizar' : 'Guardar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ArticulosForm',

    props: {
        show: {
            type: Boolean,
            default: false
        },
        articulo: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: {
                titulo: '',
                contenido: '',
                autor: '',
                seccion: '',
                revista_id: '',
                columnista_id: '',
                imagen_autor: null
            },
            revistas: [],
            columnistas: [],
            errors: {},
            loading: false,
            editMode: false
        };
    },

    watch: {
        show(value) {
            if (value) {
                this.loadSelects();
                if (this.articulo) {
                    this.editMode = true;
                    this.fillForm(this.articulo);
                } else {
                    this.editMode = false;
                    this.resetForm();
                }
            }
        }
    },

    methods: {
        async loadSelects() {
            try {
                const [revistasRes, columnistasRes] = await Promise.all([
                    axios.get('/api/revistas-list'),
                    axios.get('/api/columnistas-list')
                ]);
                this.revistas = revistasRes.data;
                this.columnistas = columnistasRes.data;
            } catch (error) {
                console.error('Error al cargar selects:', error);
            }
        },

        fillForm(articulo) {
            this.form.titulo = articulo.titulo;
            this.form.contenido = articulo.contenido;
            this.form.autor = articulo.autor || '';
            this.form.seccion = articulo.seccion || '';
            this.form.revista_id = articulo.revista_id;
            this.form.columnista_id = articulo.columnista_id;
        },

        resetForm() {
            this.form = {
                titulo: '',
                contenido: '',
                autor: '',
                seccion: '',
                revista_id: '',
                columnista_id: '',
                imagen_autor: null
            };
            this.errors = {};
        },

        handleFileUpload(event) {
            this.form.imagen_autor = event.target.files[0];
        },

        async submitForm() {
            this.loading = true;
            this.errors = {};

            try {
                const formData = new FormData();
                formData.append('titulo', this.form.titulo);
                formData.append('contenido', this.form.contenido);
                formData.append('revista_id', this.form.revista_id);
                formData.append('columnista_id', this.form.columnista_id);

                if (this.form.autor) formData.append('autor', this.form.autor);
                if (this.form.seccion) formData.append('seccion', this.form.seccion);
                if (this.form.imagen_autor) formData.append('imagen_autor', this.form.imagen_autor);

                let response;
                if (this.editMode) {
                    formData.append('_method', 'PUT');
                    response = await axios.post(`/api/articulos/${this.articulo.id}`, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                } else {
                    response = await axios.post('/api/articulos', formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                }

                alert(response.data.message);
                this.$emit('success');
                this.closeModal();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Error al guardar el artículo');
                }
                console.error('Error:', error);
            } finally {
                this.loading = false;
            }
        },

        closeModal() {
            this.resetForm();
            this.$emit('close');
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
