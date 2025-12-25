<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ pageTitle }} - El Pionero de Valparaíso
                </h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">
                        Bienvenido, {{ userName }}
                    </span>
                    <form method="POST" :action="logoutUrl">
                        <input type="hidden" name="_token" :value="csrfToken" />
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Navigation Menu -->
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-4 py-3">
                    <router-link
                        to="/dashboard-vue"
                        :class="[
                            'px-3 py-2 rounded-md text-sm font-medium',
                            $route.path === '/dashboard-vue'
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                        ]"
                    >
                        Inicio
                    </router-link>
                    <router-link
                        to="/dashboard-vue/articulos"
                        :class="[
                            'px-3 py-2 rounded-md text-sm font-medium',
                            $route.path === '/dashboard-vue/articulos'
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                        ]"
                    >
                        Artículos
                    </router-link>
                    <router-link
                        to="/dashboard-vue/editoriales"
                        :class="[
                            'px-3 py-2 rounded-md text-sm font-medium',
                            $route.path === '/dashboard-vue/editoriales'
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                        ]"
                    >
                        Editoriales
                    </router-link>
                    <router-link
                        to="/dashboard-vue/noticias"
                        :class="[
                            'px-3 py-2 rounded-md text-sm font-medium',
                            $route.path === '/dashboard-vue/noticias'
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                        ]"
                    >
                        Noticias
                    </router-link>
                    <router-link
                        to="/dashboard-vue/entrevistas"
                        :class="[
                            'px-3 py-2 rounded-md text-sm font-medium',
                            $route.path === '/dashboard-vue/entrevistas'
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                        ]"
                    >
                        Entrevistas
                    </router-link>
                    <router-link
                        to="/dashboard-vue/cable"
                        :class="[
                            'px-3 py-2 rounded-md text-sm font-medium',
                            $route.path === '/dashboard-vue/cable'
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                        ]"
                    >
                        Cable a Tierra
                    </router-link>
                    <router-link
                        to="/dashboard-vue/revistas"
                        :class="[
                            'px-3 py-2 rounded-md text-sm font-medium',
                            $route.path === '/dashboard-vue/revistas'
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                        ]"
                    >
                        Revistas
                    </router-link>
                    <router-link
                        to="/dashboard-vue/columnistas"
                        :class="[
                            'px-3 py-2 rounded-md text-sm font-medium',
                            $route.path === '/dashboard-vue/columnistas'
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                        ]"
                    >
                        Columnistas
                    </router-link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
export default {
    name: 'DashboardLayout',
    props: {
        userName: {
            type: String,
            required: true
        },
        csrfToken: {
            type: String,
            required: true
        },
        logoutUrl: {
            type: String,
            required: true
        }
    },
    computed: {
        pageTitle() {
            return this.$route.meta.title || 'Dashboard';
        }
    }
};
</script>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
