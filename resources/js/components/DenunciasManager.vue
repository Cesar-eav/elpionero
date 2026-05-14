<template>
    <div class="denuncias-manager">
        <!-- Modal de confirmación interno -->
        <div v-if="confirmacion.visible" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4">
                <p class="text-gray-800 font-medium mb-5">{{ confirmacion.mensaje }}</p>
                <div class="flex gap-3 justify-end">
                    <button
                        @click="confirmarAccion(false)"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md text-sm"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="confirmarAccion(true)"
                        :class="confirmacion.peligro ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
                        class="px-4 py-2 text-white rounded-md text-sm font-medium"
                    >
                        {{ confirmacion.botonOk }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Aviso 24 horas -->
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-amber-800 text-sm">
                El tiempo máximo de aprobación de cada denuncia es de <strong>24 horas</strong> desde su recepción.
            </p>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="mb-4 flex flex-wrap gap-3 items-center">
            <div class="flex gap-2">
                <button
                    @click="filtroEstado = ''; loadDenuncias(1)"
                    :class="filtroEstado === '' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border border-gray-300'"
                    class="px-4 py-2 rounded-md text-sm font-medium"
                >
                    Todas
                </button>
                <button
                    @click="filtroEstado = 'pendiente'; loadDenuncias(1)"
                    :class="filtroEstado === 'pendiente' ? 'bg-amber-500 text-white' : 'bg-white text-amber-600 border border-amber-300'"
                    class="px-4 py-2 rounded-md text-sm font-medium"
                >
                    Pendientes
                </button>
                <button
                    @click="filtroEstado = 'aprobada'; loadDenuncias(1)"
                    :class="filtroEstado === 'aprobada' ? 'bg-green-600 text-white' : 'bg-white text-green-700 border border-green-300'"
                    class="px-4 py-2 rounded-md text-sm font-medium"
                >
                    Aprobadas
                </button>
            </div>

            <div class="flex-1 min-w-48">
                <input
                    v-model="searchQuery"
                    @input="handleSearch"
                    type="text"
                    placeholder="Buscar por descripción, ubicación o nombre..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                />
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-12">
            <p class="text-gray-500">Cargando denuncias...</p>
        </div>

        <!-- Lista -->
        <div v-else>
            <div v-if="denuncias.length === 0" class="text-center py-12 bg-white rounded-lg">
                <p class="text-gray-500">No se encontraron denuncias</p>
            </div>

            <div v-else class="space-y-4">
                <div
                    v-for="denuncia in denuncias"
                    :key="denuncia.id"
                    class="bg-white rounded-lg shadow-sm border overflow-hidden"
                    :class="denuncia.estado === 'pendiente' ? 'border-amber-200' : 'border-green-200'"
                >
                    <div class="p-4">
                        <!-- Cabecera -->
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="denuncia.estado === 'pendiente'
                                        ? 'bg-amber-100 text-amber-800'
                                        : 'bg-green-100 text-green-800'"
                                >
                                    {{ denuncia.estado === 'pendiente' ? 'Pendiente' : 'Aprobada' }}
                                </span>
                                <span class="text-xs text-gray-400">#{{ denuncia.id }}</span>
                                <span class="text-xs text-gray-400">{{ formatDate(denuncia.created_at) }}</span>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button
                                    v-if="denuncia.estado === 'pendiente'"
                                    @click="aprobar(denuncia)"
                                    :disabled="procesando === denuncia.id"
                                    class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1.5 rounded-md disabled:opacity-50"
                                >
                                    {{ procesando === denuncia.id ? '...' : 'Aprobar' }}
                                </button>
                                <button
                                    v-if="denuncia.estado === 'pendiente'"
                                    @click="rechazar(denuncia)"
                                    :disabled="procesando === denuncia.id"
                                    class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1.5 rounded-md disabled:opacity-50"
                                >
                                    Rechazar
                                </button>
                                <button
                                    v-if="denuncia.estado === 'aprobada'"
                                    @click="eliminar(denuncia)"
                                    class="text-red-600 hover:text-red-800 text-sm"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>

                        <!-- Información -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Ubicación</p>
                                <p class="text-gray-800 text-sm">{{ denuncia.ubicacion }}</p>
                            </div>
                            <div v-if="denuncia.nombre">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Denunciante</p>
                                <p class="text-gray-800 text-sm">{{ denuncia.nombre }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Descripción</p>
                            <p class="text-gray-800 text-sm leading-relaxed">{{ denuncia.descripcion }}</p>
                        </div>

                        <!-- Imágenes -->
                        <div
                            v-if="denuncia.imagen1"
                            class="grid gap-2"
                            :class="imageCount(denuncia) === 1 ? 'grid-cols-1' : (imageCount(denuncia) === 2 ? 'grid-cols-2' : 'grid-cols-3')"
                        >
                            <a
                                v-for="img in getImages(denuncia)"
                                :key="img"
                                :href="`/storage/${img}`"
                                target="_blank"
                                class="block"
                            >
                                <img
                                    :src="`/storage/${img}`"
                                    :alt="'Imagen denuncia ' + denuncia.id"
                                    class="w-full h-40 object-cover rounded-lg border hover:opacity-90 transition-opacity cursor-pointer"
                                />
                            </a>
                        </div>

                        <!-- Fecha aprobación -->
                        <div v-if="denuncia.approved_at" class="mt-3 text-xs text-gray-400">
                            Aprobada el {{ formatDate(denuncia.approved_at) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            <div v-if="pagination.last_page > 1" class="mt-6 flex justify-center gap-2">
                <button
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-3 py-1.5 border border-gray-300 rounded text-sm disabled:opacity-40"
                >
                    Anterior
                </button>
                <span class="px-3 py-1.5 text-sm text-gray-600">
                    Página {{ pagination.current_page }} de {{ pagination.last_page }}
                </span>
                <button
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-3 py-1.5 border border-gray-300 rounded text-sm disabled:opacity-40"
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
    name: 'DenunciasManager',
    data() {
        return {
            denuncias: [],
            loading: true,
            procesando: null,
            filtroEstado: 'pendiente',
            searchQuery: '',
            searchTimeout: null,
            pagination: {
                current_page: 1,
                last_page: 1,
                per_page: 10,
                total: 0
            },
            confirmacion: {
                visible: false,
                mensaje: '',
                botonOk: 'Confirmar',
                peligro: false,
                resolve: null
            }
        };
    },
    mounted() {
        this.loadDenuncias();
    },
    methods: {
        pedir(mensaje, botonOk = 'Confirmar', peligro = false) {
            return new Promise(resolve => {
                this.confirmacion = { visible: true, mensaje, botonOk, peligro, resolve };
            });
        },
        confirmarAccion(valor) {
            this.confirmacion.visible = false;
            if (this.confirmacion.resolve) {
                this.confirmacion.resolve(valor);
            }
        },
        loadDenuncias(page = 1) {
            this.loading = true;

            const params = { page, per_page: this.pagination.per_page };

            if (this.filtroEstado !== '') {
                params.estado = this.filtroEstado;
            }
            if (this.searchQuery) {
                params.search = this.searchQuery;
            }

            axios.get('/api/denuncias', { params })
                .then(response => {
                    this.denuncias = response.data.data;
                    this.pagination = {
                        current_page: response.data.current_page,
                        last_page: response.data.last_page,
                        per_page: response.data.per_page,
                        total: response.data.total
                    };
                })
                .catch(() => this.pedir('Error al cargar las denuncias', 'OK'))
                .finally(() => { this.loading = false; });
        },
        handleSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => this.loadDenuncias(1), 300);
        },
        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.loadDenuncias(page);
            }
        },
        async aprobar(denuncia) {
            const ok = await this.pedir('¿Aprobar esta denuncia y publicarla en el sitio?', 'Aprobar', false);
            if (!ok) return;

            this.procesando = denuncia.id;
            axios.post(`/api/denuncias/${denuncia.id}/aprobar`)
                .then(() => {
                    this.loadDenuncias(this.pagination.current_page);
                })
                .catch(error => {
                    console.error('[Aprobar] error:', error.response?.status, error.response?.data);
                    this.pedir('Error al aprobar la denuncia. Revisa la consola.', 'OK');
                })
                .finally(() => { this.procesando = null; });
        },
        async rechazar(denuncia) {
            const ok = await this.pedir('¿Rechazar y eliminar esta denuncia? Esta acción no se puede deshacer.', 'Rechazar', true);
            if (!ok) return;

            this.procesando = denuncia.id;
            axios.post(`/api/denuncias/${denuncia.id}/rechazar`)
                .then(() => this.loadDenuncias(this.pagination.current_page))
                .catch(() => this.pedir('Error al rechazar la denuncia.', 'OK'))
                .finally(() => { this.procesando = null; });
        },
        async eliminar(denuncia) {
            const ok = await this.pedir('¿Eliminar esta denuncia permanentemente?', 'Eliminar', true);
            if (!ok) return;

            axios.delete(`/api/denuncias/${denuncia.id}`)
                .then(() => this.loadDenuncias(this.pagination.current_page))
                .catch(() => this.pedir('Error al eliminar la denuncia.', 'OK'));
        },
        getImages(denuncia) {
            return [denuncia.imagen1, denuncia.imagen2, denuncia.imagen3].filter(Boolean);
        },
        imageCount(denuncia) {
            return this.getImages(denuncia).length;
        },
        formatDate(dateStr) {
            if (!dateStr) return '';
            return new Date(dateStr).toLocaleString('es-ES', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
};
</script>
