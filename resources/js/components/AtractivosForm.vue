<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4" @click.self="$emit('close')">
        <div class="relative top-20 mx-auto p-5 border bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gray-100 border-b flex justify-between items-center p-6">
                <h2 class="text-2xl font-bold">
                    {{ isEditing ? 'Editar Atractivo' : 'Crear Nuevo Atractivo' }}
                </h2>
                <button
                    @click="$emit('close')"
                    class="text-gray-500 hover:text-gray-700 text-2xl font-bold"
                >
                    ✕
                </button>
            </div>

            <form @submit.prevent="submitForm" class="p-6 space-y-4">
                <!-- Título -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Título *</label>
                    <input
                        v-model="formData.title"
                        type="text"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ej: Museo Institucional UTFSM"
                    />
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Descripción *</label>
                    <textarea
                        v-model="formData.description"
                        required
                        rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Describe el atractivo en detalle"
                    ></textarea>
                </div>

                <!-- Grid 2 columnas -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Categoría -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Categoría *</label>
                        <select
                            v-model="formData.category"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Selecciona una categoría</option>
                            <option value="museum">Museo</option>
                            <option value="monument">Monumento</option>
                            <option value="restaurant">Restaurante</option>
                            <option value="beach">Playa</option>
                            <option value="park">Parque</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>

                    <!-- Ciudad -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Ciudad</label>
                        <select
                            v-model="formData.ciudad"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Selecciona una ciudad</option>
                            <option value="Valparaíso">Valparaíso</option>
                            <option value="Viña del Mar">Viña del Mar</option>
                            <option value="Quilpué">Quilpué</option>
                        </select>
                    </div>
                </div>

                <!-- Grid 2 columnas -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Autor -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Autor</label>
                        <input
                            v-model="formData.autor"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Admin"
                        />
                    </div>

                    <!-- Enlace -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Enlace</label>
                        <input
                            v-model="formData.enlace"
                            type="url"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="https://ejemplo.com"
                        />
                    </div>
                </div>

                <!-- Grid 2 columnas: Coordenadas -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Longitud -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Longitud (Lng)</label>
                        <input
                            v-model="formData.lng"
                            type="number"
                            step="0.00000001"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="-71.596"
                        />
                    </div>

                    <!-- Latitud -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Latitud (Lat)</label>
                        <input
                            v-model="formData.lat"
                            type="number"
                            step="0.00000001"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="-33.034"
                        />
                    </div>
                </div>

                <!-- Horario -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Horario</label>
                    <input
                        v-model="formData.horario"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ej: Lun-Vie 9:00-18:00"
                    />
                </div>

                <!-- Etiquetas -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Etiquetas (separadas por coma)</label>
                    <input
                        v-model="tagsInput"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ej: valparaiso, museo, cultura"
                    />
                </div>

                <!-- Imagen -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Imagen</label>
                    <input
                        @change="handleImageUpload"
                        type="file"
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <div v-if="imagePreview" class="mt-2">
                        <img :src="imagePreview" alt="Preview" class="w-32 h-32 object-cover rounded" />
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex gap-4 justify-end pt-4 border-t">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded-lg"
                    >
                        {{ isEditing ? 'Actualizar' : 'Crear' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AtractivosForm',
    props: {
        atractivo: {
            type: Object,
            default: null,
        },
        isEditing: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            formData: {
                title: '',
                description: '',
                category: '',
                ciudad: '',
                autor: '',
                enlace: '',
                lng: '',
                lat: '',
                horario: '',
                tags: [],
            },
            tagsInput: '',
            imagePreview: null,
            imageFile: null,
        };
    },
    mounted() {
        if (this.isEditing && this.atractivo) {
            this.formData = { ...this.atractivo };
            this.tagsInput = Array.isArray(this.atractivo.tags)
                ? this.atractivo.tags.join(', ')
                : '';
            if (this.atractivo.image) {
                this.imagePreview = `/storage/${this.atractivo.image}`;
            }
        }
    },
    methods: {
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.imageFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        async submitForm() {
            // Procesar etiquetas
            this.formData.tags = this.tagsInput
                .split(',')
                .map((tag) => tag.trim())
                .filter((tag) => tag);

            // Si hay imagen nueva, usar FormData
            if (this.imageFile) {
                const formData = new FormData();
                Object.keys(this.formData).forEach((key) => {
                    if (key === 'tags') {
                        formData.append(key, JSON.stringify(this.formData[key]));
                    } else {
                        formData.append(key, this.formData[key]);
                    }
                });
                formData.append('image', this.imageFile);

                this.$emit('save', formData);
            } else {
                this.$emit('save', this.formData);
            }
        },
    },
};
</script>
