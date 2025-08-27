<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    }
});

const chartRef = ref(null);
let myChart = null;

const completedCount = ref(0);
const pendingCount = ref(0);
const procrastinatedCount = ref(0);

const calculateProjectStatus = () => {
    completedCount.value = props.projects.filter(p => p.progress === 100).length;
    pendingCount.value = props.projects.filter(p => p.progress > 0 && p.progress < 100).length;
    procrastinatedCount.value = props.projects.filter(p => p.progress === 0).length;
};

const createChart = () => {
    if (myChart) {
        myChart.destroy();
    }
    const ctx = chartRef.value.getContext('2d');
    myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Proyectos Completados', 'Proyectos Pendientes', 'Proyectos Procrastinados'],
            datasets: [{
                data: [completedCount.value, pendingCount.value, procrastinatedCount.value],
                backgroundColor: [
                    'rgba(52, 211, 153, 0.8)', // Verde para completados
                    'rgba(59, 130, 246, 0.8)', // Azul para pendientes
                    'rgba(239, 68, 68, 0.8)'   // Rojo para procrastinados
                ],
                hoverBackgroundColor: [
                    'rgba(52, 211, 153, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 2,
                borderColor: '#1f2937' // Gris oscuro para bordes
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#D1D5DB' // Color de texto para la leyenda
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += context.parsed;
                                if (context.dataset.data) {
                                    const total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    label += ` (${percentage}%)`;
                                }
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
};

onMounted(() => {
    calculateProjectStatus();
    createChart();
});

watch(() => props.projects, () => {
    calculateProjectStatus();
    if (myChart) {
        myChart.data.datasets[0].data = [completedCount.value, pendingCount.value, procrastinatedCount.value];
        myChart.update();
    }
}, { deep: true });
</script>

<template>
    <Head title="Seguimiento de Proyectos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                Seguimiento de Proyectos
            </h2>
        </template>

        <div class="py-12 bg-gray-900 min-h-screen text-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-white">Estado de tus Proyectos</h3>
                    <div class="chart-container" style="height: 400px; width: 100%;">
                        <canvas ref="chartRef"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
