<script setup>
import { ref } from 'vue';

const props = defineProps({
    progress: Number,
});

const emit = defineEmits(['updateProgress']);

const currentProgress = ref(props.progress);

const updateProgress = (amount) => {
    let newProgress = currentProgress.value + amount;
    if (newProgress < 0) newProgress = 0;
    if (newProgress > 100) newProgress = 100;
    currentProgress.value = newProgress;
    
    // En un proyecto real, aquí emitirías un evento o harías una llamada a la API
    // para actualizar el progreso del proyecto en el backend.
    emit('updateProgress', newProgress);
};
</script>

<template>
    <div class="mt-4">
        <div class="flex items-center space-x-2 mb-2">
            <button @click="updateProgress(-10)" class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs">-10%</button>
            <div class="flex-grow bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" :style="{ width: currentProgress + '%' }"></div>
            </div>
            <button @click="updateProgress(10)" class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs">+10%</button>
        </div>
        <p class="text-center text-sm font-medium text-gray-700">Progreso: {{ currentProgress }}%</p>
    </div>
</template>
