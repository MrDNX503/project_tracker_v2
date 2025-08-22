<template>
  <div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Proyectos</h2>
      
      <!-- Botón de Filtrado y Menú Desplegable -->
      <div class="relative">
        <button @click="toggleFilterDropdown" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2">
          Filtrar por Categoría
          <svg class="h-4 w-4" :class="{'transform rotate-180': isFilterDropdownOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        
        <div v-if="isFilterDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
          <button @click="setFilter('all')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg">
            Todas
          </button>
          <button @click="setFilter('desarrollo')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg">
            Desarrollo
          </button>
          <button @click="setFilter('diseño')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg">
            Diseño
          </button>
          <button @click="setFilter('marketing')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg">
            Marketing
          </button>
          <button @click="setFilter('investigacion')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg">
            Investigación
          </button>
        </div>
      </div>
    </div>

    <div v-if="projects.length === 0" class="text-center text-gray-500">
      No hay proyectos.
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="project in filteredProjects" :key="project.id" class="p-6 bg-white shadow-lg rounded-lg border border-gray-200">
        <h3 class="text-xl font-bold text-blue-600 mb-2">{{ project.name }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ project.description }}</p>
        <div class="flex justify-between items-center text-xs text-gray-500 mt-4">
          <span>Categoría: <span class="font-semibold capitalize text-gray-700">{{ project.category }}</span></span>
          <span>Creado el: {{ project.start_date }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const projects = ref([]);
const currentFilter = ref('all');
const isFilterDropdownOpen = ref(false);

const setFilter = (filterValue) => {
  currentFilter.value = filterValue;
  isFilterDropdownOpen.value = false;
};

const toggleFilterDropdown = () => {
  isFilterDropdownOpen.value = !isFilterDropdownOpen.value;
};

const filteredProjects = computed(() => {
  if (currentFilter.value === 'all') {
    return projects.value;
  }
  return projects.value.filter(p => p.category === currentFilter.value);
});

onMounted(async () => {
  try {
    const res = await axios.get('http://localhost:8000/api/test-projects');
    projects.value = res.data;
  } catch (err) {
    console.error(err);
  }
});
</script>
