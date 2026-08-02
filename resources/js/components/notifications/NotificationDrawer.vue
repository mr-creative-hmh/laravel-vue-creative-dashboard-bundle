<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { Bell, Check, CheckCheck, ExternalLink, Inbox } from '@lucide/vue';
import { onClickOutside } from '@vueuse/core';
import { computed, ref } from 'vue';
import { useTrans } from '@/composables/useTrans';
import type { NotificationItem, SharedPageProps } from '@/types/auth';

const isOpen = ref(false);
const popoverRef = ref<HTMLElement | null>(null);
const page = usePage<SharedPageProps>();
const { t, isRtl } = useTrans();

onClickOutside(popoverRef, () => {
    isOpen.value = false;
});

const unreadCount = computed(() => page.props.auth?.user?.unread_notifications_count || 0);
const recentNotifications = computed<NotificationItem[]>(
    () => page.props.auth?.user?.recent_notifications || []
);

function toggle() {
    isOpen.value = !isOpen.value;
}

function markAllAsRead() {
    router.post(
        '/notifications/mark-all-read',
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                isOpen.value = false;
            },
        }
    );
}

function markAsRead(id: string) {
    router.post(
        `/notifications/${id}/read`,
        {},
        {
            preserveScroll: true,
        }
    );
}
</script>

<template>
    <div ref="popoverRef" class="relative">
        <!-- Bell Trigger Button -->
        <button
            type="button"
            @click="toggle"
            class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg border border-neutral-200 bg-white text-neutral-600 transition hover:bg-neutral-100 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
            :title="t('Notifications')"
        >
            <Bell class="h-4 w-4 shrink-0" />
            <span
                v-if="unreadCount > 0"
                class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white shadow"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <!-- Popover Dropdown -->
        <div
            v-if="isOpen"
            :class="[
                'absolute mt-2 z-50 w-80 sm:w-96 rounded-xl border border-neutral-200 bg-white p-3 shadow-xl dark:border-neutral-800 dark:bg-neutral-900',
                isRtl ? 'left-0' : 'right-0',
            ]"
        >
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-neutral-100 pb-2.5 dark:border-neutral-800">
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-neutral-900 dark:text-neutral-100">
                        {{ t('Notifications') }}
                    </h4>
                    <span
                        v-if="unreadCount > 0"
                        class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-700 dark:bg-red-950/60 dark:text-red-300"
                    >
                        {{ unreadCount }} {{ t('unread') }}
                    </span>
                </div>

                <button
                    v-if="unreadCount > 0"
                    type="button"
                    @click="markAllAsRead"
                    class="inline-flex items-center gap-1 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100"
                >
                    <CheckCheck class="h-3.5 w-3.5 shrink-0" />
                    <span>{{ t('Mark all read') }}</span>
                </button>
            </div>

            <!-- List of Recent Notifications -->
            <div v-if="recentNotifications.length > 0" class="max-h-80 overflow-y-auto divide-y divide-neutral-100 dark:divide-neutral-800/60 my-1">
                <div
                    v-for="item in recentNotifications"
                    :key="item.id"
                    :class="[
                        'group relative flex items-start gap-3 p-2.5 transition rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800/50',
                        !item.read_at ? 'bg-neutral-50/70 dark:bg-neutral-800/30' : '',
                    ]"
                >
                    <!-- Read/Unread Indicator Dot -->
                    <span
                        :class="[
                            'mt-1.5 h-2 w-2 shrink-0 rounded-full',
                            !item.read_at ? 'bg-blue-600 dark:bg-blue-400 ring-4 ring-blue-100 dark:ring-blue-950' : 'bg-neutral-300 dark:bg-neutral-700',
                        ]"
                    />

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <p class="text-xs font-semibold text-neutral-900 truncate dark:text-neutral-100">
                                {{ item.data.title || t('Notification') }}
                            </p>
                            <span class="text-[10px] text-neutral-400 shrink-0">
                                {{ item.created_at_human }}
                            </span>
                        </div>

                        <p class="mt-0.5 text-xs text-neutral-600 line-clamp-2 dark:text-neutral-400">
                            {{ item.data.message || '' }}
                        </p>

                        <!-- Action Link -->
                        <div class="mt-1.5 flex items-center justify-between">
                            <a
                                v-if="item.data.action_url"
                                :href="String(item.data.action_url)"
                                class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 hover:underline dark:text-blue-400"
                            >
                                <span>{{ t('View Details') }}</span>
                                <ExternalLink class="h-3 w-3" />
                            </a>
                            <button
                                v-if="!item.read_at"
                                type="button"
                                @click="markAsRead(item.id)"
                                class="inline-flex items-center gap-1 text-[10px] text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-200 ms-auto"
                                title="Mark as read"
                            >
                                <Check class="h-3 w-3" />
                                <span>{{ t('Mark read') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-8 text-center text-xs text-neutral-500 dark:text-neutral-400">
                <Inbox class="mx-auto h-8 w-8 text-neutral-300 dark:text-neutral-600 mb-2" />
                <p>{{ t('No notifications') }}</p>
            </div>

            <!-- Footer Link -->
            <div class="border-t border-neutral-100 pt-2 text-center dark:border-neutral-800">
                <Link
                    href="/notifications"
                    @click="isOpen = false"
                    class="text-xs font-semibold text-neutral-700 hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-neutral-100"
                >
                    {{ t('View all notifications →') }}
                </Link>
            </div>
        </div>
    </div>
</template>
