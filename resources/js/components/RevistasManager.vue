<template>
    <div class="revistas-manager">
        <!-- Barra de acciones -->
        <div class="mb-4 flex gap-4 items-center">
            <button
                @click="openCreateModal"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            >
                Crear Nueva Revista
            </button>

            <!-- Buscador -->
            <div class="flex-1">
                <input
                    v-model="searchQuery"
                    @input="handleSearch"
                    type="text"
                    placeholder="Buscar revistas por título..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                />
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
            <p class="text-gray-500">Cargando revistas...</p>
        </div>

        <!-- Lista de revistas -->
        <ul v-else class="space-y-2">
            <li
                v-for="revista in revistas"
                :key="revista.id"
                class="bg-gray-100 rounded-md p-4 flex justify-between items-center"
            >
                <div>
                    <a
                        :href="`/revistas/${revista.id}`"
                        class="hover:underline text-lg font-semibold text-gray-800"
                    >
                        {{ revista.titulo }}
                    </a>
                    <p class="text-sm text-gray-500">
                        Fecha: {{ formatDate(revista.fecha_publicacion) }} |
                        Artículos: {{ revista.articulos_count || 0 }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <button
                        @click="openEditModal(revista)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm"
                    >
                        Editar
                    </button>
                    <button
                        @click="deleteRevista(revista.id)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm ml-2"
                    >
                        Eliminar
                    </button>
                </div>
            </li>
            <li v-if="revistas.length === 0" class="text-gray-500">
                No hay revistas creadas aún.
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
        <RevistasForm
            :show="showForm"
            :revista="selectedRevista"
            @close="closeForm"
            @success="handleSuccess"
        />
    </div>
</template>

<script>
import axios from 'axios';
import RevistasForm from './RevistasForm.vue';

export default {
    name: 'RevistasManager',

    components: {
        RevistasForm
    },

    data() {
        return {
            revistas: [],
            loading: false,
            showForm: false,
            selectedRevista: null,
            successMessage: '',
            searchQuery: '',
            searchTimeout: null,
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
        this.fetchRevistas();
        this.checkEditParam();
    },

    methods: {
        async fetchRevistas(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get('/api/revistas', {
                    params: {
                        page: page,
                        per_page: 15,
                        search: this.searchQuery
                    }
                });

                this.revistas = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    from: response.data.from,
                    to: response.data.to,
                    total: response.data.total
                };
            } catch (error) {
                console.error('Error al cargar revistas:', error);
                this.showSuccessMessage('Error al cargar las revistas');
            } finally {
                this.loading = false;
            }
        },

        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.fetchRevistas(page);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        openCreateModal() {
            this.selectedRevista = null;
            this.showForm = true;
        },

        openEditModal(revista) {
            this.selectedRevista = revista;
            this.showForm = true;
        },

        closeForm() {
            this.showForm = false;
            this.selectedRevista = null;
        },

        async deleteRevista(id) {
            if (!confirm('¿Estás seguro de eliminar esta revista?')) {
                return;
            }

            try {
                await axios.delete(`/api/revistas/${id}`);
                this.showSuccessMessage('Revista eliminada exitosamente');
                this.fetchRevistas(this.pagination.current_page);
            } catch (error) {
                console.error('Error al eliminar revista:', error);
                this.showSuccessMessage('Error al eliminar la revista');
            }
        },

        handleSuccess() {
            this.showSuccessMessage('Revista guardada exitosamente');
            this.fetchRevistas(this.pagination.current_page);
        },

        showSuccessMessage(message) {
            this.successMessage = message;
            setTimeout(() => {
                this.successMessage = '';
            }, 3000);
        },

        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },

        async checkEditParam() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');

            if (editId) {
                try {
                    const response = await axios.get(`/api/revistas/${editId}`);
                    this.openEditModal(response.data);
                    // Limpiar el parámetro de la URL
                    window.history.replaceState({}, '', '/revistas-vue');
                } catch (error) {
                    console.error('Error al cargar revista para editar:', error);
                }
            }
        },

        handleSearch() {
            // Cancelar búsqueda anterior si existe
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            // Esperar 500ms después de que el usuario deje de escribir
            this.searchTimeout = setTimeout(() => {
                this.fetchRevistas(1); // Buscar desde la página 1
            }, 500);
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
