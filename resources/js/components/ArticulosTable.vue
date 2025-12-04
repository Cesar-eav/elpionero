<template>
    <div class="articulos-table-container">
        <!-- Barra de búsqueda y filtros -->
        <div class="mb-4 flex gap-4">
            <input
                v-model="searchQuery"
                @input="fetchArticulos"
                type="text"
                placeholder="Buscar artículos..."
                class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button
                @click="openCreateModal"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
                Nuevo Artículo
            </button>
        </div>

        <!-- Loading state -->
        <div v-if="loading" class="text-center py-8">
            <p class="text-gray-500">Cargando artículos...</p>
        </div>

        <!-- Tabla de artículos -->
        <div v-else class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Título
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Columnista
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Revista
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="articulo in articulos" :key="articulo.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ articulo.titulo }}</div>
                            <div class="text-sm text-gray-500">{{ truncate(articulo.contenido, 60) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ articulo.columnista?.nombre || 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ articulo.revista?.titulo || 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ formatDate(articulo.created_at) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <button
                                @click="editArticulo(articulo)"
                                class="text-blue-600 hover:text-blue-900 mr-3"
                            >
                                Editar
                            </button>
                            <button
                                @click="deleteArticulo(articulo.id)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Sin resultados -->
            <div v-if="articulos.length === 0" class="text-center py-8">
                <p class="text-gray-500">No se encontraron artículos</p>
            </div>
        </div>

        <!-- Paginación -->
        <div v-if="pagination.last_page > 1" class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-700">
                Mostrando {{ pagination.from }} a {{ pagination.to }} de {{ pagination.total }} resultados
            </div>
            <div class="flex gap-2">
                <button
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-4 py-2 border rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
                >
                    Anterior
                </button>
                <button
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-4 py-2 border rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
                >
                    Siguiente
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ArticulosTable',

    data() {
        return {
            articulos: [],
            loading: false,
            searchQuery: '',
            pagination: {
                current_page: 1,
                last_page: 1,
                from: 0,
                to: 0,
                total: 0
            }
        };
    },

    mounted() {
        this.fetchArticulos();
    },

    methods: {
        async fetchArticulos(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get('/api/articulos', {
                    params: {
                        page: page,
                        search: this.searchQuery,
                        per_page: 15
                    }
                });

                this.articulos = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    from: response.data.from,
                    to: response.data.to,
                    total: response.data.total
                };
            } catch (error) {
                console.error('Error al cargar artículos:', error);
                alert('Error al cargar los artículos');
            } finally {
                this.loading = false;
            }
        },

        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.fetchArticulos(page);
            }
        },

        openCreateModal() {
            // Emitir evento para abrir modal de creación
            this.$emit('create-articulo');
        },

        editArticulo(articulo) {
            // Emitir evento para editar artículo
            this.$emit('edit-articulo', articulo);
        },

        async deleteArticulo(id) {
            if (!confirm('¿Estás seguro de eliminar este artículo?')) {
                return;
            }

            try {
                await axios.delete(`/api/articulos/${id}`);
                alert('Artículo eliminado exitosamente');
                this.fetchArticulos(this.pagination.current_page);
            } catch (error) {
                console.error('Error al eliminar artículo:', error);
                alert('Error al eliminar el artículo');
            }
        },

        truncate(text, length) {
            if (!text) return '';
            return text.length > length ? text.substring(0, length) + '...' : text;
        },

        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
