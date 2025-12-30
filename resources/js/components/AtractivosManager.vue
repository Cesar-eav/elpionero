<template>
    <div class="atractivos-manager">
        <!-- Barra de acciones -->
        <div class="mb-4 flex gap-4 items-center">
            <button
                @click="openCreateModal"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            >
                Crear Nuevo Atractivo
            </button>

            <!-- Buscador -->
            <div class="flex-1">
                <input
                    v-model="searchQuery"
                    @input="handleSearch"
                    type="text"
                    placeholder="Buscar atractivos por título o ciudad..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
        </div>

        <!-- Filtros y opciones de visualización -->
        <div class="mb-4 flex gap-2 items-center flex-wrap">
            <select
                v-model="filterCategory"
                @change="handleFilter"
                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Todas las categorías</option>
                <option value="museum">Museo</option>
                <option value="monument">Monumento</option>
                <option value="restaurant">Restaurante</option>
                <option value="beach">Playa</option>
                <option value="park">Parque</option>
                <option value="other">Otro</option>
            </select>

            <select
                v-model="filterCiudad"
                @change="handleFilter"
                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Todas las ciudades</option>
                <option value="Valparaíso">Valparaíso</option>
                <option value="Viña del Mar">Viña del Mar</option>
                <option value="Quilpué">Quilpué</option>
            </select>

            <!-- Opciones de visualización -->
            <div class="flex items-center gap-4 ml-4">
                <label class="flex items-center gap-1">
                    <input type="checkbox" v-model="showHorario" class="form-checkbox">
                    <span>Mostrar horario</span>
                </label>
                <label class="flex items-center gap-1">
                    <input type="checkbox" v-model="showEnlace" class="form-checkbox">
                    <span>Mostrar enlace</span>
                </label>
            </div>
        </div>

        <!-- Mensaje de éxito -->
        <div
            v-if="successMessage"
            class="bg-green-200 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
            role="alert"
        >
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ successMessage }}</span>
        </div>

        <!-- Loading state -->
        <div v-if="loading" class="text-center py-8">
            <p class="text-gray-500">Cargando atractivos...</p>
        </div>

        <!-- Tabla de atractivos -->
        <div v-else class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Imagen</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Título</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Categoría</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Ciudad</th>
                        <th v-if="showHorario" class="border border-gray-300 px-4 py-2 text-left">Horario</th>
                        <th v-if="showEnlace" class="border border-gray-300 px-4 py-2 text-left">Enlace</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="atractivo in atractivos" :key="atractivo.id" class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">
                            <img
                                v-if="atractivo.image"
                                :src="`/storage/${atractivo.image}`"
                                :alt="atractivo.title"
                                class="w-12 h-12 rounded object-cover"
                            />
                            <div v-else class="w-12 h-12 rounded bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600">N/A</span>
                            </div>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a :href="`/atractivos/${atractivo.id}`" class="hover:underline text-blue-600">
                                {{ atractivo.title }}
                            </a>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                {{ atractivo.categoria?.nombre || 'N/A' }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ atractivo.ciudad }}</td>
                        <td v-if="showHorario" class="border border-gray-300 px-4 py-2">
                            <span v-if="atractivo.horario">{{ atractivo.horario }}</span>
                            <span v-else class="text-gray-400">N/A</span>
                        </td>
                        <td v-if="showEnlace" class="border border-gray-300 px-4 py-2">
                            <a v-if="atractivo.enlace" :href="atractivo.enlace" target="_blank" class="text-blue-600 hover:underline">Ver enlace</a>
                            <span v-else class="text-gray-400">N/A</span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 flex gap-2">
                            <button
                                @click="openEditModal(atractivo)"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm"
                            >
                                Editar
                            </button>
                            <button
                                @click="deleteAtractivo(atractivo.id)"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div v-if="!loading" class="mt-4 flex justify-between items-center">
            <button
                v-if="currentPage > 1"
                @click="fetchAtractivos(currentPage - 1)"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
                Anterior
            </button>
            <span class="text-gray-700">Página {{ currentPage }}</span>
            <button
                v-if="hasNextPage"
                @click="fetchAtractivos(currentPage + 1)"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
                Siguiente
            </button>
        </div>

        <!-- Modal para crear/editar -->
        <AtractivosForm
            v-if="showModal"
            :atractivo="editingAtractivo"
            :is-editing="isEditing"
            @save="saveAtractivo"
            @close="closeModal"
        />
    </div>
</template>

<script>
import AtractivosForm from './AtractivosForm.vue';

export default {
    name: 'AtractivosManager',
    components: {
        AtractivosForm,
    },
    data() {
        return {
            atractivos: [],
            loading: false,
            showModal: false,
            editingAtractivo: null,
            isEditing: false,
            searchQuery: '',
            filterCategory: '',
            filterCiudad: '',
            currentPage: 1,
            hasNextPage: false,
            successMessage: '',
            showHorario: false,
            showEnlace: false,
        };
    },
    mounted() {
        // Obtener página de la URL si existe
        const url = new URL(window.location);
        const pageFromUrl = url.searchParams.get('atractivos_page');
        if (pageFromUrl) {
            this.currentPage = parseInt(pageFromUrl);
        }
        this.fetchAtractivos(this.currentPage);
    },
    methods: {
        async fetchAtractivos(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: page,
                    per_page: 10,
                });

                if (this.searchQuery) {
                    params.append('search', this.searchQuery);
                }
                if (this.filterCategory) {
                    params.append('category', this.filterCategory);
                }
                if (this.filterCiudad) {
                    params.append('ciudad', this.filterCiudad);
                }

                const response = await fetch(`/api/atractivos?${params}`);
                const data = await response.json();

                this.atractivos = data.data;
                this.currentPage = data.current_page;
                this.hasNextPage = data.next_page_url !== null;
                
                // Actualizar URL con la página actual
                const url = new URL(window.location);
                url.searchParams.set('atractivos_page', this.currentPage);
                window.history.pushState({}, '', url);
                
                this.loading = false;
            } catch (error) {
                console.error('Error fetching atractivos:', error);
                this.loading = false;
            }
        },

        handleSearch() {
            this.currentPage = 1;
            this.fetchAtractivos();
        },

        handleFilter() {
            this.currentPage = 1;
            this.fetchAtractivos();
        },

        openCreateModal() {
            this.editingAtractivo = null;
            this.isEditing = false;
            this.showModal = true;
        },

        openEditModal(atractivo) {
            this.editingAtractivo = { ...atractivo };
            this.isEditing = true;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editingAtractivo = null;
            this.isEditing = false;
        },

        async saveAtractivo(atractivoData) {
            // Ya se guarda en AtractivosForm, solo recargamos
            this.fetchAtractivos(this.currentPage);
            this.successMessage = '✓ Guardado correctamente';
            setTimeout(() => (this.successMessage = ''), 3000);
        },

        async deleteAtractivo(id) {
            if (confirm('¿Está seguro de que desea eliminar este atractivo?')) {
                try {
                    const response = await fetch(`/api/atractivos/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });

                    if (response.ok) {
                        this.successMessage = 'Atractivo eliminado';
                        setTimeout(() => (this.successMessage = ''), 3000);
                        this.fetchAtractivos(this.currentPage);
                    }
                } catch (error) {
                    console.error('Error deleting atractivo:', error);
                }
            }
        },
    },
};
</script>
