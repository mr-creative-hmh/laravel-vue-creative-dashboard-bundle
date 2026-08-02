<script setup lang="ts">
import { Monitor, Moon, Sun } from '@lucide/vue';
import { useAppearance } from '@/composables/useAppearance';
import { useTrans } from '@/composables/useTrans';

const { appearance, updateAppearance } = useAppearance();
const { t } = useTrans();

const modes = ['system', 'dark', 'light'] as const;
const icons = {
    system: Monitor,
    dark: Moon,
    light: Sun,
} as const;

const cycleMode = () => {
    const currentIndex = modes.indexOf(appearance.value);
    const nextIndex = (currentIndex + 1) % modes.length;
    updateAppearance(modes[nextIndex]);
};
</script>

<template>
    <button
        @click="cycleMode"
        type="button"
        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-100 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900/90 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100 backdrop-blur-md cursor-pointer"
        :title="t('Theme Mode') + ': ' + t(appearance)"
    >
        <component
            :is="icons[appearance]"
            :class="[
                'h-4 w-4 transition-colors',
                appearance === 'light' ? 'text-amber-500 dark:text-amber-400' : appearance === 'dark' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400'
            ]"
        />
    </button>
</template>
