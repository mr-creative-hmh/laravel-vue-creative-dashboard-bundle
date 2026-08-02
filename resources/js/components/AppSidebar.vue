<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Activity, Bell, LayoutGrid, ShieldCheck, Users } from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useAuth } from '@/composables/useAuth';
import { useTrans } from '@/composables/useTrans';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const { can } = useAuth();
const { isRtl } = useTrans();

type CustomNavItem = NavItem & {
    permission?: string;
};

const allNavItems: CustomNavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'User Management',
        href: '/users',
        icon: Users,
        permission: 'users.view',
    },
    {
        title: 'Roles & Permissions',
        href: '/roles',
        icon: ShieldCheck,
        permission: 'roles.view',
    },
    {
        title: 'Activity Logs',
        href: '/logs',
        icon: Activity,
        permission: 'logs.view',
    },
    {
        title: 'Notifications',
        href: '/notifications',
        icon: Bell,
    },
];

const mainNavItems = computed(() => {
    return allNavItems.filter((item) => {
        if (!item.permission) return true;
        return can(item.permission);
    });
});

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" :side="isRtl ? 'right' : 'left'">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter v-if="footerNavItems.length > 0" :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
