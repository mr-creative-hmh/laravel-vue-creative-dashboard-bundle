<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();
</script>

<template>
    <div class="flex items-center gap-2.5 min-w-0">
        <!-- Col 1: Logo Icon -->
        <AppLogoIcon class="size-8 drop-shadow-[0_0_8px_rgba(99,102,241,0.5)] shrink-0" />

        <!-- Col 2: Brand Title, Subtitle & Badges -->
        <div class="flex flex-col justify-center min-w-0 text-start leading-tight gap-0.5">
            <!-- Row 1: Brand Title & Version -->
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="truncate font-extrabold tracking-tight text-neutral-900 dark:text-white text-xs sm:text-sm">
                    Creative Starter
                </span>
                <span
                    class="inline-flex items-center rounded-full bg-slate-900/10 dark:bg-white/10 text-slate-700 dark:text-slate-200 border border-slate-900/15 dark:border-white/20 px-1.5 py-0.5 text-[8px] font-mono font-bold shrink-0">
                    v1.0
                </span>
            </div>

            <!-- Row 2: Subtitle & PRO Bundle Badge -->
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="truncate font-semibold text-neutral-500 dark:text-slate-400 text-[10px] shrink-0">
                    Dashboard Kit
                </span>
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-violet-600 via-indigo-600 to-purple-600 text-white border border-indigo-400/30 px-1.5 py-0.5 text-[8px] font-black uppercase tracking-wider shrink-0 shadow-[0_1px_6px_rgba(99,102,241,0.35)]">
                    <span class="size-1 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_4px_#34d399]"></span>
                    PRO Bundle
                </span>
            </div>
        </div>
    </div>
</template>
