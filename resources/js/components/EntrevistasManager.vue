<template>
    <div class="entrevistas-manager">
        <!-- Header con botón crear y búsqueda -->
        <div class="mb-4 flex gap-4 items-center">
            <button
                @click="openCreateModal"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium"
            >
                Crear Nueva Entrevista
            </button>

            <div class="flex-1">
                <input
                    v-model="searchQuery"
                    @input="handleSearch"
                    type="text"
                    placeholder="Buscar entrevistas por título, entrevistado o cargo..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
            <p class="text-gray-500 text-lg">Cargando entrevistas...</p>
        </div>

        <!-- Lista de entrevistas -->
        <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Imagen
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Título
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Entrevistado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cargo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha Publicación
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="entrevista in entrevistas" :key="entrevista.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img
                                v-if="entrevista.imagen"
                                :src="`/storage/${entrevista.imagen}`"
                                :alt="entrevista.titulo"
                                class="w-16 h-16 object-cover rounded"
                            />
                            <span v-else class="text-gray-400 text-sm">Sin imagen</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ truncate(entrevista.titulo, 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ entrevista.entrevistado }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">
                                {{ entrevista.cargo || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                {{ formatDate(entrevista.fecha_publicacion) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button
                                @click="openEditModal(entrevista)"
                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                            >
                                Editar
                            </button>
                            <button
                                @click="deleteEntrevista(entrevista.id)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Mensaje cuando no hay entrevistas -->
            <div v-if="entrevistas.length === 0" class="text-center py-12">
                <p class="text-gray-500">No se encontraron entrevistas</p>
            </div>

            <!-- Paginación -->
            <div v-if="pagination.last_page > 1" class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button
                        @click="changePage(pagination.current_page - 1)"
                        :disabled="pagination.current_page === 1"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    >
                        Anterior
                    </button>
                    <button
                        @click="changePage(pagination.current_page + 1)"
                        :disabled="pagination.current_page === pagination.last_page"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    >
                        Siguiente
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Mostrando página <span class="font-medium">{{ pagination.current_page }}</span> de
                            <span class="font-medium">{{ pagination.last_page }}</span>
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <button
                                v-for="page in visiblePages"
                                :key="page"
                                @click="changePage(page)"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    page === pagination.current_page
                                        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                ]"
                            >
                                {{ page }}
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de formulario -->
        <EntrevistasForm
            v-if="showModal"
            :entrevista="selectedEntrevista"
            @close="closeModal"
            @saved="handleSaved"
        />
    </div>
</template>

<script>
import axios from 'axios';
import EntrevistasForm from './EntrevistasForm.vue';

export default {
    name: 'EntrevistasManager',
    components: {
        EntrevistasForm
    },
    data() {
        return {
            entrevistas: [],
            loading: true,
            showModal: false,
            selectedEntrevista: null,
            searchQuery: '',
            searchTimeout: null,
            pagination: {
                current_page: 1,
                last_page: 1,
                per_page: 15,
                total: 0
            }
        };
    },
    computed: {
        visiblePages() {
            const current = this.pagination.current_page;
            const last = this.pagination.last_page;
            const delta = 2;
            const range = [];
            const rangeWithDots = [];

            for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
                range.push(i);
            }

            if (current - delta > 2) {
                rangeWithDots.push(1, '...');
            } else {
                rangeWithDots.push(1);
            }

            rangeWithDots.push(...range);

            if (current + delta < last - 1) {
                rangeWithDots.push('...', last);
            } else if (last > 1) {
                rangeWithDots.push(last);
            }

            return rangeWithDots;
        }
    },
    mounted() {
        this.loadEntrevistas();
    },
    methods: {
        loadEntrevistas(page = 1) {
            this.loading = true;

            const params = {
                page: page,
                per_page: this.pagination.per_page
            };

            if (this.searchQuery) {
                params.search = this.searchQuery;
            }

            axios.get('/api/entrevistas', { params })
                .then(response => {
                    this.entrevistas = response.data.data;
                    this.pagination = {
                        current_page: response.data.current_page,
                        last_page: response.data.last_page,
                        per_page: response.data.per_page,
                        total: response.data.total
                    };
                })
                .catch(error => {
                    console.error('Error al cargar entrevistas:', error);
                    alert('Error al cargar las entrevistas');
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        handleSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.loadEntrevistas(1);
            }, 300);
        },
        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.loadEntrevistas(page);
            }
        },
        openCreateModal() {
            this.selectedEntrevista = null;
            this.showModal = true;
        },
        openEditModal(entrevista) {
            this.selectedEntrevista = { ...entrevista };
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.selectedEntrevista = null;
        },
        handleSaved() {
            this.closeModal();
            this.loadEntrevistas(this.pagination.current_page);
        },
        deleteEntrevista(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta entrevista?')) {
                return;
            }

            axios.delete(`/api/entrevistas/${id}`)
                .then(() => {
                    alert('Entrevista eliminada exitosamente');
                    this.loadEntrevistas(this.pagination.current_page);
                })
                .catch(error => {
                    console.error('Error al eliminar entrevista:', error);
                    alert('Error al eliminar la entrevista');
                });
        },
        truncate(text, length) {
            if (!text) return '';
            return text.length > length ? text.substring(0, length) + '...' : text;
        },
        formatDate(date) {
            if (!date) return '';
            // Para evitar problemas de zona horaria, parseamos la fecha como local
            const [year, month, day] = date.split('-');
            const localDate = new Date(year, month - 1, day);
            return localDate.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
