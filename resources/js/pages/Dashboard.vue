<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    ArrowUpRight,
    Download,
    Plus,
    Shield,
    UserCheck,
    Users,
} from '@lucide/vue';
import RoleDistributionChart from '@/components/charts/RoleDistributionChart.vue';
import UserGrowthChart from '@/components/charts/UserGrowthChart.vue';
import UserInfo from '@/components/user/UserInfo.vue';
import { useAuth } from '@/composables/useAuth';
import { useTrans } from '@/composables/useTrans';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    stats: {
        total_users: number;
        active_users: number;
        total_roles: number;
        total_logs: number;
    };
    charts: {
        user_growth: {
            labels: string[];
            series: number[];
        };
        role_distribution: {
            labels: string[];
            series: number[];
        };
    };
    recentLogs: Array<{
        id: number;
        description: string;
        event: string | null;
        causer: string;
        created_at_human: string;
    }>;
    recentUsers: Array<{
        id: number;
        name: string;
        email: string;
        avatar_url?: string | null;
        roles: string[];
        created_at_human: string;
    }>;
}>();

const { can } = useAuth();
const { t } = useTrans();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

function exportExcel() {
    window.location.href = '/exports/users';
}
</script>

<template>
    <Head title="Dashboard Overview" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8 space-y-8">
            <!-- Welcome Header & Quick Actions -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ t('System Dashboard Overview') }}
                    </h1>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        {{ t("Welcome back! Here is what's happening across your platform today.") }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        v-if="can('users.export')"
                        type="button"
                        @click="exportExcel"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-3.5 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                    >
                        <Download class="h-4 w-4" />
                        <span>{{ t('Export Report') }}</span>
                    </button>
                    <Link
                        v-if="can('users.create')"
                        href="/users/create"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-neutral-900 px-4 text-xs font-semibold text-white transition hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200"
                    >
                        <Plus class="h-4 w-4" />
                        <span>{{ t('Add User') }}</span>
                    </Link>
                </div>
            </div>

            <!-- KPI Stat Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Users Card -->
                <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-neutral-500">{{ t('Total Users') }}</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-neutral-100 text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100">
                            <Users class="h-4 w-4" />
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                            {{ stats.total_users }}
                        </span>
                        <span class="inline-flex items-center text-xs font-medium text-emerald-600 dark:text-emerald-400">
                            <ArrowUpRight class="h-3.5 w-3.5 me-0.5" />
                            {{ t('Registered') }}
                        </span>
                    </div>
                </div>

                <!-- Active Users Card -->
                <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-neutral-500">{{ t('Active Accounts') }}</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                            <UserCheck class="h-4 w-4" />
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                            {{ stats.active_users }}
                        </span>
                        <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                            {{ Math.round((stats.active_users / (stats.total_users || 1)) * 100) }}% {{ t('ratio') }}
                        </span>
                    </div>
                </div>

                <!-- Total Roles Card -->
                <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-neutral-500">{{ t('Configured Roles') }}</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-neutral-100 text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100">
                            <Shield class="h-4 w-4" />
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                            {{ stats.total_roles }}
                        </span>
                        <Link v-if="can('roles.view')" href="/roles" class="text-xs font-medium text-neutral-600 hover:underline dark:text-neutral-400">
                            {{ t('Manage →') }}
                        </Link>
                    </div>
                </div>

                <!-- Total Logs Card -->
                <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-neutral-500">{{ t('Activity Logs') }}</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-neutral-100 text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100">
                            <Activity class="h-4 w-4" />
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                            {{ stats.total_logs }}
                        </span>
                        <Link v-if="can('logs.view')" href="/logs" class="text-xs font-medium text-neutral-600 hover:underline dark:text-neutral-400">
                            {{ t('View Logs →') }}
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- User Growth Trend Chart -->
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900 lg:col-span-2">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-neutral-900 dark:text-neutral-100">
                                {{ t('User Registration Growth') }}
                            </h3>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                {{ t('Monthly new user accounts registered over the past 6 months.') }}
                            </p>
                        </div>
                    </div>
                    <UserGrowthChart
                        :labels="charts.user_growth.labels"
                        :series="charts.user_growth.series"
                    />
                </div>

                <!-- Role Distribution Donut Chart -->
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-neutral-900 dark:text-neutral-100">
                            {{ t('Role Distribution') }}
                        </h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                            {{ t('User access level breakdown.') }}
                        </p>
                    </div>
                    <RoleDistributionChart
                        :labels="charts.role_distribution.labels"
                        :series="charts.role_distribution.series"
                    />
                </div>
            </div>

            <!-- Bottom Widgets Row -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Recent Activities Widget -->
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-neutral-900 dark:text-neutral-100">
                            {{ t('Recent Audit Logs') }}
                        </h3>
                        <Link v-if="can('logs.view')" href="/logs" class="text-xs font-semibold text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100">
                            {{ t('View All Logs →') }}
                        </Link>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="log in recentLogs"
                            :key="log.id"
                            class="flex items-center justify-between rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 text-xs dark:border-neutral-800/80 dark:bg-neutral-800/40"
                        >
                            <div class="flex items-center gap-2.5">
                                <span
                                    :class="[
                                        'inline-block h-2 w-2 shrink-0 rounded-full',
                                        log.event === 'created' ? 'bg-emerald-500' : log.event === 'updated' ? 'bg-amber-500' : 'bg-rose-500',
                                    ]"
                                ></span>
                                <div>
                                    <p class="font-medium text-neutral-900 dark:text-neutral-100">{{ log.description }}</p>
                                    <p class="text-[10px] text-neutral-400 dark:text-neutral-500">{{ t('By') }} {{ log.causer }}</p>
                                </div>
                            </div>
                            <span class="text-[11px] text-neutral-400 dark:text-neutral-500 me-1">{{ log.created_at_human }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Registered Users Widget -->
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-neutral-900 dark:text-neutral-100">
                            {{ t('Recently Joined Users') }}
                        </h3>
                        <Link v-if="can('users.view')" href="/users" class="text-xs font-semibold text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100">
                            {{ t('Manage Users →') }}
                        </Link>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="user in recentUsers"
                            :key="user.id"
                            class="flex items-center justify-between gap-2 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 text-xs dark:border-neutral-800/80 dark:bg-neutral-800/40"
                        >
                            <UserInfo :user="user" :show-email="true" />
                            <span class="text-[11px] text-neutral-400 dark:text-neutral-500 shrink-0 me-1">{{ user.created_at_human }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
