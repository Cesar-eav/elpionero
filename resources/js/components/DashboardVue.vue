<template>
    <div class="dashboard-vue">
        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
            <p class="text-gray-500 text-lg">Cargando dashboard...</p>
        </div>

        <!-- Dashboard Content -->
        <div v-else>
            <!-- Stats Cards -->
            <div class="flex flex-row gap-6 mb-8">
                <!-- Artículos Card -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase">Artículos</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.articulos.total }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <a
                        href="/articulos-vue"
                        class="mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium inline-block"
                    >
                        Ver todos →
                    </a>
                </div>

                <!-- Editoriales Card -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase">Editoriales</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.editoriales.total }}</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                    </div>
                    <a
                        href="/editoriales-vue"
                        class="mt-4 text-orange-600 hover:text-orange-800 text-sm font-medium inline-block"
                    >
                        Ver todas →
                    </a>
                </div>

                <!-- Noticias Card -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase">Noticias</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.noticias.total }}</p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                    </div>
                    <a
                        href="/noticias-vue"
                        class="mt-4 text-red-600 hover:text-red-800 text-sm font-medium inline-block"
                    >
                        Ver todas →
                    </a>
                </div>

                <!-- Entrevistas Card -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase">Entrevistas</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.entrevistas.total }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <a
                        href="/entrevistas-vue"
                        class="mt-4 text-yellow-600 hover:text-yellow-800 text-sm font-medium inline-block"
                    >
                        Ver todas →
                    </a>
                </div>

                <!-- Revistas Card -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase">Revistas</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.revistas.total }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <a
                        href="/revistas-vue"
                        class="mt-4 text-green-600 hover:text-green-800 text-sm font-medium inline-block"
                    >
                        Ver todas →
                    </a>
                </div>

                <!-- Columnistas Card -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase">Columnistas</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.columnistas.total }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <a
                        href="/columnistas-vue"
                        class="mt-4 text-purple-600 hover:text-purple-800 text-sm font-medium inline-block"
                    >
                        Ver todos →
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a
                        href="/articulos-vue"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition"
                    >
                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700">Crear Artículo</span>
                    </a>

                    <a
                        href="/editoriales-vue"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:shadow-md transition"
                    >
                        <div class="bg-orange-100 rounded-full p-2 mr-3">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700">Crear Editorial</span>
                    </a>

                    <a
                        href="/noticias-vue"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-500 hover:shadow-md transition"
                    >
                        <div class="bg-red-100 rounded-full p-2 mr-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700">Crear Noticia</span>
                    </a>

                    <a
                        href="/entrevistas-vue"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-yellow-500 hover:shadow-md transition"
                    >
                        <div class="bg-yellow-100 rounded-full p-2 mr-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700">Crear Entrevista</span>
                    </a>

                    <a
                        href="/revistas-vue"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition"
                    >
                        <div class="bg-green-100 rounded-full p-2 mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700">Crear Revista</span>
                    </a>

                    <a
                        href="/columnistas-vue"
                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:shadow-md transition"
                    >
                        <div class="bg-purple-100 rounded-full p-2 mr-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-700">Crear Columnista</span>
                    </a>
                </div>
            </div>

            <!-- Recent Items Grid -->
            <div class="flex flex-row gap-6">
                <!-- Artículos Recientes -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Artículos Recientes</h3>
                    <div class="space-y-3">
                        <div
                            v-for="articulo in stats.articulos.recientes"
                            :key="articulo.id"
                            class="border-l-2 border-blue-500 pl-3 py-2 hover:bg-gray-50 transition cursor-pointer"
                            @click="goToArticulo(articulo.id)"
                        >
                            <div class="flex items-start gap-3">
                                <img
                                    v-if="articulo.columnista?.foto"
                                    :src="`/storage/${articulo.columnista.foto}`"
                                    :alt="articulo.columnista.nombre"
                                    class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                                />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800 hover:text-blue-600">
                                        {{ truncate(articulo.titulo, 40) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Por {{ articulo.columnista?.nombre || 'Desconocido' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div v-if="stats.articulos.recientes.length === 0" class="text-gray-500 text-sm">
                            No hay artículos recientes
                        </div>
                    </div>
                </div>

                <!-- Editoriales Recientes -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Editoriales Recientes</h3>
                    <div class="space-y-3">
                        <div
                            v-for="editorial in stats.editoriales.recientes"
                            :key="editorial.id"
                            class="border-l-2 border-orange-500 pl-3 py-2 hover:bg-gray-50 transition cursor-pointer"
                            @click="goToEditorial(editorial.id)"
                        >
                            <p class="text-sm font-medium text-gray-800 hover:text-orange-600">
                                {{ truncate(editorial.titulo, 40) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Revista: {{ editorial.revista?.titulo || 'Sin revista' }}
                            </p>
                        </div>
                        <div v-if="stats.editoriales.recientes.length === 0" class="text-gray-500 text-sm">
                            No hay editoriales recientes
                        </div>
                    </div>
                </div>

                <!-- Noticias Recientes -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Noticias Recientes</h3>
                    <div class="space-y-3">
                        <div
                            v-for="noticia in stats.noticias.recientes"
                            :key="noticia.id"
                            class="border-l-2 border-red-500 pl-3 py-2 hover:bg-gray-50 transition cursor-pointer"
                            @click="goToNoticia(noticia.id)"
                        >
                            <div class="flex items-start gap-3">
                                <img
                                    v-if="noticia.imagen"
                                    :src="`/storage/${noticia.imagen}`"
                                    :alt="noticia.titulo"
                                    class="w-10 h-10 rounded object-cover flex-shrink-0"
                                />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800 hover:text-red-600">
                                        {{ truncate(noticia.titulo, 40) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ formatDate(noticia.fecha_publicacion) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div v-if="stats.noticias.recientes.length === 0" class="text-gray-500 text-sm">
                            No hay noticias recientes
                        </div>
                    </div>
                </div>

                <!-- Entrevistas Recientes -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Entrevistas Recientes</h3>
                    <div class="space-y-3">
                        <div
                            v-for="entrevista in stats.entrevistas.recientes"
                            :key="entrevista.id"
                            class="border-l-2 border-yellow-500 pl-3 py-2 hover:bg-gray-50 transition cursor-pointer"
                            @click="goToEntrevista(entrevista.id)"
                        >
                            <div class="flex items-start gap-3">
                                <img
                                    v-if="entrevista.imagen"
                                    :src="`/storage/${entrevista.imagen}`"
                                    :alt="entrevista.titulo"
                                    class="w-10 h-10 rounded object-cover flex-shrink-0"
                                />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800 hover:text-yellow-600">
                                        {{ truncate(entrevista.titulo, 40) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Con {{ entrevista.entrevistado }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div v-if="stats.entrevistas.recientes.length === 0" class="text-gray-500 text-sm">
                            No hay entrevistas recientes
                        </div>
                    </div>
                </div>

                <!-- Revistas Recientes -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Revistas Recientes</h3>
                    <div class="space-y-3">
                        <div
                            v-for="revista in stats.revistas.recientes"
                            :key="revista.id"
                            class="border-l-2 border-green-500 pl-3 py-2 hover:bg-gray-50 transition cursor-pointer"
                            @click="goToRevista(revista.id)"
                        >
                            <p class="text-sm font-medium text-gray-800 hover:text-green-600">
                                {{ revista.titulo }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ revista.articulos_count || 0 }} artículos
                            </p>
                        </div>
                        <div v-if="stats.revistas.recientes.length === 0" class="text-gray-500 text-sm">
                            No hay revistas recientes
                        </div>
                    </div>
                </div>

                <!-- Columnistas Recientes -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Columnistas Recientes</h3>
                    <div class="space-y-3">
                        <div
                            v-for="columnista in stats.columnistas.recientes"
                            :key="columnista.id"
                            class="border-l-2 border-purple-500 pl-3 py-2 hover:bg-gray-50 transition cursor-pointer"
                            @click="goToColumnista(columnista.id)"
                        >
                            <div class="flex items-center gap-3">
                                <img
                                    v-if="columnista.foto"
                                    :src="`/storage/${columnista.foto}`"
                                    :alt="columnista.nombre"
                                    class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                                />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800 hover:text-purple-600">
                                        {{ columnista.nombre }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ columnista.email || 'Sin email' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div v-if="stats.columnistas.recientes.length === 0" class="text-gray-500 text-sm">
                            No hay columnistas recientes
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'DashboardVue',

    data() {
        return {
            loading: true,
            stats: {
                articulos: { total: 0, recientes: [] },
                editoriales: { total: 0, recientes: [] },
                noticias: { total: 0, recientes: [] },
                entrevistas: { total: 0, recientes: [] },
                revistas: { total: 0, recientes: [] },
                columnistas: { total: 0, recientes: [] }
            }
        };
    },

    mounted() {
        this.loadStats();
    },

    methods: {
        loadStats() {
            // 1. Iniciar carga
            this.loading = true;

            console.log("Datos");

            // 2. Llamada a la API usando .then() y .catch()
            axios.get('/api/stats')
                .then(response => {
                    // Éxito: Asignar los datos
                    this.stats = response.data;
                    console.log("Stats:", this.stats);
                })
                .catch(error => {
                    // Error: Manejar fallos
                    console.error('Error al cargar estadísticas:', error);
                })
                .finally(() => {
                    // Finalizar carga, independientemente del resultado
                    this.loading = false;
                });
        },
        truncate(text, length) {
            if (!text) return '';
            return text.length > length ? text.substring(0, length) + '...' : text;
        },
        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },
        goToArticulo(id) {
            window.location.href = `/articulos-vue?edit=${id}`;
        },
        goToEditorial(id) {
            window.location.href = `/editoriales-vue?edit=${id}`;
        },
        goToNoticia(id) {
            window.location.href = `/noticias-vue?edit=${id}`;
        },
        goToEntrevista(id) {
            window.location.href = `/entrevistas-vue?edit=${id}`;
        },
        goToRevista(id) {
            window.location.href = `/revistas-vue?edit=${id}`;
        },
        goToColumnista(id) {
            window.location.href = `/columnistas-vue?edit=${id}`;
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
