<template>
    <div class="editoriales-form-container">
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
                        {{ editMode ? 'Editar Editorial' : 'Nueva Editorial' }}
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
                    <!-- Campos superiores en una fila -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6 p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-lg border border-orange-200">
                        <!-- Título -->
                        <div class="lg:col-span-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-1 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Título de la Editorial
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input
                                v-model="form.titulo"
                                type="text"
                                required
                                placeholder="Ingresa un título para la editorial"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm"
                                :class="{ 'border-red-500 focus:ring-red-500': errors.titulo }"
                            />
                            <p v-if="errors.titulo" class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ errors.titulo[0] }}
                            </p>
                        </div>

                        <!-- Revista -->
                        <div class="lg:col-span-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Revista
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select
                                v-model="form.revista_id"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm bg-white"
                                :class="{ 'border-red-500 focus:ring-red-500': errors.revista_id }"
                            >
                                <option value="">Selecciona una revista</option>
                                <option v-for="revista in revistas" :key="revista.id" :value="revista.id">
                                    {{ revista.titulo }}
                                </option>
                            </select>
                            <p v-if="errors.revista_id" class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ errors.revista_id[0] }}
                            </p>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="mb-6">
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Contenido de la Editorial
                            <span class="text-red-500 ml-1">*</span>
                        </label>

                        <!-- Editor Quill -->
                        <div ref="quillEditor" class="bg-white rounded-lg shadow-sm"></div>
                        <p v-if="errors.contenido" class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ errors.contenido[0] }}
                        </p>
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
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 disabled:opacity-50 disabled:cursor-not-allowed"
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
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

export default {
    name: 'EditorialesForm',

    props: {
        show: {
            type: Boolean,
            default: false
        },
        editorial: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: {
                titulo: '',
                contenido: '',
                revista_id: ''
            },
            revistas: [],
            errors: {},
            loading: false,
            editMode: false,
            quill: null
        };
    },

    watch: {
        show(value) {
            if (value) {
                this.loadRevistas();

                this.$nextTick(() => {
                    this.initQuill();

                    if (this.editorial) {
                        this.editMode = true;
                        this.form.titulo = this.editorial.titulo;
                        this.form.revista_id = this.editorial.revista_id;

                        if (this.quill && this.editorial.contenido) {
                            this.quill.root.innerHTML = this.editorial.contenido;
                        }
                    } else {
                        this.editMode = false;
                        this.resetForm();
                    }
                });
            }
        }
    },

    methods: {
        initQuill() {
            if (this.quill) {
                return;
            }

            this.quill = new Quill(this.$refs.quillEditor, {
                theme: 'snow',
                placeholder: 'Escribe el contenido de la editorial aquí...',
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
        },

        async loadRevistas() {
            try {
                const response = await axios.get('/api/revistas', {
                    params: { per_page: 100 }
                });
                this.revistas = response.data.data;
            } catch (error) {
                console.error('Error loading revistas:', error);
            }
        },

        async submitForm() {
            this.errors = {};
            this.loading = true;

            this.form.contenido = this.quill.root.innerHTML;

            try {
                let response;
                if (this.editMode) {
                    response = await axios.put(`/api/editoriales/${this.editorial.id}`, this.form);
                    this.$emit('success', 'Editorial actualizada exitosamente');
                } else {
                    response = await axios.post('/api/editoriales', this.form);
                    this.$emit('success', 'Editorial creada exitosamente');
                }

                this.resetForm();
                this.closeModal();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Error al guardar la editorial');
                }
                console.error('Error:', error);
            } finally {
                this.loading = false;
            }
        },

        closeModal() {
            this.resetForm();
            this.$emit('close');
        },

        resetForm() {
            this.form = {
                titulo: '',
                contenido: '',
                revista_id: ''
            };
            this.errors = {};
            this.editMode = false;

            if (this.quill) {
                this.quill.root.innerHTML = '';
            }
        }
    },

    beforeUnmount() {
        if (this.quill) {
            this.quill = null;
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
