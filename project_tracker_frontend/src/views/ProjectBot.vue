<template>
  <div class="fixed bottom-4 right-4 w-64 p-4 bg-white shadow-lg rounded-lg animate-bounce">
    <div class="font-bold text-blue-600 mb-2">🤖 Robot Asistente</div>
    <div v-if="!asked">
      <p>Hola! ¿Qué proyecto te gustaría crear hoy?</p>
      <button @click="asked=true" class="btn btn-primary mt-2">Responder</button>
    </div>
    <div v-else>
      <input v-model="projectName" type="text" placeholder="Nombre del proyecto" class="form-control mb-2">
      <button @click="submitProject" class="btn btn-success w-full">Crear Proyecto</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const asked = ref(false);
const projectName = ref('');
const router = useRouter();

const submitProject = async () => {
  if (!projectName.value) return;
  try {
    await axios.get('http://localhost:8000/api/test-projects', {
      name: projectName.value,
      description: 'Creado desde el Robot',
    });
    projectName.value = '';
    router.push('/projects');
  } catch (err) {
    console.error(err);
  }
};
</script>

<style scoped>
/* No custom CSS needed; use Tailwind classes directly in template */
</style>
