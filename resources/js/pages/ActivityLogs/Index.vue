<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Activity, Eye, Search, User } from '@lucide/vue';
import { ref, watch } from 'vue';
import ActivityLogDiffModal from '@/components/logs/ActivityLogDiffModal.vue';
import DataTable, { type Column } from '@/components/ui/datatable/DataTable.vue';
import DataTablePagination, { type PaginationMeta } from '@/components/ui/datatable/DataTablePagination.vue';
import DataTableSearch from '@/components/ui/datatable/DataTableSearch.vue';
import { useTrans } from '@/composables/useTrans';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

export type LogItem = {
    id: number;
    log_name: string | null;
    description: string;
    event: string | null;
    subject_type: string | null;
    subject_id: number | null;
    causer?: { id: number; name: string; email: string } | null;
    properties?: Record<string, any>;
    created_at: string;
    created_at_human: string;
};

const props = defineProps<{
    logs: {
        data: LogItem[];
        meta?: PaginationMeta;
    } & PaginationMeta;
    filters: {
        search?: string;
        event?: string;
    };
}>();

const { t } = useTrans();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Activity Logs', href: '/logs' },
];

const search = ref(props.filters.search || '');
const eventFilter = ref(props.filters.event || '');
const selectedLog = ref<LogItem | null>(null);
const diffModalOpen = ref(false);

const columns: Column[] = [
    { key: 'event', label: t('Status'), align: 'center' },
    { key: 'description', label: 'Description' },
    { key: 'causer', label: 'Performed By' },
    { key: 'subject_type', label: 'Target Module' },
    { key: 'created_at_human', label: 'Time' },
    { key: 'actions', label: t('Actions'), align: 'right' },
];

function updateFilters() {
    router.get(
        '/logs',
        {
            search: search.value || undefined,
            event: eventFilter.value || undefined,
        },
        { preserveState: true, replace: true }
    );
}

watch(eventFilter, () => {
    updateFilters();
});

function inspectLog(log: LogItem) {
    selectedLog.value = log;
    diffModalOpen.value = true;
}
</script>

<template>
    <Head title="Activity Audit Logs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ t('Activity Logs') }}
                    </h1>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        {{ t('Monitor all system operations, data modifications, and user action logs.') }}
                    </p>
                </div>
            </div>

            <!-- Toolbar & Filters -->
            <div class="mb-4 flex flex-col items-stretch justify-between gap-3 sm:flex-row sm:items-center">
                <DataTableSearch
                    v-model="search"
                    :placeholder="t('Search...')"
                    @search="updateFilters"
                />

                <div class="flex items-center gap-2">
                    <select
                        v-model="eventFilter"
                        class="h-9 rounded-md border border-neutral-200 bg-white px-3 text-xs text-neutral-700 focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300"
                    >
                        <option value="">All Events</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="deleted">Deleted</option>
                    </select>
                </div>
            </div>

            <!-- DataTable -->
            <DataTable :columns="columns" :data="logs.data">
                <template #event="{ value }">
                    <span
                        :class="[
                            'inline-block rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider',
                            value === 'created'
                                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300'
                                : value === 'updated'
                                ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300'
                                : value === 'deleted'
                                ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300'
                                : 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300',
                        ]"
                    >
                        {{ value || 'System' }}
                    </span>
                </template>

                <template #description="{ value }">
                    <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ value }}</span>
                </template>

                <template #causer="{ value }">
                    <div v-if="value" class="flex items-center gap-2">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-neutral-200 text-neutral-700 dark:bg-neutral-700 dark:text-neutral-200 text-[10px] font-bold">
                            {{ value.name ? value.name[0].toUpperCase() : 'U' }}
                        </div>
                        <div class="text-xs">
                            <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ value.name }}</span>
                        </div>
                    </div>
                    <span v-else class="text-xs italic text-neutral-400 dark:text-neutral-500">System Bot</span>
                </template>

                <template #subject_type="{ value }">
                    <span v-if="value" class="inline-block rounded bg-neutral-100 px-2 py-0.5 text-xs font-medium text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                        {{ value }}
                    </span>
                    <span v-else class="text-xs text-neutral-400">-</span>
                </template>

                <template #created_at_human="{ value, row }">
                    <span class="text-xs text-neutral-500 dark:text-neutral-400" :title="row.created_at">
                        {{ value }}
                    </span>
                </template>

                <template #actions="{ row }">
                    <div class="flex items-center justify-end">
                        <button
                            type="button"
                            @click="inspectLog(row)"
                            class="inline-flex h-8 items-center gap-1.5 rounded-md border border-neutral-200 bg-white px-2.5 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                        >
                            <Eye class="h-3.5 w-3.5" />
                            <span>{{ t('View Diff') }}</span>
                        </button>
                    </div>
                </template>
            </DataTable>

            <!-- Pagination -->
            <DataTablePagination v-if="logs.meta || logs.last_page" :meta="logs.meta || logs" />

            <!-- Diff Modal -->
            <ActivityLogDiffModal
                v-model:open="diffModalOpen"
                :log="selectedLog"
            />
        </div>
    </AppLayout>
</template>
