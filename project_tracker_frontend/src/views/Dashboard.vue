<template>
  <div class="dashboard min-h-screen flex flex-col items-center bg-gray-50">

    <!-- Banner del título (full width usando -mx-4) -->
    <div class="title-container relative w-full h-32 -mx-4 flex items-center justify-center mb-6 mt-0 overflow-hidden rounded-b-xl">
      <div class="bg-layer absolute inset-0 z-0"></div>
      <div class="stars absolute inset-0 z-10" aria-hidden="true"></div>
      <h1 class="text-4xl font-bold z-20 relative">Project Tracker 2.0</h1>
    </div>

    <!-- Robot y diálogo: robot a la izquierda en desktop, stacked en mobile -->
    <div class="robot-dialog-container flex flex-col md:flex-row items-center justify-center w-full max-w-4xl mb-8 gap-3 md:gap-6">


      <!-- Robot (lado izquierdo) -->
      <div class="robot w-40 flex-shrink-0 flex flex-col items-center">
        <!-- Cabeza -->
        <div class="robot-head w-16 h-16 rounded-lg relative flex items-center justify-center shadow-md">
          <div class="robot-eye left-eye"></div>
          <div class="robot-eye right-eye"></div>
        </div>

        <!-- Cuerpo (más pegado a la cabeza: mt-1) -->
        <div class="robot-body w-28 h-28 rounded-lg relative mt-1 flex items-start justify-center shadow-md">
          <!-- Brazo izquierdo -->
          <div class="robot-arm robot-arm-left animate-wave-hand"></div>
          <!-- Brazo derecho -->
          <div class="robot-arm robot-arm-right animate-wave-hand-reverse"></div>
        </div>
      </div>

      <!-- Cuadro de diálogo (lado derecho) -->
      <div
        :class="['robot-dialog p-6 rounded-lg shadow-lg bg-white text-black w-full md:w-1/2 flex flex-col items-center relative', { enter: showDialog }]"
      >
        <p class="typewriter mb-4 text-center" aria-live="polite">{{ displayedText }}</p>
        <button v-if="showButton" @click="goToCreateProject" class="btn-create px-4 py-2 rounded">
          Crear nuevo proyecto
        </button>
      </div>
    </div>

    <!-- Menú de proyectos: grid en desktop, columna en móvil -->
    <div class="project-menu w-full max-w-4xl text-center">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div v-for="(option, index) in menuOptions" :key="index">
          <div @click="router.push(option.link)" class="menu-btn p-4 rounded shadow cursor-pointer hover:shadow-lg">
            {{ option.label }}
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Máquina de escribir + control de aparición
const fullText = "¿Qué proyecto te gustaría crear hoy?";
const displayedText = ref("");
const showButton = ref(false);
const showDialog = ref(false);

let intervalId = null;
let dialogTimer = null;
let typeTimer = null;

const typeWriter = () => {
  let index = 0;
  displayedText.value = "";
  showButton.value = false;
  intervalId = setInterval(() => {
    displayedText.value += fullText[index] ?? "";
    index++;
    if (index >= fullText.length) {
      clearInterval(intervalId);
      intervalId = null;
      showButton.value = true;
    }
  }, 50);
};

onMounted(() => {
  // Secuencia: mostrar diálogo (entrada) -> luego empezar máquina de escribir
  showDialog.value = false;
  dialogTimer = setTimeout(() => {
    showDialog.value = true;
    // empezar la escritura justo después de que el diálogo tenga tiempo de entrar
    typeTimer = setTimeout(() => {
      typeWriter();
    }, 260);
  }, 120);
});

onBeforeUnmount(() => {
  if (intervalId) clearInterval(intervalId);
  if (dialogTimer) clearTimeout(dialogTimer);
  if (typeTimer) clearTimeout(typeTimer);
});

const goToCreateProject = () => {
  router.push('/create-project');
};

const menuOptions = [
  { label: "Ver todos los proyectos", link: "/projects" },
  { label: "Proyectos con prioridad", link: "/projects?filter=priority" },
  { label: "Proyectos con procrastinación", link: "/projects?filter=procrastination" },
  { label: "Proyectos completados", link: "/projects?filter=completed" },
];
</script>
