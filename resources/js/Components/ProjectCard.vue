<script setup>
import { computed } from 'vue';
import ProgressIndicator from './ProgressIndicator.vue';
import dayjs from 'dayjs';

const props = defineProps({
    project: Object,
});

const daysRemaining = computed(() => {
    const today = dayjs();
    const deadline = dayjs(props.project.deadline);
    const diff = deadline.diff(today, 'day');
    return diff > 0 ? diff : 0;
});

const priorityColor = computed(() => {
    switch (props.project.priority) {
        case 'urgente': return 'bg-red-500';
        case 'alta': return 'bg-orange-500';
        case 'media': return 'bg-yellow-500';
        case 'baja': return 'bg-green-500';
        default: return 'bg-gray-500';
    }
});
</script>

<template>
    <div class="bg-gray-100 rounded-lg shadow-md p-6 border-l-4" :class="priorityColor">
        <h3 class="text-xl font-bold mb-2">{{ project.name }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ project.description }}</p>
        
        <div class="flex justify-between items-center text-sm text-gray-500 mb-2">
            <span>Creado: {{ project.creation_date }}</span>
            <span>Faltan: {{ daysRemaining }} días</span>
        </div>

        <ProgressIndicator :progress="project.progress" />
    </div>
</template>
