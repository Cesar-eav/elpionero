<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold mb-4">Crear Categoría</h2>
      <form @submit.prevent="submitCategoria">
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">Nombre *</label>
          <input v-model="nombre" type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Museo" />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">Icono *</label>
          <input
            v-model="iconoSearch"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Buscar por nombre (ej: museo, playa, comida...)"
          />
          <select v-model="icono" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Selecciona un icono</option>
            <option v-for="icon in filteredIconos" :key="icon.emoji" :value="icon.emoji">{{ icon.emoji }} {{ icon.nombre }}</option>
          </select>
        </div>
        <div class="flex gap-4 justify-end">
          <button type="button" @click="$emit('close')" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100">Cancelar</button>
          <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded-lg">Crear</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CategoriaForm',
  data() {
    return {
      nombre: '',
      icono: '',
      iconoSearch: '',
      iconos: [
        { emoji: '🏛️', nombre: 'museo' },
        { emoji: '🏞️', nombre: 'parque' },
        { emoji: '🏖️', nombre: 'playa' },
        { emoji: '🍽️', nombre: 'comida' },
        { emoji: '🗿', nombre: 'monumento' },
        { emoji: '🏟️', nombre: 'estadio' },
        { emoji: '🎭', nombre: 'teatro' },
        { emoji: '🛒', nombre: 'shopping' },
        { emoji: '🏨', nombre: 'hotel' },
        { emoji: '🏰', nombre: 'castillo' },
        { emoji: '🖼️', nombre: 'galería' },
        { emoji: '🎡', nombre: 'feria' },
        { emoji: '🕌', nombre: 'mezquita' },
        { emoji: '⛩️', nombre: 'templo' },
        { emoji: '⛲', nombre: 'fuente' },
        { emoji: '🗽', nombre: 'estatua' },
        { emoji: '🗻', nombre: 'montaña' },
        { emoji: '🧗', nombre: 'aventura' },
        { emoji: '👁️', nombre: 'mirador' },
        { emoji: '✨', nombre: 'otros' },
        
      ],
    };
  },
  computed: {
    filteredIconos() {
      if (!this.iconoSearch) return this.iconos;
      return this.iconos.filter(icon =>
        icon.nombre.toLowerCase().includes(this.iconoSearch.toLowerCase())
      );
    },
  },
  methods: {
    async submitCategoria() {
      try {
        await fetch('/api/categorias', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ nombre: this.nombre, icono: this.icono }),
        });
        this.$emit('created');
        this.$emit('close');
      } catch (e) {
        alert('Error al crear la categoría');
      }
    },
  },
};
</script>
