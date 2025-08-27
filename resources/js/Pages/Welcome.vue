<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';

const textToType = '¿Qué proyecto te gustaría crear hoy?';
const typedText = ref('');
const showButton = ref(false);
const { props } = usePage();
const canLogin = props.canLogin;

const authUser = computed(() => usePage().props.auth.user);

const typeEffect = () => {
    let i = 0;
    const interval = setInterval(() => {
        if (i < textToType.length) {
            typedText.value += textToType.charAt(i);
            i++;
        } else {
            clearInterval(interval);
            showButton.value = true;
        }
    }, 50); // Velocidad del efecto de máquina de escribir
};

onMounted(() => {
    typeEffect();
});
</script>

<template>
    <Head title="Home" />

    <div class="relative min-h-screen bg-black flex flex-col items-center justify-center p-4 overflow-hidden">
        <!-- Barra superior para el usuario logeado -->
        <div v-if="authUser" class="absolute top-0 right-0 mt-4 mr-4 text-white z-20 flex items-center space-x-4">
            <span class="text-sm">Hola, {{ authUser.name }}</span>
            <Link :href="route('logout')" method="post" as="button"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-full transition-colors duration-200 text-sm">
                Cerrar Sesión
            </Link>
        </div>

        <!-- Título de la PWA (más pequeño) -->
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-8 z-20 font-inter">
            Project Tracker V2.0
        </h1>

        <!-- Estrellas fugaces con CSS/JS -->
        <div class="absolute inset-0 z-0">
            <div class="absolute bg-white rounded-full star star-1"></div>
            <div class="absolute bg-white rounded-full star star-2"></div>
            <div class="absolute bg-white rounded-full star star-3"></div>
            <div class="absolute bg-white rounded-full star star-4"></div>
            <div class="absolute bg-white rounded-full star star-5"></div>
        </div>

        <!-- Robot y cuadro de diálogo con efecto de máquina de escribir -->
        <div class="z-10 flex flex-col items-center mt-20">
            <!-- Icono del robot (usando SVG incrustado) -->
            <svg class="w-24 h-24 mb-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-3 18c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm6 0c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/>
            </svg>
            
            <div class="bg-gray-800 text-white p-6 rounded-lg shadow-lg max-w-sm text-center transform scale-100 transition-all duration-500 ease-in-out">
                <p class="mb-4 typed-text">{{ typedText }}<span class="cursor">|</span></p>
                
                <Transition name="fade">
                    <Link v-if="showButton" :href="route('projects.create')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full transition-colors duration-200">
                        Crear proyecto
                    </Link>
                </Transition>
            </div>
            
            <!-- Nuevos botones de navegación -->
            <div class="mt-8 text-center text-gray-300 space-y-2">
                <Link :href="route('projects.index')"
                    class="block w-64 bg-gray-800 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 hover:bg-blue-700 transform hover:scale-105 shadow-lg">
                    Ver todos los proyectos
                </Link>
                <Link :href="route('projects.index', { status: 'completados' })"
                    class="block w-64 bg-gray-800 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 hover:bg-blue-700 transform hover:scale-105 shadow-lg">
                    Ver proyectos completados 🎉
                </Link>
                <Link :href="route('projects.index', { status: 'pendientes' })"
                    class="block w-64 bg-gray-800 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 hover:bg-blue-700 transform hover:scale-105 shadow-lg">
                    Ver proyectos pendientes
                </Link>
                <Link :href="route('projects.index', { status: 'procrastinados' })"
                    class="block w-64 bg-gray-800 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 hover:bg-blue-700 transform hover:scale-105 shadow-lg">
                    Proyectos procrastinados
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap');
.font-inter {
    font-family: 'Inter', sans-serif;
}
.typed-text {
    white-space: nowrap;
    overflow: hidden;
}
.cursor {
    display: inline-block;
    animation: blink 1s infinite;
}
@keyframes blink {
    50% { opacity: 0; }
}

@keyframes shooting-star {
    0% { transform: translateY(0) translateX(0) rotate(225deg); opacity: 0; }
    50% { opacity: 1; }
    100% { transform: translateY(100vh) translateX(100vw) rotate(225deg); opacity: 0; }
}
.star {
    position: absolute;
    width: 2px;
    height: 2px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 0 10px #fff;
    animation: shooting-star linear infinite;
    top: -100px;
}
.star-1 { animation-duration: 5s; animation-delay: 0s; top: 10%; left: 15%; }
.star-2 { animation-duration: 7s; animation-delay: 2s; top: 30%; left: 60%; }
.star-3 { animation-duration: 6s; animation-delay: 4s; top: 50%; left: 25%; }
.star-4 { animation-duration: 8s; animation-delay: 6s; top: 70%; left: 80%; }
.star-5 { animation-duration: 9s; animation-delay: 8s; top: 90%; left: 45%; }
</style>
