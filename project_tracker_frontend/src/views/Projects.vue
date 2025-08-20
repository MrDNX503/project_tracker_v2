<template>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Proyectos</h2>

    <div v-if="projects.length === 0">No hay proyectos.</div>

    <div v-else class="row g-3">
      <div v-for="project in filteredProjects" :key="project.id" class="col-6">
        <div class="p-4 bg-white shadow rounded">
          <h3 class="font-bold">{{ project.name }}</h3>
          <p>{{ project.description }}</p>
          <div class="progress">
            <div class="progress-bar" role="progressbar" :style="{ width: project.progress + '%' }">{{ project.progress }}%</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

const projects = ref([]);
const route = useRoute();

const filteredProjects = computed(() => {
  const filter = route.query.filter;
  if (!filter) return projects.value;
  if (filter === 'completed') return projects.value.filter(p => p.status === 'completed');
  if (filter === 'priority') return projects.value.filter(p => p.priority); // si tienes atributo priority
  if (filter === 'procrastination') return projects.value.filter(p => p.procrastination_count > 0); // ejemplo
  return projects.value;
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
