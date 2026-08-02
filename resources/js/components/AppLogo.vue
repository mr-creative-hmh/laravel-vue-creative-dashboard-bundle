<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();
</script>

<template>
    <div class="flex items-center gap-2">
        <!-- Col 1: Logo -->
        <AppLogoIcon class="size-8 drop-shadow-[0_0_8px_rgba(99,102,241,0.5)] shrink-0" />

        <!-- Col 2: Name in 2 rows -->
        <div class="grid flex-1 text-start leading-tight">
            <span class="truncate font-extrabold tracking-tight text-neutral-900 dark:text-white text-sm">
                Creative Starter
            </span>
            <span class="truncate font-semibold text-neutral-500 dark:text-slate-400 text-[10px]">
                Dashboard Kit
            </span>
        </div>

        <!-- Col 3: Version -->
        <span class="shrink-0 rounded-md bg-indigo-500/10 border border-indigo-500/25 px-1.5 py-0.5 text-[9px] font-semibold text-indigo-500 dark:text-indigo-400 uppercase tracking-wide">
            v1.0
        </span>
    </div>
</template>
