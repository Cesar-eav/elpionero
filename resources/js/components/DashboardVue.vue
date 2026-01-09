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
                    <router-link
                        to="/dashboard-vue/articulos"
                        class="mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium inline-block"
                    >
                        Ver todos →
                    </router-link>
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
                    <router-link
                        to="/dashboard-vue/editoriales"
                        class="mt-4 text-orange-600 hover:text-orange-800 text-sm font-medium inline-block"
                    >
                        Ver todas →
                    </router-link>
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
                    <router-link
                        to="/dashboard-vue/noticias"
                        class="mt-4 text-red-600 hover:text-red-800 text-sm font-medium inline-block"
                    >
                        Ver todas →
                    </router-link>
                </div>
                </div> 
                <div class="flex flex-row gap-6 mb-8">

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
                    <router-link
                        to="/dashboard-vue/entrevistas"
                        class="mt-4 text-yellow-600 hover:text-yellow-800 text-sm font-medium inline-block"
                    >
                        Ver todas →
                    </router-link>
                </div>

                <!-- Cable a Tierra Card -->
                <div class="flex-1 bg-white rounded-lg shadow-md p-6 border-l-4 border-teal-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase">Cable a Tierra</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.cable_a_tierra ? stats.cable_a_tierra.total : 0 }}</p>
                        </div>
                        <div class="bg-teal-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <router-link
                        to="/dashboard-vue/cable"
                        class="mt-4 text-teal-600 hover:text-teal-800 text-sm font-medium inline-block"
                    >
                        Ver todos →
                    </router-link>
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
                    <router-link
                        to="/dashboard-vue/revistas"
                        class="mt-4 text-green-600 hover:text-green-800 text-sm font-medium inline-block"
                    >
                        Ver todas →
                    </router-link>
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
                    <router-link
                        to="/dashboard-vue/columnistas"
                        class="mt-4 text-purple-600 hover:text-purple-800 text-sm font-medium inline-block"
                    >
                        Ver todos →
                    </router-link>
                </div>
            </div>

            <!-- PDF Stats Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Estadísticas de PDFs
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Total -->
                    <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-4 border border-pink-200">
                        <p class="text-sm font-semibold text-pink-600 uppercase">Total</p>
                        <p class="text-3xl font-bold text-pink-700">{{ pdfStats.total || 0 }}</p>
                    </div>

                    <!-- Descargas -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <p class="text-sm font-semibold text-blue-600 uppercase">Descargas</p>
                        <p class="text-3xl font-bold text-blue-700">{{ pdfStats.downloads || 0 }}</p>
                    </div>

                    <!-- Visualizaciones -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <p class="text-sm font-semibold text-green-600 uppercase">Vistas en línea</p>
                        <p class="text-3xl font-bold text-green-700">{{ pdfStats.views || 0 }}</p>
                    </div>
                </div>




                <!-- Por PDF -->
                <div v-if="pdfStats.by_pdf && Object.keys(pdfStats.by_pdf).length > 0" class="flex gap-5 mb-6">
                    <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Por PDF</h3>
                    <div class="space-y-2">
                        <div v-for="(actions, pdfName) in pdfStats.by_pdf" :key="pdfName"
                             class="bg-gray-50 rounded p-3 border border-gray-200">
                            <p class="font-semibold text-gray-800 mb-2">{{ pdfName }}</p>
                            <div class="flex gap-4 text-sm">
                                <span class="text-blue-600">
                                    Descargas: <strong>{{ getActionCount(actions, 'download') }}</strong>
                                </span>
                                <span class="text-green-600">
                                    Vistas: <strong>{{ getActionCount(actions, 'view') }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    </div>

<div>

            <!-- Por Mes-->
                <div v-if="pdfStats.by_month && Object.keys(pdfStats.by_month).length > 0" class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Por Mes</h3>
                    <div class="space-y-2">
                        <div v-for="(actions, month) in pdfStats.by_month" :key="month"
                             class="bg-gray-50 rounded p-3 border border-gray-200">
                            <p class="font-semibold text-gray-800 mb-2">{{ month }}</p>
                            <div class="flex gap-4 text-sm">
                                <span class="text-blue-600">
                                    Descargas: <strong>{{ getActionCount(actions, 'download') }}</strong>
                                </span>
                                <span class="text-green-600">
                                    Vistas: <strong>{{ getActionCount(actions, 'view') }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
</div>



                </div>

                <!-- Actividad Reciente -->
                <div v-if="pdfStats.recent && pdfStats.recent.length > 0">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Actividad Reciente</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">PDF</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="activity in pdfStats.recent" :key="activity.id">
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ activity.pdf_name }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <span :class="activity.action === 'download' ? 'text-blue-600 font-semibold' : 'text-green-600 font-semibold'">
                                            {{ activity.action === 'download' ? 'Descarga' : 'Vista' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ activity.ip_address }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ formatDateTime(activity.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
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
            },
            pdfStats: {
                total: 0,
                downloads: 0,
                views: 0,
                by_pdf: {},
                recent: [],
                by_day: {}
            }
        };
    },

    mounted() {
        this.loadStats();
        this.loadPdfStats();
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
        loadPdfStats() {
            axios.get('/api/pdf-stats')
                .then(response => {
                    this.pdfStats = response.data;
                    console.log("PDF Stats:", this.pdfStats);
                })
                .catch(error => {
                    console.error('Error al cargar estadísticas de PDFs:', error);
                });
        },
        getActionCount(actions, actionType) {
            const action = actions.find(a => a.action === actionType);
            return action ? action.count : 0;
        },
        formatDateTime(date) {
            if (!date) return '';
            return new Date(date).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
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
            this.$router.push(`/dashboard-vue/articulos?edit=${id}`);
        },
        goToEditorial(id) {
            this.$router.push(`/dashboard-vue/editoriales?edit=${id}`);
        },
        goToNoticia(id) {
            this.$router.push(`/dashboard-vue/noticias?edit=${id}`);
        },
        goToEntrevista(id) {
            this.$router.push(`/dashboard-vue/entrevistas?edit=${id}`);
        },
        goToRevista(id) {
            this.$router.push(`/dashboard-vue/revistas?edit=${id}`);
        },
        goToColumnista(id) {
            this.$router.push(`/dashboard-vue/columnistas?edit=${id}`);
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
