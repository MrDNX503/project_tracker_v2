<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold">Crea un nuevo proyecto</h1><br/>
    <form @submit.prevent="createProject" class="flex flex-col gap-2 max-w-md">
      <input
        v-model="form.name"
        type="text"
        placeholder="Nombre del proyecto"
        class="border p-2 rounded"
        required
      />
      <textarea
        v-model="form.description"
        placeholder="Descripción del proyecto"
        class="border p-2 rounded"
      ></textarea>
      <input
        v-model="form.start_date"
        placeholder="Fecha de creación"
        type=date
        class="border p-2 rounded"
      />
      <input
        v-model="form.end_date"
        placeholder="Fecha de finalización"
        type=date
        class="border p-2 rounded"
      />
      <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
        Crear Proyecto
      </button>
    </form>
    <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = ref({
  name: '',
  description: '',
  start_date: '',
  end_date: '',
});

const error = ref(null);

const createProject = async () => {
  try {
    await axios.post('http://localhost:8000/api/test-projects', form.value);
    router.push('/dashboard');
  } catch (err) {
    error.value = 'Failed to create project: ' + (err.response?.data?.message || 'Unknown error');
  }
};
</script>
