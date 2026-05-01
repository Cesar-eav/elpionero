<template>
    <div class="revistas-form-container">
        <div
            v-if="show"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeModal"
        >
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ editMode ? 'Editar Revista' : 'Nueva Revista' }}
                    </h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

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

                        <!-- Fecha de Publicación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha de Publicación <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.fecha_publicacion"
                                type="date"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.fecha_publicacion }"
                            />
                            <p v-if="errors.fecha_publicacion" class="mt-1 text-sm text-red-600">{{ errors.fecha_publicacion[0] }}</p>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea
                                v-model="form.descripcion"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            ></textarea>
                            <p v-if="errors.descripcion" class="mt-1 text-sm text-red-600">{{ errors.descripcion[0] }}</p>
                        </div>

                        <!-- Portada -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Portada</label>

                            <!-- Preview portada actual (en edición) -->
                            <div v-if="currentPortada && !newPortadaPreview" class="mb-3 flex items-center gap-3 p-2 bg-gray-50 border border-gray-200 rounded">
                                <img :src="`/storage/${currentPortada}`" alt="Portada actual" class="w-16 h-20 object-cover rounded shadow" />
                                <div>
                                    <p class="text-xs font-medium text-gray-600">Portada actual</p>
                                    <button type="button" @click="clearCurrentPortada" class="text-xs text-red-500 underline mt-0.5">Cambiar</button>
                                </div>
                            </div>

                            <!-- Input file -->
                            <div v-if="!currentPortada || newPortadaPreview !== null">
                                <input
                                    ref="portadaInput"
                                    type="file"
                                    accept="image/*"
                                    @change="handlePortadaUpload"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG, WEBP · máx. 4MB · proporción recomendada 3:4 (retrato)</p>
                            </div>

                            <!-- Preview nueva portada -->
                            <div v-if="newPortadaPreview" class="mt-3 flex items-center gap-3 p-2 bg-green-50 border border-green-200 rounded">
                                <img :src="newPortadaPreview" alt="Nueva portada" class="w-16 h-20 object-cover rounded shadow" />
                                <div>
                                    <p class="text-xs font-semibold text-green-700">Nueva portada lista</p>
                                    <button type="button" @click="resetPortada" class="text-xs text-gray-500 underline mt-0.5">Cambiar</button>
                                </div>
                            </div>

                            <p v-if="errors.portada" class="mt-1 text-sm text-red-600">{{ errors.portada[0] }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="closeModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
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
    name: 'RevistasForm',

    props: {
        show:    { type: Boolean, default: false },
        revista: { type: Object,  default: null  }
    },

    data() {
        return {
            form: {
                titulo: '',
                fecha_publicacion: '',
                descripcion: '',
            },
            portadaFile: null,
            newPortadaPreview: null,
            currentPortada: null,
            errors: {},
            loading: false,
            editMode: false,
        };
    },

    watch: {
        show(value) {
            if (value) {
                if (this.revista) {
                    this.editMode = true;
                    this.fillForm(this.revista);
                } else {
                    this.editMode = false;
                    this.resetForm();
                }
            }
        }
    },

    methods: {
        fillForm(revista) {
            this.form.titulo            = revista.titulo;
            this.form.fecha_publicacion = revista.fecha_publicacion;
            this.form.descripcion       = revista.descripcion || '';
            this.currentPortada         = revista.portada || null;
            this.newPortadaPreview      = null;
            this.portadaFile            = null;
        },

        resetForm() {
            this.form = { titulo: '', fecha_publicacion: '', descripcion: '' };
            this.portadaFile       = null;
            this.newPortadaPreview = null;
            this.currentPortada    = null;
            this.errors            = {};
        },

        handlePortadaUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.portadaFile = file;
            const reader = new FileReader();
            reader.onload = (e) => { this.newPortadaPreview = e.target.result; };
            reader.readAsDataURL(file);
        },

        clearCurrentPortada() {
            this.currentPortada = null;
            this.newPortadaPreview = null;
            this.portadaFile = null;
        },

        resetPortada() {
            this.newPortadaPreview = null;
            this.portadaFile = null;
            if (this.$refs.portadaInput) this.$refs.portadaInput.value = '';
        },

        async submitForm() {
            this.loading = true;
            this.errors  = {};

            try {
                const formData = new FormData();
                formData.append('titulo',            this.form.titulo);
                formData.append('fecha_publicacion', this.form.fecha_publicacion);
                if (this.form.descripcion) formData.append('descripcion', this.form.descripcion);
                if (this.portadaFile)      formData.append('portada', this.portadaFile);

                let response;
                if (this.editMode) {
                    formData.append('_method', 'PUT');
                    response = await axios.post(`/api/revistas/${this.revista.id}`, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                } else {
                    response = await axios.post('/api/revistas', formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                }

                this.$emit('success');
                this.closeModal();
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Error al guardar la revista');
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
