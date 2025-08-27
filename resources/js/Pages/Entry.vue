<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';

const { props } = usePage();
const canLogin = props.canLogin;
const canRegister = props.canRegister;
</script>

<template>
    <Head title="Bienvenido" />

    <div class="relative min-h-screen bg-black flex flex-col items-center justify-center p-4">
        <!-- Título de la PWA -->
        <h1 class="text-4xl sm:text-6xl font-extrabold text-white mb-8 z-10 font-inter">
            Project Tracker V2.0
        </h1>
        
        <!-- Contenedor principal con opciones de acceso -->
        <div class="z-10 bg-gray-900 rounded-lg p-8 shadow-xl text-center">
            <h2 class="text-white text-2xl mb-6">Elige cómo quieres empezar</h2>
            
            <div class="space-y-4">
                <div v-if="canLogin">
                    <Link :href="route('login')"
                        class="block w-full py-3 px-6 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-700 transition-colors duration-200">
                        Iniciar sesión
                    </Link>
                </div>
                <div v-if="canRegister">
                    <Link :href="route('register')"
                        class="block w-full py-3 px-6 rounded-full bg-gray-700 text-white font-bold hover:bg-gray-800 transition-colors duration-200">
                        Registrarse
                    </Link>
                </div>
                <div>
                    <Link :href="route('welcome')"
                        class="block w-full py-3 px-6 rounded-full bg-transparent border-2 border-gray-500 text-gray-300 font-bold hover:bg-gray-800 transition-colors duration-200">
                        Continuar como invitado
                    </Link>
                </div>
            </div>
        </div>
        
        <!-- Estrellas fugaces con CSS (el mismo efecto que antes) -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div class="absolute bg-white rounded-full star star-1"></div>
            <div class="absolute bg-white rounded-full star star-2"></div>
            <div class="absolute bg-white rounded-full star star-3"></div>
            <div class="absolute bg-white rounded-full star star-4"></div>
            <div class="absolute bg-white rounded-full star star-5"></div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap');
.font-inter {
    font-family: 'Inter', sans-serif;
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
