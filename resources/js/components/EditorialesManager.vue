<template>
    <div class="editoriales-manager">
        <!-- Barra de acciones -->
        <div class="mb-4 flex gap-4 items-center">
            <button
                @click="openCreateModal"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            >
                Crear Nueva Editorial
            </button>

            <!-- Buscador -->
            <div class="flex-1">
                <input
                    v-model="searchQuery"
                    @input="handleSearch"
                    type="text"
                    placeholder="Buscar editoriales por título..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
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
            <p class="text-gray-500">Cargando editoriales...</p>
        </div>

        <!-- Lista de editoriales -->
        <ul v-else class="space-y-2">
            <li
                v-for="editorial in editoriales"
                :key="editorial.id"
                class="bg-gray-100 rounded-md p-4 flex justify-between items-center"
            >
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-12 h-12 rounded-full bg-orange-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ editorial.titulo }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Revista: {{ editorial.revista?.titulo || 'Sin revista' }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button
                        @click="openEditModal(editorial)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm"
                    >
                        Editar
                    </button>
                    <button
                        @click="deleteEditorial(editorial.id)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm ml-2"
                    >
                        Eliminar
                    </button>
                </div>
            </li>
            <li v-if="editoriales.length === 0" class="text-gray-500">
                No hay editoriales creadas aún.
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
                        ? 'bg-orange-500 text-white border-orange-500'
                        : 'bg-white text-gray-700 hover:bg-gray-50'
                ]"
            >
                {{ page }}
            </button>
        </div>

        <!-- Formulario modal -->
        <EditorialesForm
            :show="showForm"
            :editorial="selectedEditorial"
            @close="closeForm"
            @success="handleSuccess"
        />
    </div>
</template>

<script>
import axios from 'axios';
import EditorialesForm from './EditorialesForm.vue';

export default {
    name: 'EditorialesManager',

    components: {
        EditorialesForm
    },

    data() {
        return {
            editoriales: [],
            loading: false,
            showForm: false,
            selectedEditorial: null,
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
        this.fetchEditoriales();
    },

    methods: {
        async fetchEditoriales(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get('/api/editoriales', {
                    params: {
                        page: page,
                        per_page: 15,
                        search: this.searchQuery
                    }
                });

                this.editoriales = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    from: response.data.from,
                    to: response.data.to,
                    total: response.data.total
                };
            } catch (error) {
                console.error('Error fetching editoriales:', error);
                alert('Error al cargar las editoriales');
            } finally {
                this.loading = false;
            }
        },

        handleSearch() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.searchTimeout = setTimeout(() => {
                this.fetchEditoriales(1);
            }, 500);
        },

        changePage(page) {
            this.fetchEditoriales(page);
        },

        openCreateModal() {
            this.selectedEditorial = null;
            this.showForm = true;
        },

        openEditModal(editorial) {
            this.selectedEditorial = editorial;
            this.showForm = true;
        },

        closeForm() {
            this.showForm = false;
            this.selectedEditorial = null;
        },

        handleSuccess(message) {
            this.successMessage = message;
            this.fetchEditoriales(this.pagination.current_page);
            this.closeForm();

            setTimeout(() => {
                this.successMessage = '';
            }, 3000);
        },

        async deleteEditorial(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta editorial?')) {
                return;
            }

            try {
                await axios.delete(`/api/editoriales/${id}`);
                this.successMessage = 'Editorial eliminada exitosamente';
                this.fetchEditoriales(this.pagination.current_page);

                setTimeout(() => {
                    this.successMessage = '';
                }, 3000);
            } catch (error) {
                console.error('Error deleting editorial:', error);
                alert('Error al eliminar la editorial');
            }
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
