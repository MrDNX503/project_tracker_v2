<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import dayjs from 'dayjs';

// Importar el plugin para manejar días restantes
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/es';

dayjs.extend(relativeTime);
dayjs.locale('es');

const props = defineProps({
    projects: Array,
});

// Estado para controlar la ventana modal
const showModal = ref(false);
const currentProject = ref(null);

// Obtiene el filtro de estado desde la URL de una manera más robusta
const status = computed(() => {
    const url = new URL(window.location.href);
    return url.searchParams.get('status') || 'todos';
});

const filteredProjects = computed(() => {
    let list = [...props.projects];

    // Lógica para los filtros
    if (status.value === 'completados') {
        list = list.filter(project => project.progress >= 100);
    } else if (status.value === 'pendientes') {
        list = list.filter(project => project.progress < 100);
    } else if (status.value === 'procrastinados') {
        list = list.filter(project => project.progress === 0);
    }

    return list;
});

// Lógica de "Recordatorio de Proyectos"
const projectAlerts = computed(() => {
    const today = dayjs();
    return props.projects.filter(project => {
        const endDate = dayjs(project.end_date);
        const daysLeft = endDate.diff(today, 'day');
        
        // Muestra alerta si el proyecto está pendiente y faltan 3 días o menos, o si la fecha ha pasado
        return project.progress < 100 && daysLeft <= 3;
    });
});

const getPriorityColor = (priority) => {
    switch (priority) {
        case 2: return 'bg-red-500'; // Alta
        case 1: return 'bg-yellow-400'; // Media
        case 0: return 'bg-blue-400'; // Baja
        default: return 'bg-gray-400';
    }
};

const getPriorityTextColor = (priority) => {
    switch (priority) {
        case 2: return '#f2f2f2'; // Alta
        case 1: return '#f2f2f2'; // Media
        case 0: return '#f2f2f2'; // Baja
        default: return 'text-gray-700';
    }
};

const getDaysLeft = (endDate) => {
    const today = dayjs();
    const end = dayjs(endDate);
    if (end.isBefore(today)) {
        return `Expirado hace ${end.fromNow(true)}`;
    }
    const days = end.diff(today, 'day');
    if (days === 0) return 'Hoy es la fecha límite';
    return `Faltan ${days} días`;
};

// Lógica para el manejo de acciones del proyecto
const handleProgressChange = (projectId, change) => {
    const project = props.projects.find(p => p.id === projectId);
    if (!project) return;
    
    const newProgress = Math.min(100, Math.max(0, project.progress + change));

    router.put(route('projects.update', projectId), { progress: newProgress });
};

const handleDelete = (projectId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este proyecto?')) {
        router.delete(route('projects.destroy', projectId));
    }
};

const handleEdit = (projectId) => {
    router.get(route('projects.edit', projectId));
};

const openProgressModal = (project) => {
    currentProject.value = project;
    showModal.value = true;
};

// Lógica para actualizar el progreso desde la modal
const updateProgressFromModal = (newProgress) => {
    if (!currentProject.value) return;
    
    const finalProgress = Math.min(100, Math.max(0, newProgress));

    router.put(route('projects.update', currentProject.value.id), { progress: finalProgress }, {
        onSuccess: () => {
            showModal.value = false;
            currentProject.value = null;
        }
    });
};
</script>

<template>
    <Head title="Mis Proyectos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                Mis Proyectos
            </h2>
        </template>

        <div class="py-12 bg-gray-900 min-h-screen text-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Controles principales y filtros -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                    <Link :href="route('projects.create')"
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition-colors duration-200 text-center">
                        + Crear Nuevo Proyecto
                    </Link>
                    <!-- Filtros como botones -->
                    <div class="flex flex-wrap justify-center md:justify-end gap-2 md:gap-4">
                        <Link :href="route('projects.index', { status: 'todos' })"
                                :class="{ 'bg-blue-600': status === 'todos' }"
                                class="py-2 px-4 rounded-full text-sm font-semibold transition-colors duration-200 bg-gray-800 hover:bg-blue-600">
                            Todos
                        </Link>
                        <Link :href="route('projects.index', { status: 'completados' })"
                                :class="{ 'bg-blue-600': status === 'completados' }"
                                class="py-2 px-4 rounded-full text-sm font-semibold transition-colors duration-200 bg-gray-800 hover:bg-blue-600">
                            Completados
                        </Link>
                        <Link :href="route('projects.index', { status: 'pendientes' })"
                                :class="{ 'bg-blue-600': status === 'pendientes' }"
                                class="py-2 px-4 rounded-full text-sm font-semibold transition-colors duration-200 bg-gray-800 hover:bg-blue-600">
                            Pendientes
                        </Link>
                        <Link :href="route('projects.index', { status: 'procrastinados' })"
                                :class="{ 'bg-blue-600': status === 'procrastinados' }"
                                class="py-2 px-4 rounded-full text-sm font-semibold transition-colors duration-200 bg-gray-800 hover:bg-blue-600">
                            Procrastinados
                        </Link>
                    </div>
                </div>

                <!-- Barra de Alerta para Proyectos Cercanos a la fecha límite -->
                <div v-if="projectAlerts.length > 0" class="p-4 mb-6 rounded-md shadow-lg"
                        :class="getPriorityColor(projectAlerts[0].priority)">
                    <p class="font-bold text-gray-900">
                        ¡Tienes proyectos que necesitan tu atención!
                    </p>
                    <ul class="mt-2 list-disc list-inside text-gray-900">
                        <li v-for="project in projectAlerts" :key="project.id">
                            <a href="#" @click.prevent="openProgressModal(project)" class="font-medium hover:underline">
                                {{ project.title }} - {{ getDaysLeft(project.end_date) }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div v-if="filteredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="project in filteredProjects" :key="project.id" class="p-6 bg-gray-800 rounded-lg shadow-md border border-gray-700">
                        <h3 class="text-xl font-bold mb-2 text-gray-100">{{ project.title }}</h3>
                        <p class="text-gray-400 text-sm mb-4">{{ project.description }}</p>
                        
                        <!-- Fechas del proyecto -->
                        <div class="text-sm text-gray-400 mb-2">
                            <p>Inicio: {{ dayjs(project.start_date).format('DD-MM-YYYY') }}</p>
                            <p>Fin: {{ dayjs(project.end_date).format('DD-MM-YYYY') }}</p>
                        </div>
                        
                        <!-- Indicador de Prioridad -->
                        <div class="mb-2">
                            <span class="text-xs font-bold uppercase py-1 px-2 rounded-full"
                                    :class="[getPriorityColor(project.priority), getPriorityTextColor(project.priority)]">
                                Prioridad: {{ ['Baja', 'Media', 'Alta'][project.priority] }}
                            </span>
                        </div>

                        <!-- Barra de progreso con botones -->
                        <div class="mb-4">
                            <div class="w-full bg-gray-600 rounded-full h-2.5 mb-1">
                                <div class="bg-blue-500 h-2.5 rounded-full" :style="{ width: project.progress + '%' }"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <button @click="handleProgressChange(project.id, -10)"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded-full text-xs transition-colors duration-200">
                                    -10%
                                </button>
                                <p class="text-sm font-medium">{{ project.progress }}%</p>
                                <button @click="handleProgressChange(project.id, 10)"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded-full text-xs transition-colors duration-200">
                                    +10%
                                </button>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="flex justify-end space-x-2">
                            <button @click="handleEdit(project.id)"
                                    class="text-blue-400 hover:text-blue-500 transition-colors duration-200 text-sm">
                                Editar
                            </button>
                            <button @click="handleDelete(project.id)"
                                    class="text-red-400 hover:text-red-500 transition-colors duration-200 text-sm">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center text-gray-500">
                    <p>No hay proyectos que coincidan con los filtros.</p>
                </div>
            </div>
        </div>

        <!-- Modal para actualizar progreso -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm border border-gray-700">
                <h3 class="text-lg font-bold mb-4 text-white">Actualizar progreso de {{ currentProject.title }}</h3>
                <p class="mb-4 text-gray-300">¿Cuánto has avanzado con el proyecto?</p>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <button @click="updateProgressFromModal(10)" class="bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition-colors">
                        +10%
                    </button>
                    <button @click="updateProgressFromModal(20)" class="bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition-colors">
                        +20%
                    </button>
                    <button @click="updateProgressFromModal(50)" class="bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition-colors">
                        +50%
                    </button>
                    <button @click="updateProgressFromModal(100)" class="bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition-colors">
                        Completado
                    </button>
                </div>
                <div class="flex justify-between items-center">
                    <button @click="updateProgressFromModal(0)" class="text-red-400 hover:text-red-500">
                        Procrastinar (0%)
                    </button>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-300 font-bold">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
