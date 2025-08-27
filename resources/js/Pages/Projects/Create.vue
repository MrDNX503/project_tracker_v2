<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { watch } from 'vue';

const form = useForm({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    priority: 0,
    progress: 0,
});

// Lógica de redirección con mensaje de éxito
const submit = () => {
    form.post(route('projects.store'), {
        onSuccess: () => {
            router.visit(route('projects.index'), {
                preserveScroll: true,
                onFinish: () => {
                    // Muestra el mensaje de éxito después de la redirección
                    router.reload({
                        only: ['projects'],
                        onSuccess: () => {
                            // Puedes añadir un mensaje flash aquí si el backend lo maneja
                            // En este caso, asumimos que el controlador ya lo hace.
                        }
                    });
                }
            });
        },
    });
};

// Función para el botón de cancelar
const cancel = () => {
    router.get(route('projects.index'));
};

watch(() => form.progress, (newProgress) => {
    // Asegura que el progreso esté entre 0 y 100
    if (newProgress < 0) {
        form.progress = 0;
    } else if (newProgress > 100) {
        form.progress = 100;
    }
});
</script>

<template>
    <Head title="Crear Proyecto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Crear Nuevo Proyecto</h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <!-- Título del proyecto -->
                        <div class="mb-4">
                            <label for="title" class="block text-gray-400 font-medium mb-1">Título</label>
                            <input type="text" id="title" v-model="form.title" class="w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-blue-500 focus:ring-blue-500" required>
                            <div v-if="form.errors.title" class="text-red-400 mt-2 text-sm">{{ form.errors.title }}</div>
                        </div>

                        <!-- Descripción del proyecto -->
                        <div class="mb-4">
                            <label for="description" class="block text-gray-400 font-medium mb-1">Descripción</label>
                            <textarea id="description" v-model="form.description" class="w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-blue-500 focus:ring-blue-500"></textarea>
                            <div v-if="form.errors.description" class="text-red-400 mt-2 text-sm">{{ form.errors.description }}</div>
                        </div>
                        
                        <!-- Fechas del proyecto -->
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

                        <!-- Progreso -->
                        <div class="mb-4">
                            <label for="progress" class="block text-gray-400 font-medium mb-1">Progreso (%)</label>
                            <input type="number" id="progress" v-model="form.progress" min="0" max="100" class="w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-blue-500 focus:ring-blue-500" required>
                            <div v-if="form.errors.progress" class="text-red-400 mt-2 text-sm">{{ form.errors.progress }}</div>
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
                                Crear Proyecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
