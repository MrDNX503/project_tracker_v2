import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../views/Dashboard.vue';
import CreateProject from '../views/CreateProject.vue';
import Projects from '../views/Projects.vue';

const routes = [
  { path: '/', redirect: '/dashboard' }, // Redirigir a dashboard por defecto
  { path: '/dashboard', name: 'dashboard', component: Dashboard },
  { path: '/create-project', name: 'create-project', component: CreateProject },
  { path: '/projects', name: 'projects', component: Projects },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
