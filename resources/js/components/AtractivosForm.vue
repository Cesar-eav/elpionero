<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4">
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
                            v-model.number="formData.categoria_id"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Selecciona una categoría</option>
                            <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
                                {{ cat.icono }} {{ cat.nombre }}
                            </option>
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
                categoria_id: '',
                ciudad: '',
                autor: '',
                enlace: '',
                lng: '',
                lat: '',
                horario: '',
                tags: [],
            },
            categorias: [],
            tagsInput: '',
            imagePreview: null,
            imageFile: null,
        };
    },
    async mounted() {
        // Cargar categorías primero
        try {
            const response = await axios.get('/api/categorias');
            this.categorias = response.data;
        } catch (error) {
            console.error('Error cargando categorías:', error);
        }

        // Luego, si estamos editando, cargar los datos del atractivo
        if (this.isEditing && this.atractivo) {
            // Copiar los datos del atractivo
            this.formData = {
                title: this.atractivo.title || '',
                description: this.atractivo.description || '',
                categoria_id: this.atractivo.categoria_id || '',
                ciudad: this.atractivo.ciudad || '',
                autor: this.atractivo.autor || '',
                enlace: this.atractivo.enlace || '',
                lng: this.atractivo.lng || '',
                lat: this.atractivo.lat || '',
                horario: this.atractivo.horario || '',
                tags: this.atractivo.tags || [],
                id: this.atractivo.id || null,
            };
            
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
    console.log('=== INICIO submitForm ===');
    console.log('formData.categoria_id TYPE:', typeof this.formData.categoria_id);
    console.log('formData.categoria_id VALUE:', this.formData.categoria_id);
    console.log('imageFile:', this.imageFile);
    
    // Procesar tags
    const tags = this.tagsInput
        .split(',')
        .map((tag) => tag.trim())
        .filter((tag) => tag);

    // Usar FormData para soportar archivos
    const formData = new FormData();
    formData.append('title', this.formData.title);
    formData.append('description', this.formData.description);
    formData.append('categoria_id', this.formData.categoria_id);
    formData.append('ciudad', this.formData.ciudad);
    formData.append('autor', this.formData.autor);
    formData.append('enlace', this.formData.enlace);
    formData.append('lng', this.formData.lng);
    formData.append('lat', this.formData.lat);
    formData.append('horario', this.formData.horario);
    
    // Agregar tags como array
    tags.forEach((tag, index) => {
        formData.append(`tags[${index}]`, tag);
    });
    
    // Agregar imagen si existe
    if (this.imageFile) {
        formData.append('image', this.imageFile);
        console.log('Imagen agregada al FormData');
    }

    try {
        let response;
        
        if (this.isEditing) {
            console.log('PUT a /api/atractivos/' + this.formData.id);
            // Para PUT con archivos, necesitamos agregar _method = PUT
            formData.append('_method', 'PUT');
            response = await axios.post(`/api/atractivos/${this.formData.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            });
        } else {
            console.log('POST a /api/atractivos');
            response = await axios.post('/api/atractivos', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            });
        }
        
        alert('✓ Guardado correctamente');
        this.$emit('close');
        
    } catch (error) {
        console.error('❌ Error:', error);
        console.error('Respuesta del servidor:', error.response?.data);
        alert('Error: ' + (error.response?.data?.message || error.message));
    }
}
    },
};
</script>
