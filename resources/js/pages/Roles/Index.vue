<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit2, Plus, Shield, Trash2, Users } from '@lucide/vue';
import { useAuth } from '@/composables/useAuth';
import { useTrans } from '@/composables/useTrans';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { can } = useAuth();
const { t } = useTrans();

export type RoleItem = {
    id: number;
    name: string;
    users_count: number;
    permissions: Array<{ id: number; name: string }>;
};

const props = defineProps<{
    roles: RoleItem[];
    groupedPermissions: Record<string, Array<{ id: number; name: string }>>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Roles & Permissions', href: '/roles' },
];

function deleteRole(role: RoleItem) {
    if (role.name === 'Super Admin') return;
    if (confirm(`Are you sure you want to delete the role "${role.name}"?`)) {
        router.delete(`/roles/${role.id}`);
    }
}
</script>

<template>
    <Head title="Roles & Permissions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ t('Roles & Permissions') }}
                    </h1>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        {{ t('Manage user roles, access levels, and security permission assignments.') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        v-if="can('permissions.view')"
                        href="/permissions"
                        class="inline-flex h-9 items-center justify-center rounded-lg border border-neutral-200 bg-white px-4 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                    >
                        {{ t('View All Permissions') }}
                    </Link>
                    <Link
                        v-if="can('roles.create')"
                        href="/roles/create"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-neutral-900 px-4 text-xs font-semibold text-white transition hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200"
                    >
                        <Plus class="h-4 w-4" />
                        <span>{{ t('Create Role') }}</span>
                    </Link>
                </div>
            </div>

            <!-- Role Cards Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="role in roles"
                    :key="role.id"
                    class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900"
                >
                    <div>
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-neutral-100 text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100">
                                    <Shield class="h-5 w-5" />
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-neutral-900 dark:text-neutral-100">
                                        {{ role.name }}
                                    </h3>
                                    <div class="mt-0.5 flex items-center gap-1.5 text-xs text-neutral-500 dark:text-neutral-400">
                                        <Users class="h-3.5 w-3.5 shrink-0" />
                                        <span>{{ role.users_count }} {{ t('Assigned user(s)') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Preview -->
                        <div class="mt-5 border-t border-neutral-100 pt-4 dark:border-neutral-800">
                            <p class="text-xs font-medium text-neutral-400 dark:text-neutral-500 mb-2">
                                {{ t('Permissions') }} ({{ role.permissions.length }}):
                            </p>
                            <div class="flex flex-wrap gap-1.5 max-h-28 overflow-y-auto">
                                <span
                                    v-for="perm in role.permissions.slice(0, 6)"
                                    :key="perm.id"
                                    class="inline-block rounded-md bg-neutral-100 px-2 py-0.5 text-xs font-medium text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                >
                                    {{ perm.name }}
                                </span>
                                <span
                                    v-if="role.permissions.length > 6"
                                    class="inline-block rounded-md bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400"
                                >
                                    +{{ role.permissions.length - 6 }} {{ t('more') }}
                                </span>
                                <span
                                    v-if="role.permissions.length === 0"
                                    class="text-xs italic text-neutral-400 dark:text-neutral-500"
                                >
                                    {{ t('No permissions assigned') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions Footer -->
                    <div class="mt-6 flex items-center justify-end gap-2 border-t border-neutral-100 pt-4 dark:border-neutral-800">
                        <Link
                            v-if="can('roles.edit')"
                            :href="`/roles/${role.id}/edit`"
                            class="inline-flex h-8 items-center gap-1.5 rounded-md px-3 text-xs font-medium text-neutral-600 transition hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-800"
                        >
                            <Edit2 class="h-3.5 w-3.5" />
                            <span>{{ t('Edit') }}</span>
                        </Link>
                        <button
                            v-if="can('roles.delete') && role.name !== 'Super Admin'"
                            type="button"
                            @click="deleteRole(role)"
                            class="inline-flex h-8 items-center gap-1.5 rounded-md px-3 text-xs font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40"
                        >
                            <Trash2 class="h-3.5 w-3.5" />
                            <span>{{ t('Delete') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
