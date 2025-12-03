<template>
  <div class="p-4 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">{{ title }}</h2>
    <p class="mb-4">Contador: {{ counter }}</p>
    <button
      @click="incrementar"
      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
    >
      Incrementar
    </button>
  </div>
</template>

<script>
export default {
  name: 'ExampleComponent',

  // Options API
  data() {
    return {
      title: 'Vue 3 con Options API',
      counter: 0
    }
  },

  methods: {
    incrementar() {
      this.counter++
    }
  },

  mounted() {
    console.log('Componente Vue montado correctamente!')
  }
}
</script>

<style scoped>
/* Estilos específicos del componente */
</style>
