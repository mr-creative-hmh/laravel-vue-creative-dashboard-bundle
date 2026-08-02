<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Search } from '@lucide/vue';
import { computed, ref } from 'vue';
import CommandPalette from '@/components/CommandPalette.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import NotificationDrawer from '@/components/notifications/NotificationDrawer.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import UserMenuContent from '@/components/user/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import { useTrans } from '@/composables/useTrans';
import type { BreadcrumbItem } from '@/types';
import type { SharedPageProps } from '@/types/auth';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    }
);

const page = usePage<SharedPageProps>();
const auth = computed(() => page.props.auth);
const commandPaletteOpen = ref(false);
const { t } = useTrans();
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between border-b border-sidebar-border/70 px-4 md:px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-14">
        <!-- Left Side: Sidebar Trigger & Breadcrumbs -->
        <div class="flex items-center gap-3">
            <SidebarTrigger class="shrink-0" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <div class="h-4 w-px bg-neutral-200 dark:bg-neutral-800"></div>
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <!-- Right Side: Search Palette, Theme Toggle, Language Switcher, Notifications, User Menu -->
        <div class="flex items-center gap-2.5">
            <!-- Search Command Palette Button -->
            <button type="button" @click="commandPaletteOpen = true"
                class="hidden md:flex items-center gap-2 rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs text-neutral-500 transition hover:border-neutral-300 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400 dark:hover:border-neutral-700">
                <Search class="h-3.5 w-3.5 shrink-0" />
                <span>{{ t('Search...') }}</span>
                <kbd
                    class="rounded bg-neutral-200 px-1.5 py-0.5 text-[10px] font-semibold text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300">Ctrl+K</kbd>
            </button>

            <!-- Theme Toggle -->
            <ThemeToggle />

            <!-- Language Switcher Dropdown -->
            <LanguageSwitcher />

            <!-- Notification Drawer -->
            <NotificationDrawer />

            <!-- User Menu Dropdown -->
            <DropdownMenu v-if="auth?.user">
                <DropdownMenuTrigger :as-child="true">
                    <Button variant="ghost" size="icon"
                        class="relative size-9 w-auto rounded-full p-0.5 focus-within:ring-2 focus-within:ring-primary">
                        <Avatar class="size-8 overflow-hidden rounded-full">
                            <AvatarImage v-if="auth.user.avatar_url || auth.user.avatar"
                                :src="auth.user.avatar_url || auth.user.avatar!" :alt="auth.user.name" />
                            <AvatarFallback
                                class="rounded-2xl bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white text-2xl">
                                {{ getInitials(auth.user.name) }} </AvatarFallback>
                        </Avatar>
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-56">
                    <UserMenuContent :user="auth.user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <!-- Command Palette Modal -->
        <CommandPalette v-model:open="commandPaletteOpen" />
    </header>
</template>
