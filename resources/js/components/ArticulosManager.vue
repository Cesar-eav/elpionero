<template>
    <div class="articulos-manager">
        <!-- Botón Crear Nuevo Artículo -->
        <div class="mb-4">
            <button
                @click="openCreateModal"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            >
                Crear Nuevo Artículo
            </button>
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
            <p class="text-gray-500">Cargando artículos...</p>
        </div>

        <!-- Lista de artículos -->
        <ul v-else class="space-y-2">
            <li
                v-for="articulo in articulos"
                :key="articulo.id"
                class="bg-gray-100 rounded-md p-4 flex justify-between items-center"
            >
                <div>
                    <a
                        :href="`/articulos/${articulo.id}`"
                        class="hover:underline text-lg font-semibold text-gray-800"
                    >
                        {{ articulo.titulo }}
                    </a>
                    <p class="text-sm text-gray-500">
                        Autor: {{ articulo.columnista?.nombre || 'Anónimo' }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <button
                        @click="openEditModal(articulo)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm"
                    >
                        Editar
                    </button>
                    <button
                        @click="deleteArticulo(articulo.id)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm ml-2"
                    >
                        Eliminar
                    </button>
                </div>
            </li>
            <li v-if="articulos.length === 0" class="text-gray-500">
                No hay artículos creados aún.
            </li>
        </ul>

        <!-- Paginación -->
        <div v-if="pagination.last_page > 1" class="mt-4 flex justify-center gap-2">
            <button
                v-for="page in paginationPages"
                :key="page"
                @click="changePage(page)"
                :class="[
                    'px-4 py-2 border rounded',
                    page === pagination.current_page
                        ? 'bg-blue-500 text-white border-blue-500'
                        : 'bg-white text-gray-700 hover:bg-gray-50'
                ]"
            >
                {{ page }}
            </button>
        </div>

        <!-- Formulario modal -->
        <ArticulosForm
            :show="showForm"
            :articulo="selectedArticulo"
            @close="closeForm"
            @success="handleSuccess"
        />
    </div>
</template>

<script>
import axios from 'axios';
import ArticulosForm from './ArticulosForm.vue';

export default {
    name: 'ArticulosManager',

    components: {
        ArticulosForm
    },

    data() {
        return {
            articulos: [],
            loading: false,
            showForm: false,
            selectedArticulo: null,
            successMessage: '',
            pagination: {
                current_page: 1,
                last_page: 1,
                from: 0,
                to: 0,
                total: 0
            }
        };
    },

    computed: {
        paginationPages() {
            const pages = [];
            const maxPages = 5;
            let startPage = Math.max(1, this.pagination.current_page - Math.floor(maxPages / 2));
            let endPage = Math.min(this.pagination.last_page, startPage + maxPages - 1);

            if (endPage - startPage < maxPages - 1) {
                startPage = Math.max(1, endPage - maxPages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                pages.push(i);
            }
            return pages;
        }
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
                        per_page: 15
                    }
                });

                this.articulos = response.data.data;
                console.log(response.data);
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    from: response.data.from,
                    to: response.data.to,
                    total: response.data.total
                };
            } catch (error) {
                console.error('Error al cargar artículos:', error);
                this.showSuccessMessage('Error al cargar los artículos');
            } finally {
                this.loading = false;
            }
        },

        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.fetchArticulos(page);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        openCreateModal() {
            this.selectedArticulo = null;
            this.showForm = true;
        },

        openEditModal(articulo) {
            this.selectedArticulo = articulo;
            this.showForm = true;
        },

        closeForm() {
            this.showForm = false;
            this.selectedArticulo = null;
        },

        async deleteArticulo(id) {
            if (!confirm('¿Estás seguro de eliminar este artículo?')) {
                return;
            }

            try {
                await axios.delete(`/api/articulos/${id}`);
                this.showSuccessMessage('Artículo eliminado exitosamente');
                this.fetchArticulos(this.pagination.current_page);
            } catch (error) {
                console.error('Error al eliminar artículo:', error);
                this.showSuccessMessage('Error al eliminar el artículo');
            }
        },

        handleSuccess() {
            this.showSuccessMessage('Artículo guardado exitosamente');
            this.fetchArticulos(this.pagination.current_page);
        },

        showSuccessMessage(message) {
            this.successMessage = message;
            setTimeout(() => {
                this.successMessage = '';
            }, 3000);
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
