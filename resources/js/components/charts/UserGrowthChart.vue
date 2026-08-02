<script setup lang="ts">
import { computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import { useAppearance } from '@/composables/useAppearance';

const props = defineProps<{
    labels: string[];
    series: number[];
}>();

const { appearance } = useAppearance();

const isDark = computed(() => {
    if (appearance.value === 'system') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    return appearance.value === 'dark';
});

const chartOptions = computed(() => ({
    chart: {
        type: 'area' as const,
        toolbar: { show: false },
        zoom: { enabled: false },
        fontFamily: 'inherit',
        background: 'transparent',
    },
    colors: [isDark.value ? '#3b82f6' : '#0f172a'],
    stroke: {
        curve: 'smooth' as const,
        width: 2.5,
    },
    fill: {
        type: 'gradient' as const,
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.35,
            opacityTo: 0.05,
            stops: [0, 90, 100],
        },
    },
    dataLabels: { enabled: false },
    xaxis: {
        categories: props.labels,
        labels: {
            style: { 
                colors: isDark.value ? '#94a3b8' : '#64748b',
                fontSize: '11px'
            },
        },
        axisBorder: { show: false },
        axisTicks: { show: false },
    },
    yaxis: {
        labels: {
            style: { 
                colors: isDark.value ? '#94a3b8' : '#64748b',
                fontSize: '11px'
            },
        },
    },
    grid: {
        borderColor: isDark.value ? '#334155' : '#f1f5f9',
        strokeDashArray: 4,
    },
    tooltip: {
        theme: (isDark.value ? 'dark' : 'light') as 'dark' | 'light',
    },
}));

const chartSeries = computed(() => [
    {
        name: 'New Registrations',
        data: props.series,
    },
]);
</script>

<template>
    <div class="w-full">
        <VueApexCharts
            type="area"
            height="260"
            :options="chartOptions"
            :series="chartSeries"
        />
    </div>
</template>
