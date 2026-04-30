<template>
    <div class="columnistas-form-container">
        <!-- Modal backdrop -->
        <div
            v-if="show"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeModal"
        >
            <!-- Modal -->
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ editMode ? 'Editar Columnista' : 'Nuevo Columnista' }}
                    </h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Biografía</label>
                            <textarea
                                v-model="form.bio"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            ></textarea>
                            <p v-if="errors.bio" class="mt-1 text-sm text-red-600">{{ errors.bio[0] }}</p>
                        </div>

                        <!-- Foto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto del Columnista</label>

                            <!-- Tabs -->
                            <div class="flex border-b border-gray-200 mb-3">
                                <button
                                    type="button"
                                    @click="switchMode('existing')"
                                    class="px-4 py-2 text-sm font-medium border-b-2 transition-colors"
                                    :class="fotoMode === 'existing' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                >
                                    Seleccionar existente
                                </button>
                                <button
                                    type="button"
                                    @click="switchMode('upload')"
                                    class="px-4 py-2 text-sm font-medium border-b-2 transition-colors"
                                    :class="fotoMode === 'upload' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                >
                                    Subir nueva
                                </button>
                            </div>

                            <!-- Galería de imágenes existentes -->
                            <div v-if="fotoMode === 'existing'">
                                <div class="flex flex-wrap gap-2 max-h-64 overflow-y-auto p-2 border border-gray-200 rounded-lg bg-gray-50">
                                    <div
                                        v-for="image in availableImages"
                                        :key="image"
                                        @click="form.fotoExistente = image"
                                        class="relative cursor-pointer flex-shrink-0 rounded overflow-hidden"
                                        :class="{
                                            'ring-2 ring-blue-500': form.fotoExistente === image,
                                            'hover:ring-2 hover:ring-gray-300': form.fotoExistente !== image
                                        }"
                                    >
                                        <img :src="`/storage/${image}`" :alt="image" class="w-20 h-20 object-cover" />
                                        <div v-if="form.fotoExistente === image" class="absolute top-1 right-1 bg-blue-500 text-white rounded-full p-0.5">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="form.fotoExistente" class="mt-3 p-2 bg-blue-50 rounded border border-blue-200">
                                    <div class="flex items-center gap-3">
                                        <img :src="`/storage/${form.fotoExistente}`" alt="Preview" class="w-16 h-16 object-cover rounded" />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-700">Seleccionada</p>
                                            <p class="text-xs text-gray-500 truncate">{{ form.fotoExistente }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload + Recortador -->
                            <div v-else-if="fotoMode === 'upload'">
                                <input
                                    ref="fileInput"
                                    @change="handleFileUpload"
                                    type="file"
                                    accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG, WEBP · máx. 2MB · se recortará en cuadrado 1:1</p>

                                <!-- Área de recorte -->
                                <div v-if="cropSrc" class="mt-3">
                                    <p class="text-xs font-medium text-gray-600 mb-1">Ajusta el recorte y confirma:</p>
                                    <div class="crop-wrapper border border-gray-300 rounded bg-gray-900">
                                        <img ref="cropImage" :src="cropSrc" style="display:block;max-width:100%;" />
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <button
                                            type="button"
                                            @click="confirmCrop"
                                            class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded transition"
                                        >
                                            Confirmar recorte
                                        </button>
                                        <button
                                            type="button"
                                            @click="cancelCrop"
                                            class="px-4 py-1.5 border border-gray-300 text-gray-600 text-sm rounded hover:bg-gray-50 transition"
                                        >
                                            Cancelar
                                        </button>
                                    </div>
                                </div>

                                <!-- Preview del recorte confirmado -->
                                <div v-if="croppedPreview && !cropSrc" class="mt-3 flex items-center gap-3 p-2 bg-green-50 border border-green-200 rounded">
                                    <img :src="croppedPreview" alt="Recorte" class="w-16 h-16 object-cover rounded shadow" />
                                    <div>
                                        <p class="text-xs font-semibold text-green-700">Recorte listo</p>
                                        <button type="button" @click="resetCrop" class="text-xs text-gray-500 underline mt-0.5">Cambiar imagen</button>
                                    </div>
                                </div>
                            </div>

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
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

export default {
    name: 'ColumnistasForm',

    props: {
        show: { type: Boolean, default: false },
        columnista: { type: Object, default: null }
    },

    data() {
        return {
            form: {
                nombre: '',
                email: '',
                bio: '',
                foto: null,
                fotoExistente: '',
                participa_proximo_numero: false
            },
            fotoMode: 'existing',
            availableImages: [],
            errors: {},
            loading: false,
            editMode: false,
            cropSrc: null,
            croppedPreview: null,
        };
    },

    mounted() {
        this.loadAvailableImages();
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
        async loadAvailableImages() {
            try {
                const response = await axios.get('/api/columnistas/available-images');
                this.availableImages = response.data;
            } catch (error) {
                console.error('Error cargando imágenes:', error);
            }
        },

        fillForm(columnista) {
            this.form.nombre = columnista.nombre;
            this.form.email = columnista.email || '';
            this.form.bio = columnista.bio || '';
            this.form.fotoExistente = columnista.foto || '';
            this.form.participa_proximo_numero = columnista.participa_proximo_numero || false;
        },

        resetForm() {
            this.form = {
                nombre: '',
                email: '',
                bio: '',
                foto: null,
                fotoExistente: '',
                participa_proximo_numero: false
            };
            this.fotoMode = 'existing';
            this.errors = {};
            this.destroyCropper();
        },

        switchMode(mode) {
            this.fotoMode = mode;
            this.destroyCropper();
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.destroyCropper();
            this.croppedPreview = null;
            this.form.foto = null;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.cropSrc = e.target.result;
                this.$nextTick(() => {
                    this._cropper = new Cropper(this.$refs.cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.9,
                        responsive: true,
                    });
                });
            };
            reader.readAsDataURL(file);
        },

        confirmCrop() {
            if (!this._cropper) return;
            const canvas = this._cropper.getCroppedCanvas({ width: 400, height: 400 });
            const dataURL = canvas.toDataURL('image/jpeg', 0.9);
            this.croppedPreview = dataURL;

            // Conversión síncrona de dataURL a File
            const arr = dataURL.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while (n--) u8arr[n] = bstr.charCodeAt(n);
            this.form.foto = new File([u8arr], 'foto_columnista.jpg', { type: mime });

            this.destroyCropper();
        },

        cancelCrop() {
            this.destroyCropper();
            this.$refs.fileInput.value = '';
        },

        resetCrop() {
            this.croppedPreview = null;
            this.form.foto = null;
            this.$refs.fileInput.value = '';
        },

        destroyCropper() {
            if (this._cropper) {
                this._cropper.destroy();
                this._cropper = null;
            }
            this.cropSrc = null;
        },

        async submitForm() {
            this.loading = true;
            this.errors = {};

            try {
                const formData = new FormData();
                formData.append('nombre', this.form.nombre);
                if (this.form.email) formData.append('email', this.form.email);
                if (this.form.bio) formData.append('bio', this.form.bio);

                if (this.fotoMode === 'existing' && this.form.fotoExistente) {
                    formData.append('foto_existente', this.form.fotoExistente);
                } else if (this.fotoMode === 'upload' && this.form.foto) {
                    formData.append('foto', this.form.foto);
                }

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
            this.destroyCropper();
            this.croppedPreview = null;
            this.resetForm();
            this.$emit('close');
        }
    }
};
</script>

<style scoped>
.crop-wrapper { height: 320px; }
</style>
