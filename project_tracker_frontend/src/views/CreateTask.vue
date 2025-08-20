<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold">Create New Task</h1>
    <form @submit.prevent="CreateTask" class="flex flex-col gap-2 max-w-md">
      <input
        v-model="form.title"
        type="text"
        placeholder="Task Title"
        class="border p-2 rounded"
        required
      />
      <textarea
        v-model="form.description"
        placeholder="Description"
        class="border p-2 rounded"
      ></textarea>
      <select v-model="form.priority" class="border p-2 rounded">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
      <textarea
        v-model="form.notes"
        placeholder="Notes"
        class="border p-2 rounded"
      ></textarea>
      <input
        v-model="form.due_date"
        type="date"
        class="border p-2 rounded"
      />
      <select v-model="form.status" class="border p-2 rounded">
        <option value="pending">Pending</option>
        <option value="in_progress">In Progress</option>
        <option value="completed">Completed</option>
      </select>
      <input
        v-model="form.project_id"
        type="number"
        placeholder="Project ID"
        class="border p-2 rounded"
        required
      />
      <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
        Create Task
      </button>
    </form>
    <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
  </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  data() {
    return {
      form: {
        title: '',
        description: '',
        priority: 'medium',
        notes: '',
        due_date: '',
        status: 'pending',
        project_id: '',
      },
      error: null,
    };
  },
  setup() {
    return { router: useRouter() };
  },
  methods: {
    async CreateTask() {
      try {
        const response = await axios.post('http://localhost:8000/api/test-tasks', this.form);
        this.router.push('/dashboard');
      } catch (error) {
        this.error = 'Failed to create task: ' + (error.response?.data?.message || 'Unknown error');
      }
    },
  },
};
</script>