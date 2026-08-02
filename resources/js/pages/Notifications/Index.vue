<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Bell, Check, CheckCheck, Inbox, Trash2 } from '@lucide/vue';
import DataTablePagination, { type PaginationMeta } from '@/components/ui/datatable/DataTablePagination.vue';
import { useTrans } from '@/composables/useTrans';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

export type NotificationItem = {
    id: string;
    type: string;
    data: {
        title?: string;
        message?: string;
        action_url?: string;
        [key: string]: any;
    };
    read_at: string | null;
    created_at: string;
    created_at_human: string;
};

const props = defineProps<{
    notifications: {
        data: NotificationItem[];
        meta?: PaginationMeta;
    } & PaginationMeta;
}>();

const { t } = useTrans();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Notifications', href: '/notifications' },
];

function markRead(id: string) {
    router.post(`/notifications/${id}/read`, {}, { preserveScroll: true });
}

function markAllRead() {
    router.post('/notifications/mark-all-read', {}, { preserveScroll: true });
}

function deleteNotification(id: string) {
    router.delete(`/notifications/${id}`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Notifications Center" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ t('Notifications Center') }}
                    </h1>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        {{ t('Review system notifications, automated alerts, and background job status updates.') }}
                    </p>
                </div>
                <button
                    type="button"
                    @click="markAllRead"
                    class="inline-flex h-9 items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-neutral-200 bg-white px-4 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                >
                    <CheckCheck class="h-4 w-4 shrink-0 text-neutral-500 dark:text-neutral-400" />
                    <span>{{ t('Mark All as Read') }}</span>
                </button>
            </div>

            <!-- Notifications List -->
            <div v-if="notifications.data && notifications.data.length > 0" class="space-y-3">
                <div
                    v-for="item in notifications.data"
                    :key="item.id"
                    :class="[
                        'flex items-start justify-between rounded-xl border p-4 transition',
                        !item.read_at
                            ? 'border-neutral-300 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900'
                            : 'border-neutral-200/80 bg-neutral-50/60 dark:border-neutral-800/80 dark:bg-neutral-900/40',
                    ]"
                >
                    <div class="flex items-start gap-3.5">
                        <div
                            :class="[
                                'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-neutral-900 dark:text-neutral-100',
                                !item.read_at ? 'bg-neutral-900 text-white dark:bg-neutral-100 dark:text-neutral-900' : 'bg-neutral-200 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400',
                            ]"
                        >
                            <Bell class="h-4 w-4" />
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                                    {{ item.data.title || item.type }}
                                </h4>
                                <span
                                    v-if="!item.read_at"
                                    class="inline-block rounded-full bg-red-600 px-2 py-0.2 text-[10px] font-bold text-white"
                                >
                                    New
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-neutral-600 dark:text-neutral-400">
                                {{ item.data.message || 'Notification payload received' }}
                            </p>
                            <p class="mt-2 text-[11px] text-neutral-400 dark:text-neutral-500">
                                {{ item.created_at_human }}
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1.5">
                        <button
                            v-if="!item.read_at"
                            type="button"
                            @click="markRead(item.id)"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 bg-white text-neutral-600 hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800"
                            title="Mark as read"
                        >
                            <Check class="h-3.5 w-3.5" />
                        </button>
                        <button
                            type="button"
                            @click="deleteNotification(item.id)"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 bg-white text-neutral-400 hover:bg-red-50 hover:text-red-600 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-500 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                            title="Delete notification"
                        >
                            <Trash2 class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="rounded-xl border border-neutral-200 bg-white p-12 text-center dark:border-neutral-800 dark:bg-neutral-900">
                <Inbox class="mx-auto h-12 w-12 text-neutral-300 dark:text-neutral-600" />
                <h3 class="mt-3 text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ t('No notifications') }}</h3>
                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                    You're all caught up! System alerts will appear here when triggered.
                </p>
            </div>

            <!-- Pagination (Only when notifications exist) -->
            <DataTablePagination v-if="notifications.data && notifications.data.length > 0 && (notifications.meta || notifications.last_page)" :meta="notifications.meta || notifications" />
        </div>
    </AppLayout>
</template>
