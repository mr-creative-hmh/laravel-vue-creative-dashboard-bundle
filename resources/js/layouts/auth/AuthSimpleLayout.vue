<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Sparkles } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import { Toaster } from '@/components/ui/sonner';
import { useTrans } from '@/composables/useTrans';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();

const { t } = useTrans();
</script>

<template>
    <div class="relative flex min-h-screen flex-col items-center justify-between bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 selection:bg-indigo-500 selection:text-white font-['Outfit',sans-serif] p-6">
        <Toaster richColors />

        <!-- Background Gradient Glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -left-40 h-[500px] w-[500px] rounded-full bg-gradient-to-br from-indigo-600/15 dark:from-indigo-600/25 to-violet-600/5 dark:to-violet-600/10 blur-[130px]"></div>
            <div class="absolute top-1/3 -right-40 h-[550px] w-[550px] rounded-full bg-gradient-to-bl from-emerald-600/10 dark:from-emerald-600/15 to-teal-600/5 dark:to-teal-600/10 blur-[140px]"></div>
            <div class="absolute -bottom-40 left-1/3 h-[500px] w-[500px] rounded-full bg-gradient-to-tr from-purple-600/10 dark:from-purple-600/20 to-pink-600/5 dark:to-pink-600/10 blur-[140px]"></div>
        </div>

        <!-- Top Header Navigation -->
        <header class="relative z-10 flex w-full max-w-7xl items-center justify-between py-4">
            <Link :href="home()" class="transition hover:opacity-90">
                <AppLogo />
            </Link>

            <div class="flex items-center gap-3">
                <ThemeToggle />
                <LanguageSwitcher />
            </div>
        </header>

        <!-- Main Auth Glassmorphic Card -->
        <main class="relative z-10 my-auto flex w-full max-w-md flex-col items-center py-8">
            <div class="w-full rounded-3xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 p-8 sm:p-10 shadow-2xl shadow-indigo-500/10 backdrop-blur-2xl">
                <!-- Header / Logo -->
                <div class="flex flex-col items-center gap-3 text-center mb-8">
                    <Link :href="home()" class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-500/10 border border-indigo-500/20 shadow-inner">
                        <AppLogoIcon class="h-10 w-10 drop-shadow-[0_0_12px_rgba(99,102,241,0.6)]" />
                    </Link>

                    <h1 v-if="title" class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white mt-2">
                        {{ t(title) }}
                    </h1>
                    <p v-if="description" class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ t(description) }}
                    </p>
                </div>

                <!-- Form Body -->
                <slot />
            </div>
        </main>

        <!-- Footer Credit -->
        <footer class="relative z-10 py-4 text-center">
            <div class="inline-flex items-center gap-2 rounded-xl border border-indigo-500/20 bg-indigo-500/10 px-4 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-300 backdrop-blur-md">
                <Sparkles class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" />
                <span>{{ t('Created by Eng. Hasan Mohammad Hasan') }}</span>
            </div>
        </footer>
    </div>
</template>
