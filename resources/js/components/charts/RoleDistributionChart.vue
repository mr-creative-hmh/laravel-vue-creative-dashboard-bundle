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
        type: 'donut' as const,
        fontFamily: 'inherit',
        background: 'transparent',
    },
    labels: props.labels,
    colors: ['#0f172a', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
    legend: {
        position: 'bottom' as const,
        labels: { 
            colors: isDark.value ? '#94a3b8' : '#64748b'
        },
        fontSize: '12px',
    },
    dataLabels: { enabled: false },
    stroke: { width: 0 },
    plotOptions: {
        pie: {
            donut: {
                size: '70%',
            },
        },
    },
    tooltip: {
        theme: (isDark.value ? 'dark' : 'light') as 'dark' | 'light',
    },
}));
</script>

<template>
    <div class="w-full flex items-center justify-center">
        <VueApexCharts
            type="donut"
            height="260"
            :options="chartOptions"
            :series="series"
        />
    </div>
</template>
