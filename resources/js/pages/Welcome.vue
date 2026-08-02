<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    Activity,
    ArrowRight,
    CheckCircle2,
    Code2,
    Globe,
    Heart,
    KeyRound,
    Layers,
    LayoutDashboard,
    Lock,
    LogIn,
    Shield,
    Sparkles,
    Users,
    Zap,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import { Toaster } from '@/components/ui/sonner';
import { useTrans } from '@/composables/useTrans';
import { dashboard, login, register } from '@/routes';

const { t } = useTrans();
const page = usePage();
</script>

<template>
    <Head :title="t('Creative Starter Dashboard Kit - Laravel 13 & Vue 3 Foundation')" />

    <div class="min-h-screen bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 selection:bg-indigo-500 selection:text-white font-['Outfit',sans-serif]">
        <Toaster richColors />
        <!-- Background Gradient Glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -left-40 h-[550px] w-[550px] rounded-full bg-gradient-to-br from-indigo-600/20 dark:from-indigo-600/30 to-violet-600/5 dark:to-violet-600/10 blur-[130px]"></div>
            <div class="absolute top-1/3 -right-40 h-[600px] w-[600px] rounded-full bg-gradient-to-bl from-emerald-600/10 dark:from-emerald-600/20 to-teal-600/5 dark:to-teal-600/10 blur-[140px]"></div>
            <div class="absolute -bottom-40 left-1/3 h-[550px] w-[550px] rounded-full bg-gradient-to-tr from-purple-600/10 dark:from-purple-600/20 to-pink-600/5 dark:to-pink-600/10 blur-[140px]"></div>
        </div>

        <!-- Navigation Bar -->
        <header class="relative z-10 border-b border-slate-200 dark:border-slate-800/60 bg-white/70 dark:bg-slate-950/70 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <!-- Brand Logo -->
                <AppLogo />

                <!-- Navigation Controls -->
                <nav class="flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <ThemeToggle />

                    <!-- Language Switcher Dropdown -->
                    <LanguageSwitcher />

                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                    >
                        <LayoutDashboard class="h-4 w-4" />
                        <span>{{ t('Dashboard') }}</span>
                    </Link>

                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-900/80 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 transition hover:border-slate-400 dark:hover:border-slate-600 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white"
                        >
                            <LogIn class="h-4 w-4" />
                            <span>{{ t('Log in') }}</span>
                        </Link>

                        <Link
                            :href="register()"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                        >
                            <span>{{ t('Register') }}</span>
                            <ArrowRight class="h-4 w-4 rtl:rotate-180" />
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Main Hero Section -->
        <main class="relative z-10">
            <section class="mx-auto max-w-7xl px-6 pt-16 pb-24 text-center lg:pt-24 lg:pb-32">
                <!-- Floating Pill Badge -->
                <div class="inline-flex items-center gap-2 rounded-full border border-indigo-500/30 dark:border-indigo-500/30 bg-indigo-500/10 dark:bg-indigo-500/10 px-4 py-1.5 text-xs font-semibold text-indigo-700 dark:text-indigo-300 shadow-sm backdrop-blur-md">
                    <Sparkles class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400 animate-pulse" />
                    <span>Laravel 13 • Vue 3 • Inertia v3 • Tailwind CSS v4</span>
                </div>

                <!-- Main Hero Headline -->
                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-6xl lg:text-7xl leading-none">
                    {{ t('Build Enterprise SaaS Apps') }} <br class="hidden sm:inline" />
                    <span class="bg-gradient-to-r from-indigo-400 via-purple-300 to-pink-400 bg-clip-text text-transparent">
                        {{ t('10x Faster & Scalable') }}
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="mx-auto mt-6 max-w-3xl text-base text-slate-600 dark:text-slate-400 sm:text-lg leading-relaxed">
                    {{ t('An opinionated, production-ready dashboard kit equipped with Spatie Roles & Permissions, User Impersonation, Bulk Table Operations, API Access Tokens, System Settings, Activity Audit Trail, and Full RTL Multi-Language Infrastructure.') }}
                </p>

                <!-- Hero Action Buttons -->
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                    <Link
                        :href="$page.props.auth.user ? dashboard() : login()"
                        class="inline-flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 px-8 py-4 text-base font-bold text-white shadow-xl shadow-indigo-500/30 transition hover:scale-105 hover:shadow-indigo-500/50"
                    >
                        <span>{{ $page.props.auth.user ? t('Go to Dashboard') : t('Explore Demo Account') }}</span>
                        <ArrowRight class="h-5 w-5 rtl:rotate-180" />
                    </Link>

                    <a
                        href="https://github.com/mr-creative-hmh/laravel-vue-creative-starter-dashboard-kit"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center gap-2.5 rounded-2xl border border-slate-300 dark:border-slate-800 bg-slate-100 dark:bg-slate-900/90 px-8 py-4 text-base font-semibold text-slate-700 dark:text-slate-300 backdrop-blur-md transition hover:border-slate-400 dark:hover:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white"
                    >
                        <Code2 class="h-5 w-5 text-slate-500 dark:text-slate-400" />
                        <span>GitHub Repository</span>
                    </a>
                </div>

                <!-- Live Feature Stats Pill Bar -->
                <div class="mt-16 grid grid-cols-2 gap-4 rounded-2xl border border-slate-200 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-900/40 p-6 backdrop-blur-xl sm:grid-cols-4 sm:gap-8">
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">71+</p>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-400 mt-1">{{ t('Automated Pest Tests') }}</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">100%</p>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-400 mt-1">{{ t('RTL & i18n Ready') }}</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">Sanctum</p>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-400 mt-1">{{ t('API Access Tokens') }}</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-purple-600 dark:text-purple-400">ApexCharts</p>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-400 mt-1">{{ t('Interactive Analytics') }}</p>
                    </div>
                </div>
            </section>

            <!-- Features Grid Section -->
            <section class="border-t border-slate-200 dark:border-slate-800/60 bg-slate-50 dark:bg-slate-950/50 py-24">
                <div class="mx-auto max-w-7xl px-6">
                    <div class="text-center">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">{{ t('Built for Enterprise Developers') }}</h2>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl">{{ t('Everything Included Out-of-the-Box') }}</p>
                    </div>

                    <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Feature 1: Impersonation -->
                        <div class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 backdrop-blur-xl transition hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-900">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/10 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition">
                                <Users class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ t('User Impersonation') }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ t('Super Admins can log in as any user with 1 click to debug issues. Features a persistent top banner to exit impersonation anytime.') }}
                            </p>
                        </div>

                        <!-- Feature 2: Roles & Permissions -->
                        <div class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 backdrop-blur-xl transition hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-900">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500/10 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition">
                                <Shield class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ t('Permission Matrix & Generators') }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ t('Spatie Authorization with visual permission assignment grid, plus CLI generator commands (permissions:generate, permissions:sync) and Web UI generator.') }}
                            </p>
                        </div>

                        <!-- Feature 3: Bulk Table Actions -->
                        <div class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 backdrop-blur-xl transition hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-900">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/10 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition">
                                <Zap class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ t('Bulk Data Operations') }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ t('Multi-select checkboxes built directly into DataTable.vue. Perform bulk deletes, activate/disable actions, and custom batch operations.') }}
                            </p>
                        </div>

                        <!-- Feature 4: API Access Tokens -->
                        <div class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 backdrop-blur-xl transition hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-900">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/10 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition">
                                <KeyRound class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ t('Sanctum API Tokens') }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ t('Personal access token manager allowing users to issue third-party API keys with custom permissions and token revocation controls.') }}
                            </p>
                        </div>

                        <!-- Feature 5: Activity Audit Logs -->
                        <div class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 backdrop-blur-xl transition hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-900">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/10 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 group-hover:bg-rose-500 group-hover:text-white transition">
                                <Activity class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ t('Activity Logs & 1-Click Undo') }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ t('Full Spatie Activitylog history with event filtering, side-by-side JSON diff inspector, and 1-click action reversal capabilities.') }}
                            </p>
                        </div>

                        <!-- Feature 6: i18n & RTL -->
                        <div class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 backdrop-blur-xl transition hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-900">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500/10 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition">
                                <Globe class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ t('i18n & Full RTL Support') }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ t('Complete English (en) & Arabic (ar) translations with automatic text direction switching (dir="rtl" / dir="ltr").') }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer Section -->
        <footer class="relative z-10 border-t border-slate-200 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-950 py-12">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-6 sm:flex-row text-xs text-slate-600 dark:text-slate-400">
                <div class="flex items-center gap-3">
                    <AppLogoIcon class="h-6 w-6 drop-shadow-[0_0_8px_rgba(99,102,241,0.5)]" />
                    <div>
                        <p class="font-bold text-slate-900 dark:text-slate-200">{{ t('Creative Starter Dashboard Kit') }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-500 mt-0.5">© {{ new Date().getFullYear() }} {{ t('All rights reserved.') }}</p>
                    </div>
                </div>

                <!-- Custom Creator Credit -->
                <div class="flex items-center gap-2 rounded-xl border border-indigo-500/20 bg-indigo-500/10 px-4 py-2 text-xs font-semibold text-indigo-600 dark:text-indigo-300">
                    <Sparkles class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" />
                    <span>{{ t('Created by Eng. Hasan Mohammad Hasan') }}</span>
                </div>

                <!-- Tech Links -->
                <div class="flex items-center gap-6 text-slate-400">
                    <a href="https://laravel.com" target="_blank" class="hover:text-white transition">Laravel 13</a>
                    <a href="https://vuejs.org" target="_blank" class="hover:text-white transition">Vue.js 3</a>
                    <a href="https://inertiajs.com" target="_blank" class="hover:text-white transition">Inertia v3</a>
                </div>
            </div>
        </footer>
    </div>
</template>
