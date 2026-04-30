<template>
    <div>
        <!-- Volver -->
        <div class="mb-4">
            <router-link
                to="/dashboard-vue/columnistas"
                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al listado
            </router-link>
        </div>

        <div v-if="loading" class="text-center py-16 text-gray-400">Cargando...</div>

        <div v-else-if="columnista" class="space-y-6">

            <!-- Tarjeta perfil -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-[#fc5648] via-[#eba81d] to-[#fc5648]"></div>
                <div class="p-6 flex flex-col sm:flex-row gap-6">

                    <!-- Foto -->
                    <div class="shrink-0 flex flex-col items-center gap-3">
                        <img
                            v-if="columnista.foto"
                            :src="`/storage/${columnista.foto}`"
                            :alt="columnista.nombre"
                            class="w-32 h-32 rounded-full object-cover ring-4 ring-gray-100 shadow"
                        />
                        <div
                            v-else
                            class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-4xl text-gray-400 shadow"
                        >
                            {{ columnista.nombre.charAt(0) }}
                        </div>
                        <button
                            @click="openEdit"
                            class="text-xs px-3 py-1.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-semibold transition"
                        >
                            Editar
                        </button>
                    </div>

                    <!-- Datos -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h2 class="text-2xl font-bold text-gray-900">{{ columnista.nombre }}</h2>
                            <span
                                v-if="columnista.participa_proximo_numero"
                                class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-semibold"
                            >
                                Próximo número
                            </span>
                        </div>

                        <p v-if="columnista.email" class="text-sm text-gray-500 mb-4 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ columnista.email }}
                        </p>

                        <p v-if="columnista.bio" class="text-sm text-gray-700 leading-relaxed">{{ columnista.bio }}</p>
                        <p v-else class="text-sm text-gray-400 italic">Sin biografía registrada.</p>
                    </div>
                </div>
            </div>

            <!-- Artículos -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-800 border-b pb-2 mb-4">
                    Artículos publicados
                    <span class="ml-2 text-sm font-normal text-gray-400">({{ articulos.length }})</span>
                </h3>

                <div v-if="loadingArticulos" class="text-sm text-gray-400 py-4 text-center">Cargando artículos...</div>

                <ul v-else-if="articulos.length" class="divide-y divide-gray-100">
                    <li v-for="a in articulos" :key="a.id" class="py-3 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ a.titulo }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ a.revista?.titulo ?? '—' }}
                                <span v-if="a.created_at"> · {{ formatDate(a.created_at) }}</span>
                            </p>
                        </div>
                        <a
                            :href="`/articulo/${a.slug}`"
                            target="_blank"
                            class="shrink-0 text-xs text-[#fc5648] hover:underline font-semibold"
                        >
                            Ver →
                        </a>
                    </li>
                </ul>

                <p v-else class="text-sm text-gray-400 italic">No tiene artículos publicados.</p>
            </div>
        </div>

        <!-- Modal edición -->
        <ColumnistasForm
            :show="showForm"
            :columnista="columnista"
            @close="showForm = false"
            @success="handleUpdated"
        />
    </div>
</template>

<script>
import axios from 'axios';
import ColumnistasForm from './ColumnistasForm.vue';

export default {
    name: 'ColumnistasShow',
    components: { ColumnistasForm },

    data() {
        return {
            columnista: null,
            articulos: [],
            loading: true,
            loadingArticulos: false,
            showForm: false,
        };
    },

    mounted() {
        this.fetchColumnista();
    },

    methods: {
        async fetchColumnista() {
            this.loading = true;
            try {
                const id = this.$route.params.id;
                const res = await axios.get(`/api/columnistas/${id}`);
                this.columnista = res.data;
                this.fetchArticulos();
            } catch {
                this.columnista = null;
            } finally {
                this.loading = false;
            }
        },

        async fetchArticulos() {
            this.loadingArticulos = true;
            try {
                const res = await axios.get('/api/articulos', {
                    params: { columnista_id: this.columnista.id, per_page: 100 }
                });
                this.articulos = res.data.data ?? res.data;
            } catch {
                this.articulos = [];
            } finally {
                this.loadingArticulos = false;
            }
        },

        openEdit() {
            this.showForm = true;
        },

        async handleUpdated() {
            this.showForm = false;
            await this.fetchColumnista();
        },

        formatDate(dateStr) {
            return new Date(dateStr).toLocaleDateString('es-CL');
        }
    }
};
</script>
