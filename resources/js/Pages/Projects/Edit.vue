<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

const formattedStartDate = props.project.start_date ? dayjs(props.project.start_date).format('YYYY-MM-DD') : '';
const formattedEndDate = props.project.end_date ? dayjs(props.project.end_date).format('YYYY-MM-DD') : '';

const form = useForm({
    title: props.project.title,
    description: props.project.description,
    start_date: formattedStartDate,
    end_date: formattedEndDate,
    priority: props.project.priority,
    // Se incluye el progreso para que la validación del controlador no falle.
    progress: props.project.progress, 
});

const submit = () => {
    // Al usar Inertia, el backend debe manejar la redirección.
    form.put(route('projects.update', props.project.id));
};

const cancel = () => {
    router.get(route('projects.index'));
};
</script>

<template>
    <Head title="Editar Proyecto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Editar Proyecto</h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="title" class="block text-gray-400 font-medium mb-1">Título</label>
                            <input type="text" id="title" v-model="form.title" class="w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-blue-500 focus:ring-blue-500" required>
                            <div v-if="form.errors.title" class="text-red-400 mt-2 text-sm">{{ form.errors.title }}</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-400 font-medium mb-1">Descripción</label>
                            <textarea id="description" v-model="form.description" class="w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-blue-500 focus:ring-blue-500"></textarea>
                            <div v-if="form.errors.description" class="text-red-400 mt-2 text-sm">{{ form.errors.description }}</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="start_date" class="block text-gray-400 font-medium mb-1">Fecha de inicio</label>
                            <input type="date" id="start_date" v-model="form.start_date" class="w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-blue-500 focus:ring-blue-500" required>
                            <div v-if="form.errors.start_date" class="text-red-400 mt-2 text-sm">{{ form.errors.start_date }}</div>
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="block text-gray-400 font-medium mb-1">Fecha de finalización</label>
                            <input type="date" id="end_date" v-model="form.end_date" class="w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-blue-500 focus:ring-blue-500" required>
                            <div v-if="form.errors.end_date" class="text-red-400 mt-2 text-sm">{{ form.errors.end_date }}</div>
                        </div>

                        <!-- Prioridad -->
                        <div class="mb-4">
                            <label for="priority" class="block text-gray-400 font-medium mb-1">Prioridad</label>
                            <select id="priority" v-model="form.priority" class="w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-blue-500 focus:ring-blue-500" required>
                                <option :value="0">Baja</option>
                                <option :value="1">Media</option>
                                <option :value="2">Alta</option>
                            </select>
                            <div v-if="form.errors.priority" class="text-red-400 mt-2 text-sm">{{ form.errors.priority }}</div>
                        </div>

                        <div class="flex items-center justify-end mt-4 space-x-2">
                            <button type="button" @click="cancel" class="px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancelar
                            </button>
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
