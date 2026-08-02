<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Download, Edit2, Plus, Trash2, UserCheck, UserCheck as UserSwitch, UserX, X } from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import DataTable, { type Column } from '@/components/ui/datatable/DataTable.vue';
import DataTablePagination, { type PaginationMeta } from '@/components/ui/datatable/DataTablePagination.vue';
import DataTableSearch from '@/components/ui/datatable/DataTableSearch.vue';
import UserInfo from '@/components/user/UserInfo.vue';
import { useAuth } from '@/composables/useAuth';
import { useTrans } from '@/composables/useTrans';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { SharedPageProps, User } from '@/types/auth';

const { can } = useAuth();
const { t } = useTrans();
const page = usePage<SharedPageProps>();

export type UserItem = User & {
    avatar_url?: string | null;
    roles: string[];
    is_active: boolean;
};

const props = defineProps<{
    users: {
        data: UserItem[];
        meta?: PaginationMeta;
    } & PaginationMeta;
    roles: string[];
    filters: {
        search?: string;
        role?: string;
        status?: string;
        sort?: string;
        direction?: 'asc' | 'desc';
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'User Management', href: '/users' },
];

const search = ref(props.filters.search || '');
const roleFilter = ref(props.filters.role || '');
const statusFilter = ref(props.filters.status || '');
const sortField = ref(props.filters.sort || 'created_at');
const sortDirection = ref<'asc' | 'desc'>(props.filters.direction || 'desc');
const selectedIds = ref<Array<number>>([]);

const columns = computed<Column[]>(() => [
    { key: 'name', label: t('User'), sortable: true },
    { key: 'email', label: t('Email Address'), sortable: true },
    { key: 'roles', label: t('Assign Roles') },
    { key: 'is_active', label: t('Status'), sortable: true, align: 'center' },
    { key: 'created_at', label: t('Registered'), sortable: true },
    { key: 'actions', label: t('Actions'), align: 'right' },
]);

function updateFilters() {
    router.get(
        '/users',
        {
            search: search.value || undefined,
            role: roleFilter.value || undefined,
            status: statusFilter.value !== '' ? statusFilter.value : undefined,
            sort: sortField.value,
            direction: sortDirection.value,
        },
        { preserveState: true, replace: true }
    );
}

function handleSort(field: string) {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
    updateFilters();
}

watch([roleFilter, statusFilter], () => {
    updateFilters();
});

function deleteUser(user: UserItem) {
    if (confirm(`Are you sure you want to delete user "${user.name}"?`)) {
        router.delete(`/users/${user.id}`);
    }
}

function impersonateUser(user: UserItem) {
    if (confirm(`Impersonate user "${user.name}"?`)) {
        router.post(`/impersonate/${user.id}`);
    }
}

function executeBulkAction(action: 'activate' | 'disable' | 'delete') {
    if (selectedIds.value.length === 0) return;

    const actionText = action === 'delete' ? 'delete' : action === 'activate' ? 'activate' : 'disable';
    if (confirm(`Are you sure you want to ${actionText} ${selectedIds.value.length} selected user(s)?`)) {
        router.post(
            '/users/bulk-action',
            {
                action,
                ids: selectedIds.value,
            },
            {
                onSuccess: () => {
                    selectedIds.value = [];
                },
            }
        );
    }
}

function exportExcel() {
    window.location.href = '/exports/users';
}
</script>

<template>
    <Head title="User Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ t('User Management') }}
                    </h1>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        {{ t('Manage system accounts, assign roles, and control account activity.') }}
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
                        <span>{{ t('Export Excel') }}</span>
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

            <!-- Toolbar & Filters -->
            <div class="mb-4 flex flex-col items-stretch justify-between gap-3 sm:flex-row sm:items-center">
                <DataTableSearch
                    v-model="search"
                    :placeholder="t('Search...')"
                    @search="updateFilters"
                />

                <div class="flex items-center gap-2">
                    <!-- Status Filter -->
                    <select
                        v-model="statusFilter"
                        class="h-9 rounded-md border border-neutral-200 bg-white px-3 text-xs text-neutral-700 focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300"
                    >
                        <option value="">{{ t('All Statuses') }}</option>
                        <option value="1">{{ t('Active Only') }}</option>
                        <option value="0">{{ t('Inactive Only') }}</option>
                    </select>

                    <!-- Role Filter -->
                    <select
                        v-model="roleFilter"
                        class="h-9 rounded-md border border-neutral-200 bg-white px-3 text-xs text-neutral-700 focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300"
                    >
                        <option value="">{{ t('All Roles') }}</option>
                        <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
                    </select>
                </div>
            </div>

            <!-- Bulk Action Toolbar -->
            <div
                v-if="selectedIds.length > 0"
                class="mb-3 flex items-center justify-between rounded-lg border border-neutral-900 bg-neutral-900 px-4 py-2.5 text-xs text-white dark:border-neutral-800 dark:bg-neutral-800"
            >
                <div class="flex items-center gap-2">
                    <span class="font-bold">{{ selectedIds.length }}</span>
                    <span>{{ t('item(s) selected') }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        v-if="can('users.edit')"
                        type="button"
                        @click="executeBulkAction('activate')"
                        class="inline-flex items-center gap-1 rounded bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-emerald-500"
                    >
                        <UserCheck class="h-3.5 w-3.5" />
                        <span>{{ t('Activate') }}</span>
                    </button>
                    <button
                        v-if="can('users.edit')"
                        type="button"
                        @click="executeBulkAction('disable')"
                        class="inline-flex items-center gap-1 rounded bg-amber-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-amber-500"
                    >
                        <UserX class="h-3.5 w-3.5" />
                        <span>{{ t('Disable') }}</span>
                    </button>
                    <button
                        v-if="can('users.delete')"
                        type="button"
                        @click="executeBulkAction('delete')"
                        class="inline-flex items-center gap-1 rounded bg-rose-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-rose-500"
                    >
                        <Trash2 class="h-3.5 w-3.5" />
                        <span>{{ t('Delete') }}</span>
                    </button>
                    <button
                        type="button"
                        @click="selectedIds = []"
                        class="ml-2 rounded p-1 text-neutral-400 hover:text-white"
                        title="Clear Selection"
                    >
                        <X class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <!-- DataTable -->
            <DataTable
                selectable
                v-model:selected-keys="selectedIds"
                :columns="columns"
                :data="users.data"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                @sort="handleSort"
            >
                <template #name="{ row }">
                    <div class="flex items-center gap-3">
                        <UserInfo :user="row" />
                    </div>
                </template>

                <template #email="{ value }">
                    <span class="font-mono text-xs text-neutral-600 dark:text-neutral-400">{{ value }}</span>
                </template>

                <template #roles="{ value }">
                    <div class="flex flex-wrap gap-1">
                        <span
                            v-for="r in value"
                            :key="r"
                            class="inline-block rounded bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                        >
                            {{ r }}
                        </span>
                    </div>
                </template>

                <template #is_active="{ value }">
                    <span
                        :class="[
                            'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold',
                            value
                                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300'
                                : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300',
                        ]"
                    >
                        <UserCheck v-if="value" class="h-3 w-3" />
                        <UserX v-else class="h-3 w-3" />
                        <span>{{ value ? t('Active') : t('Disabled') }}</span>
                    </span>
                </template>

                <template #actions="{ row }">
                    <div class="flex items-center justify-end gap-1">
                        <button
                            v-if="can('users.edit') && row.id !== page.props.auth?.user?.id"
                            type="button"
                            @click="impersonateUser(row)"
                            class="rounded p-1.5 text-neutral-500 hover:bg-amber-50 hover:text-amber-600 dark:text-neutral-400 dark:hover:bg-amber-950/40 dark:hover:text-amber-400"
                            :title="t('Impersonate User')"
                        >
                            <UserSwitch class="h-4 w-4" />
                        </button>
                        <Link
                            v-if="can('users.edit')"
                            :href="`/users/${row.id}/edit`"
                            class="rounded p-1.5 text-neutral-500 hover:bg-neutral-100 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-100"
                            title="Edit User"
                        >
                            <Edit2 class="h-4 w-4" />
                        </Link>
                        <button
                            v-if="can('users.delete')"
                            type="button"
                            @click="deleteUser(row)"
                            class="rounded p-1.5 text-neutral-500 hover:bg-red-50 hover:text-red-600 dark:text-neutral-400 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                            title="Delete User"
                        >
                            <Trash2 class="h-4 w-4" />
                        </button>
                    </div>
                </template>
            </DataTable>

            <!-- Pagination -->
            <DataTablePagination v-if="users.meta || users.last_page" :meta="users.meta || users" />
        </div>
    </AppLayout>
</template>
