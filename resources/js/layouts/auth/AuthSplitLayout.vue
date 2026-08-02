<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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
    <div class="relative grid h-dvh flex-col items-center justify-center lg:max-w-none lg:grid-cols-2 lg:px-0">
        <Toaster richColors />

        <!-- Left Panel - Branded -->
        <div class="relative hidden h-full flex-col bg-slate-950 dark:bg-slate-950 p-10 text-white lg:flex">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -left-40 h-[500px] w-[500px] rounded-full bg-gradient-to-br from-indigo-600/25 to-violet-600/10 blur-[130px]"></div>
                <div class="absolute bottom-0 right-0 h-[400px] w-[400px] rounded-full bg-gradient-to-tr from-purple-600/20 to-pink-600/10 blur-[140px]"></div>
            </div>
            <div class="relative z-20 flex flex-col items-start gap-4">
                <Link :href="home()" class="transition hover:opacity-90">
                    <AppLogo />
                </Link>
                <ThemeToggle />
            </div>
            <div class="relative z-20 mt-auto">
                <div class="inline-flex items-center gap-2 rounded-xl border border-indigo-500/20 bg-indigo-500/10 px-4 py-1.5 text-xs font-semibold text-indigo-300 backdrop-blur-md">
                    <Sparkles class="h-3.5 w-3.5 text-indigo-400" />
                    <span>{{ t('Created by Eng. Hasan Mohammad Hasan') }}</span>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form Area -->
        <div class="relative min-h-screen flex flex-col items-center justify-between bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 p-6 lg:p-8">
            <div class="fixed inset-0 overflow-hidden pointer-events-none z-0 lg:hidden">
                <div class="absolute -top-40 -left-40 h-[500px] w-[500px] rounded-full bg-gradient-to-br from-indigo-600/15 dark:from-indigo-600/25 to-violet-600/5 dark:to-violet-600/10 blur-[130px]"></div>
                <div class="absolute -bottom-40 left-1/3 h-[500px] w-[500px] rounded-full bg-gradient-to-tr from-purple-600/10 dark:from-purple-600/20 to-pink-600/5 dark:to-pink-600/10 blur-[140px]"></div>
            </div>

            <!-- Mobile Header -->
            <header class="relative z-10 flex w-full items-center justify-between py-4 lg:justify-end">
                <Link :href="home()" class="transition hover:opacity-90 lg:hidden">
                    <AppLogo />
                </Link>
                <div class="flex items-center gap-3">
                    <ThemeToggle />
                    <LanguageSwitcher />
                </div>
            </header>

            <!-- Form Content -->
            <div class="relative z-10 mx-auto flex w-full max-w-md flex-col justify-center space-y-6 my-auto">
                <div class="w-full rounded-3xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 p-8 sm:p-10 shadow-2xl shadow-indigo-500/10 backdrop-blur-2xl">
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

                    <slot />
                </div>
            </div>

            <!-- Footer Credit -->
            <footer class="relative z-10 py-4 text-center lg:hidden">
                <div class="flex flex-col items-center gap-3">
                    <div class="inline-flex items-center gap-2 rounded-xl border border-indigo-500/20 bg-indigo-500/10 px-4 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-300 backdrop-blur-md">
                        <Sparkles class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" />
                        <span>{{ t('Created by Eng. Hasan Mohammad Hasan') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
