<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useEventListener } from '@vueuse/core';
import {
    Activity,
    Bell,
    Check,
    Globe,
    LayoutDashboard,
    Moon,
    Plus,
    Search,
    Shield,
    Sun,
    User,
    Users,
} from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import { useAppearance } from '@/composables/useAppearance';
import { useTrans } from '@/composables/useTrans';

const props = defineProps<{
    open: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
}>();

const search = ref('');
const selectedIndex = ref(0);
const { appearance, updateAppearance } = useAppearance();
const { t } = useTrans();

type PaletteItem = {
    id: string;
    title: string;
    category: string;
    icon: any;
    action: () => void;
};

const items = computed<PaletteItem[]>(() => [
    {
        id: 'nav-dashboard',
        title: t('Dashboard'),
        category: 'Navigation',
        icon: LayoutDashboard,
        action: () => navigateTo('/dashboard'),
    },
    {
        id: 'nav-users',
        title: t('User Management'),
        category: 'Navigation',
        icon: Users,
        action: () => navigateTo('/users'),
    },
    {
        id: 'nav-roles',
        title: t('Roles & Permissions'),
        category: 'Navigation',
        icon: Shield,
        action: () => navigateTo('/roles'),
    },
    {
        id: 'nav-logs',
        title: t('Activity Logs'),
        category: 'Navigation',
        icon: Activity,
        action: () => navigateTo('/logs'),
    },
    {
        id: 'nav-notifications',
        title: t('Notifications'),
        category: 'Navigation',
        icon: Bell,
        action: () => navigateTo('/notifications'),
    },
    {
        id: 'nav-profile',
        title: t('Profile'),
        category: 'Navigation',
        icon: User,
        action: () => navigateTo('/settings/profile'),
    },

    // Quick Actions
    {
        id: 'action-create-user',
        title: t('Add User'),
        category: 'Quick Actions',
        icon: Plus,
        action: () => navigateTo('/users/create'),
    },
    {
        id: 'action-create-role',
        title: t('Create Role'),
        category: 'Quick Actions',
        icon: Plus,
        action: () => navigateTo('/roles/create'),
    },
    {
        id: 'action-toggle-theme',
        title: `Switch Theme (${appearance.value === 'dark' ? 'Light Mode' : 'Dark Mode'})`,
        category: 'Quick Actions',
        icon: appearance.value === 'dark' ? Sun : Moon,
        action: () => {
            updateAppearance(appearance.value === 'dark' ? 'light' : 'dark');
            close();
        },
    },
]);

const filteredItems = computed(() => {
    if (!search.value.trim()) return items.value;
    const q = search.value.toLowerCase();
    return items.value.filter(
        (item) => item.title.toLowerCase().includes(q) || item.category.toLowerCase().includes(q)
    );
});

watch(filteredItems, () => {
    selectedIndex.value = 0;
});

function close() {
    emit('update:open', false);
    search.value = '';
}

function navigateTo(url: string) {
    close();
    router.visit(url);
}

function handleKeyDown(e: KeyboardEvent) {
    if (!props.open) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (filteredItems.value.length > 0) {
            selectedIndex.value = (selectedIndex.value + 1) % filteredItems.value.length;
        }
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (filteredItems.value.length > 0) {
            selectedIndex.value =
                (selectedIndex.value - 1 + filteredItems.value.length) % filteredItems.value.length;
        }
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (filteredItems.value[selectedIndex.value]) {
            filteredItems.value[selectedIndex.value].action();
        }
    } else if (e.key === 'Escape') {
        close();
    }
}

useEventListener('keydown', (e: KeyboardEvent) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        emit('update:open', !props.open);
    }
    handleKeyDown(e);
});
</script>

<template>
    <Teleport to="body">
        <div v-if="open" class="fixed inset-0 z-50 flex items-start justify-center pt-16 sm:pt-24">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-neutral-950/50 backdrop-blur-sm transition-opacity" @click="close"></div>

            <!-- Modal Content -->
            <div
                class="relative w-full max-w-xl overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-2xl transition-all dark:border-neutral-800 dark:bg-neutral-900"
            >
                <!-- Search Input -->
                <div class="flex items-center gap-3 border-b border-neutral-200 px-4 dark:border-neutral-800">
                    <Search class="h-5 w-5 shrink-0 text-neutral-400 dark:text-neutral-500" />
                    <input
                        v-model="search"
                        type="text"
                        :placeholder="t('Search...')"
                        class="h-14 w-full bg-transparent text-base text-neutral-900 placeholder:text-neutral-400 focus:outline-none dark:text-neutral-100 dark:placeholder:text-neutral-500"
                        autofocus
                    />
                    <kbd
                        class="hidden rounded bg-neutral-100 px-2 py-1 text-xs text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400 sm:inline-block"
                    >
                        ESC
                    </kbd>
                </div>

                <!-- Items List -->
                <div class="max-h-96 overflow-y-auto p-2">
                    <div v-if="filteredItems.length === 0" class="p-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                        No results found for "{{ search }}"
                    </div>

                    <div v-else class="space-y-1">
                        <button
                            v-for="(item, index) in filteredItems"
                            :key="item.id"
                            type="button"
                            @click="item.action"
                            @mouseenter="selectedIndex = index"
                            :class="[
                                'flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-sm transition-colors',
                                selectedIndex === index
                                    ? 'bg-neutral-100 text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100'
                                    : 'text-neutral-600 hover:bg-neutral-50 dark:text-neutral-400 dark:hover:bg-neutral-800/60',
                            ]"
                        >
                            <div class="flex items-center gap-3">
                                <component :is="item.icon" class="h-4 w-4 shrink-0 text-neutral-500 dark:text-neutral-400" />
                                <span class="font-medium">{{ item.title }}</span>
                            </div>
                            <span class="text-xs text-neutral-400 dark:text-neutral-500">{{ item.category }}</span>
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between border-t border-neutral-200 bg-neutral-50 px-4 py-2 text-xs text-neutral-500 dark:border-neutral-800 dark:bg-neutral-900/50 dark:text-neutral-400">
                    <div class="flex items-center gap-3">
                        <span>Navigate <kbd class="font-semibold">↑↓</kbd></span>
                        <span>Select <kbd class="font-semibold">↵</kbd></span>
                    </div>
                    <div>Press <kbd class="font-semibold">Ctrl+K</kbd></div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
