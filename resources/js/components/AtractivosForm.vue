<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4">
        <div class="relative top-20 mx-auto p-5 border bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gray-100 border-b flex justify-between items-center p-6">
                <h2 class="text-2xl font-bold">
                    {{ isEditing ? 'Editar Atractivo' : 'Crear Nuevo Atractivo' }}
                </h2>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">✕</button>
            </div>

            <form @submit.prevent="submitForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Título *</label>
                    <input v-model="formData.title" type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Museo Institucional UTFSM" />
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Descripción *</label>
                    <textarea v-model="formData.description" required rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe el atractivo en detalle"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Categoría *</label>
                        <select v-model.number="formData.categoria_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona una categoría</option>
                            <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
                                {{ cat.icono }} {{ cat.nombre }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Ciudad</label>
                        <select v-model="formData.ciudad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona una ciudad</option>
                            <option value="Valparaíso">Valparaíso</option>
                            <option value="Viña del Mar">Viña del Mar</option>
                            <option value="Quilpué">Quilpué</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Autor</label>
                        <input v-model="formData.autor" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Admin" />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Enlace</label>
                        <input v-model="formData.enlace" type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://ejemplo.com" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Longitud (Lng)</label>
                        <input v-model="formData.lng" type="number" step="0.00000001" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="-71.596" />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Latitud (Lat)</label>
                        <input v-model="formData.lat" type="number" step="0.00000001" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="-33.034" />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700 font-semibold">Horario</label>
                    <input v-model="formData.horario" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Lun-Vie 9:00-18:00" />
                    <div class="flex flex-col gap-2 mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="formData.show_horario" class="form-checkbox">
                            <span class="ml-2 text-sm">Mostrar horario</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="formData.show_enlace" class="form-checkbox">
                            <span class="ml-2 text-sm">Mostrar enlace</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="formData.show_galeria" class="form-checkbox">
                            <span class="ml-2 text-sm">Activar galería (freemium)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Etiquetas (separadas por coma)</label>
                    <input v-model="tagsInput" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: valparaiso, museo, cultura" />
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Imagen Principal</label>
                    <input @change="handleImageUpload" type="file" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <div v-if="imagePreview" class="mt-2">
                        <img :src="imagePreview" class="w-32 h-32 object-cover rounded border" />
                    </div>
                </div>

                <div v-if="formData.show_galeria" class="border-t pt-4">
                    <label class="block text-gray-700 font-semibold mb-2">Galería de imágenes (máx 10)</label>
                    <input type="file" accept="image/*" multiple @change="handleGaleriaUpload" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    
                    <div v-if="savedGaleria.length" class="flex flex-wrap gap-2 mt-2">
                        <div v-for="(img, idx) in savedGaleria" :key="'saved-' + idx" class="relative group">
                            <img :src="'/storage/' + img" class="w-20 h-20 object-cover rounded border" />
                            <button type="button" @click="removeSavedGaleriaImage(idx)" class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">✕</button>
                        </div>
                    </div>
                    
                    <div v-if="galeriaPreview.length" class="flex flex-wrap gap-2 mt-2">
                        <div v-for="(img, idx) in galeriaPreview" :key="'preview-' + idx" class="relative group">
                            <img :src="img" class="w-20 h-20 object-cover rounded border border-blue-400" />
                            <button type="button" @click="removeGaleriaImage(idx)" class="absolute -top-1 -right-1 bg-gray-800 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">✕</button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 justify-end pt-4 border-t">
                    <button type="button" @click="$emit('close')" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                        {{ isEditing ? 'Actualizar Atractivo' : 'Crear Atractivo' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AtractivosForm',
    props: {
        atractivo: { type: Object, default: null },
        isEditing: { type: Boolean, default: false },
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
                show_horario: false,
                show_enlace: false,
                show_galeria: false,
            },
            categorias: [],
            tagsInput: '',
            imagePreview: null,
            imageFile: null,
            // Lógica de Galería
            galeriaFiles: [],    // Archivos nuevos
            galeriaPreview: [],  // Previsualizaciones base64
            savedGaleria: [],    // Fotos que vienen de la DB
            galeriaToDelete: [], // Fotos que queremos que el backend borre
        };
    },
    async mounted() {
        try {
            const res = await axios.get('/api/categorias');
            this.categorias = res.data;
        } catch (e) { console.error("Error cats", e); }

        if (this.isEditing && this.atractivo) {
            this.formData = {
                id: this.atractivo.id,
                title: this.atractivo.title || '',
                description: this.atractivo.description || '',
                categoria_id: this.atractivo.categoria_id || '',
                ciudad: this.atractivo.ciudad || '',
                autor: this.atractivo.autor || '',
                enlace: this.atractivo.enlace || '',
                lng: this.atractivo.lng || '',
                lat: this.atractivo.lat || '',
                horario: this.atractivo.horario || '',
                show_horario: Boolean(this.atractivo.show_horario),
                show_enlace: Boolean(this.atractivo.show_enlace),
                show_galeria: Boolean(this.atractivo.show_galeria),
            };
            this.tagsInput = Array.isArray(this.atractivo.tags) ? this.atractivo.tags.join(', ') : '';
            if (this.atractivo.image) this.imagePreview = `/storage/${this.atractivo.image}`;
            if (this.atractivo.galeria) this.savedGaleria = [...this.atractivo.galeria];
        }
    },
    methods: {
        handleImageUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.imageFile = file;
            const reader = new FileReader();
            reader.onload = (e) => this.imagePreview = e.target.result;
            reader.readAsDataURL(file);
        },
        handleGaleriaUpload(e) {
            const files = Array.from(e.target.files);
            const max = 10 - this.savedGaleria.length - this.galeriaFiles.length;
            files.slice(0, max).forEach(file => {
                this.galeriaFiles.push(file);
                const reader = new FileReader();
                reader.onload = (e) => this.galeriaPreview.push(e.target.result);
                reader.readAsDataURL(file);
            });
        },
        removeGaleriaImage(idx) {
            this.galeriaFiles.splice(idx, 1);
            this.galeriaPreview.splice(idx, 1);
        },
        removeSavedGaleriaImage(idx) {
            this.galeriaToDelete.push(this.savedGaleria[idx]);
            this.savedGaleria.splice(idx, 1);
        },
        async submitForm() {
            const data = new FormData();
            
            // Llenar FormData
            Object.keys(this.formData).forEach(key => {
                // Convertir booleanos a 1/0 para que PHP los entienda fácil
                let value = this.formData[key];
                if (typeof value === 'boolean') value = value ? 1 : 0;
                data.append(key, value || '');
            });

            // Tags
            const tags = this.tagsInput.split(',').map(t => t.trim()).filter(t => t);
            tags.forEach((t, i) => data.append(`tags[${i}]`, t));

            // Archivos
            if (this.imageFile) data.append('image', this.imageFile);
            this.galeriaFiles.forEach((file, i) => data.append(`galeria[${i}]`, file));
            this.galeriaToDelete.forEach((path, i) => data.append(`galeria_delete[${i}]`, path));

            // Truco Laravel para PUT con archivos
            if (this.isEditing) data.append('_method', 'PUT');

            try {
                const url = this.isEditing ? `/api/atractivos/${this.formData.id}` : '/api/atractivos';
                await axios.post(url, data, { headers: { 'Content-Type': 'multipart/form-data' } });
                alert('¡Guardado!');
                this.$emit('close');
            } catch (error) {
                alert('Error al guardar: ' + error.response?.data?.message);
            }
        }
    }
};
</script>