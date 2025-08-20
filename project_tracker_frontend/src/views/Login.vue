<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold">Login</h1>
    <form @submit.prevent="login" class="flex flex-col gap-2 max-w-md">
      <input
        v-model="email"
        type="email"
        placeholder="Email"
        class="border p-2 rounded"
        required
      />
      <input
        v-model="password"
        type="password"
        placeholder="Password"
        class="border p-2 rounded"
        required
      />
      <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
        Login
      </button>
    </form>
    <button @click="skipLogin" class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600 mt-2">
      Skip Login (For Testing)
    </button>
    <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
  </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  data() {
    return {
      email: '',
      password: '',
      error: null,
    };
  },
  setup() {
    const router = useRouter();
    return { router };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://localhost:8000/api/login', {
          email: this.email,
          password: this.password,
        });
        localStorage.setItem('token', response.data.token);
        this.router.push('/dashboard');
      } catch (error) {
        this.error = 'Login failed: ' + (error.response?.data?.message || 'Unknown error');
      }
    },
    skipLogin() {
      localStorage.setItem('token', 'test-token'); // Token dummy para pruebas
      this.router.push('/dashboard');
    },
  },
};
</script>