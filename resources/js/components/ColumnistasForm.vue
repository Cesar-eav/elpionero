<template>
    <div class="columnistas-form-container">
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
                        {{ editMode ? 'Editar Columnista' : 'Nuevo Columnista' }}
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
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nombre"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.nombre }"
                            />
                            <p v-if="errors.nombre" class="mt-1 text-sm text-red-600">{{ errors.nombre[0] }}</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.email }"
                            />
                            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
                        </div>

                        <!-- Bio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Biografía
                            </label>
                            <textarea
                                v-model="form.bio"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.bio }"
                            ></textarea>
                            <p v-if="errors.bio" class="mt-1 text-sm text-red-600">{{ errors.bio[0] }}</p>
                        </div>

                        <!-- Foto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Foto
                            </label>
                            <input
                                @change="handleFileUpload"
                                type="file"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <p v-if="errors.foto" class="mt-1 text-sm text-red-600">{{ errors.foto[0] }}</p>
                        </div>

                        <!-- Participa Próximo Número -->
                        <div class="flex items-center">
                            <input
                                v-model="form.participa_proximo_numero"
                                type="checkbox"
                                id="participa"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label for="participa" class="ml-2 block text-sm text-gray-900">
                                Participa en próximo número
                            </label>
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
    name: 'ColumnistasForm',

    props: {
        show: {
            type: Boolean,
            default: false
        },
        columnista: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: {
                nombre: '',
                email: '',
                bio: '',
                foto: null,
                participa_proximo_numero: false
            },
            errors: {},
            loading: false,
            editMode: false
        };
    },

    watch: {
        show(value) {
            if (value) {
                if (this.columnista) {
                    this.editMode = true;
                    this.fillForm(this.columnista);
                } else {
                    this.editMode = false;
                    this.resetForm();
                }
            }
        }
    },

    methods: {
        fillForm(columnista) {
            this.form.nombre = columnista.nombre;
            this.form.email = columnista.email || '';
            this.form.bio = columnista.bio || '';
            this.form.participa_proximo_numero = columnista.participa_proximo_numero || false;
        },

        resetForm() {
            this.form = {
                nombre: '',
                email: '',
                bio: '',
                foto: null,
                participa_proximo_numero: false
            };
            this.errors = {};
        },

        handleFileUpload(event) {
            this.form.foto = event.target.files[0];
        },

        async submitForm() {
            this.loading = true;
            this.errors = {};

            try {
                const formData = new FormData();
                formData.append('nombre', this.form.nombre);
                if (this.form.email) formData.append('email', this.form.email);
                if (this.form.bio) formData.append('bio', this.form.bio);
                if (this.form.foto) formData.append('foto', this.form.foto);
                formData.append('participa_proximo_numero', this.form.participa_proximo_numero ? '1' : '0');

                let response;
                if (this.editMode) {
                    formData.append('_method', 'PUT');
                    response = await axios.post(`/api/columnistas/${this.columnista.id}`, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                } else {
                    response = await axios.post('/api/columnistas', formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                }

                this.$emit('success');
                this.closeModal();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Error al guardar el columnista');
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
