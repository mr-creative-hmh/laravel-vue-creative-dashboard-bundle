<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { LogOut, RefreshCw, ShieldCheck, Wrench } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import { Toaster } from '@/components/ui/sonner';
import { useAuth } from '@/composables/useAuth';
import { useTrans } from '@/composables/useTrans';
import { home } from '@/routes';

const { user } = useAuth();
const { t } = useTrans();

const handleReload = () => {
    window.location.reload();
};
</script>

<template>
    <Head :title="t('Maintenance Mode')" />

    <div class="relative flex min-h-screen flex-col items-center justify-between bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white font-['Outfit',sans-serif] p-6">
        <Toaster richColors />
        <!-- Background Gradient Glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -left-40 h-[500px] w-[500px] rounded-full bg-gradient-to-br from-indigo-600/25 to-violet-600/10 blur-[130px]"></div>
            <div class="absolute -bottom-40 right-0 h-[500px] w-[500px] rounded-full bg-gradient-to-tr from-cyan-600/20 to-teal-600/10 blur-[140px]"></div>
        </div>

        <!-- Header -->
        <header class="relative z-10 flex w-full max-w-7xl items-center justify-between py-4">
            <Link :href="home()" class="transition hover:opacity-90">
                <AppLogo />
            </Link>
            <div class="flex items-center gap-3">
                <ThemeToggle />
                <LanguageSwitcher />
            </div>
        </header>

        <!-- Main Card -->
        <main class="relative z-10 my-auto flex w-full max-w-lg flex-col items-center py-8">
            <div class="w-full rounded-3xl border border-slate-800/80 bg-slate-900/80 p-8 sm:p-10 text-center shadow-2xl shadow-indigo-500/10 backdrop-blur-2xl">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-indigo-500/10 border border-indigo-500/20 shadow-inner">
                    <Wrench class="h-10 w-10 text-indigo-400 animate-pulse" />
                </div>

                <span class="inline-block rounded-full bg-indigo-500/10 border border-indigo-500/30 px-3 py-1 text-xs font-bold text-indigo-400 mb-3 tracking-widest uppercase">
                    503 {{ t('Maintenance Mode') }}
                </span>

                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    {{ t('Under Maintenance') }}
                </h1>

                <p class="mt-3 text-sm text-slate-400 leading-relaxed">
                    {{ t('System is currently under maintenance. Please try again later.') }}
                </p>

                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3 flex-wrap">
                    <Link
                        href="/login"
                        class="inline-flex h-12 w-full sm:w-auto items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 px-6 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40 cursor-pointer"
                    >
                        <ShieldCheck class="h-4 w-4" />
                        <span>{{ t('Admin Login') }}</span>
                    </Link>

                    <button
                        type="button"
                        @click="handleReload"
                        class="inline-flex h-12 w-full sm:w-auto items-center justify-center gap-2 rounded-2xl border border-slate-700 bg-slate-800/80 px-6 text-sm font-bold text-slate-200 transition hover:bg-slate-700 hover:text-white cursor-pointer"
                    >
                        <RefreshCw class="h-4 w-4" />
                        <span>{{ t('Refresh Page') }}</span>
                    </button>

                    <Link
                        v-if="user"
                        href="/logout"
                        method="post"
                        as="button"
                        class="inline-flex h-12 w-full sm:w-auto items-center justify-center gap-2 rounded-2xl border border-rose-500/30 bg-rose-500/10 text-rose-300 px-6 text-sm font-bold transition hover:bg-rose-500/20 hover:text-rose-200 cursor-pointer"
                    >
                        <LogOut class="h-4 w-4" />
                        <span>{{ t('Log out') }}</span>
                    </Link>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 py-4 text-center text-xs text-slate-500">
            © {{ new Date().getFullYear() }} {{ t('Creative Starter Dashboard Kit') }}
        </footer>
    </div>
</template>
