<template>
    <div class="columnistas-manager">
        <!-- Botón Crear Nuevo Columnista -->
        <div class="mb-4">
            <button
                @click="openCreateModal"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            >
                Crear Nuevo Columnista
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
            <p class="text-gray-500">Cargando columnistas...</p>
        </div>

        <!-- Lista de columnistas -->
        <ul v-else class="space-y-2">
            <li
                v-for="columnista in columnistas"
                :key="columnista.id"
                class="bg-gray-100 rounded-md p-4 flex justify-between items-center"
            >
                <div class="flex items-center gap-4">
                    <img
                        v-if="columnista.foto"
                        :src="`/${columnista.foto}`"
                        :alt="columnista.nombre"
                        width="120px"
                        height="120px"
                        class="w-12 h-12 rounded-full object-cover"
                    />
                    <div v-else class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600 text-xl">{{ columnista.nombre.charAt(0) }}</span>
                    </div>
                    <div>
                        <a
                            :href="`/columnistas/${columnista.id}`"
                            class="hover:underline text-lg font-semibold text-gray-800"
                        >
                            {{ columnista.nombre }}
                        </a>
                        <p class="text-sm text-gray-500">
                            {{ columnista.email || 'Sin email' }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button
                        @click="openEditModal(columnista)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm"
                    >
                        Editar
                    </button>
                    <button
                        @click="deleteColumnista(columnista.id)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm ml-2"
                    >
                        Eliminar
                    </button>
                </div>
            </li>
            <li v-if="columnistas.length === 0" class="text-gray-500">
                No hay columnistas creados aún.
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
        <ColumnistasForm
            :show="showForm"
            :columnista="selectedColumnista"
            @close="closeForm"
            @success="handleSuccess"
        />
    </div>
</template>

<script>
import axios from 'axios';
import ColumnistasForm from './ColumnistasForm.vue';

export default {
    name: 'ColumnistasManager',

    components: {
        ColumnistasForm
    },

    data() {
        return {
            columnistas: [],
            loading: false,
            showForm: false,
            selectedColumnista: null,
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
        this.fetchColumnistas();
    },

    methods: {
        async fetchColumnistas(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get('/api/columnistas', {
                    params: {
                        page: page,
                        per_page: 15
                    }
                });

                this.columnistas = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    from: response.data.from,
                    to: response.data.to,
                    total: response.data.total
                };
            } catch (error) {
                console.error('Error al cargar columnistas:', error);
                this.showSuccessMessage('Error al cargar los columnistas');
            } finally {
                this.loading = false;
            }
        },

        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.fetchColumnistas(page);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        openCreateModal() {
            this.selectedColumnista = null;
            this.showForm = true;
        },

        openEditModal(columnista) {
            this.selectedColumnista = columnista;
            this.showForm = true;
        },

        closeForm() {
            this.showForm = false;
            this.selectedColumnista = null;
        },

        async deleteColumnista(id) {
            if (!confirm('¿Estás seguro de eliminar este columnista?')) {
                return;
            }

            try {
                await axios.delete(`/api/columnistas/${id}`);
                this.showSuccessMessage('Columnista eliminado exitosamente');
                this.fetchColumnistas(this.pagination.current_page);
            } catch (error) {
                console.error('Error al eliminar columnista:', error);
                this.showSuccessMessage('Error al eliminar el columnista');
            }
        },

        handleSuccess() {
            this.showSuccessMessage('Columnista guardado exitosamente');
            this.fetchColumnistas(this.pagination.current_page);
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
