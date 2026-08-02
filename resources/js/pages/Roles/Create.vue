<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check, Shield } from '@lucide/vue';
import { useTrans } from '@/composables/useTrans';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    groupedPermissions: Record<string, Array<{ id: number; name: string }>>;
}>();

const { t } = useTrans();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Roles & Permissions', href: '/roles' },
    { title: 'Create Role', href: '/roles/create' },
];

const form = useForm({
    name: '',
    permissions: [] as string[],
});

function submit() {
    form.post('/roles');
}

function isGroupAllSelected(groupPerms: Array<{ id: number; name: string }>) {
    return groupPerms.every((p) => form.permissions.includes(p.name));
}

function toggleGroup(groupPerms: Array<{ id: number; name: string }>) {
    const allSelected = isGroupAllSelected(groupPerms);
    if (allSelected) {
        form.permissions = form.permissions.filter(
            (p) => !groupPerms.some((gp) => gp.name === p)
        );
    } else {
        const toAdd = groupPerms.map((gp) => gp.name).filter((p) => !form.permissions.includes(p));
        form.permissions.push(...toAdd);
    }
}
</script>

<template>
    <Head title="Create Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-4xl px-4 py-6 md:px-8">
            <!-- Back Header -->
            <div class="mb-6">
                <Link
                    href="/roles"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100"
                >
                    <ArrowLeft class="h-3.5 w-3.5 rtl:rotate-180" />
                    <span>{{ t('Back to Roles') }}</span>
                </Link>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    {{ t('Create New Role') }}
                </h1>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <!-- Role Name Field -->
                    <div class="max-w-md">
                        <label class="block text-sm font-medium text-neutral-900 dark:text-neutral-100">
                            {{ t('Role Name') }}
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="e.g. Content Moderator, Support Lead"
                            class="mt-1.5 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-400 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                            required
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-600 dark:text-red-400">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Permissions Matrix -->
                    <div class="mt-8">
                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                            {{ t('Assign Permissions') }}
                        </h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                            {{ t('Select the permissions to assign to this role.') }}
                        </p>

                        <div class="mt-4 space-y-6">
                            <div
                                v-for="(perms, groupName) in groupedPermissions"
                                :key="groupName"
                                class="rounded-lg border border-neutral-100 bg-neutral-50/50 p-4 dark:border-neutral-800/60 dark:bg-neutral-800/30"
                            >
                                <div class="flex items-center justify-between border-b border-neutral-200/60 pb-3 dark:border-neutral-800">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                                        {{ groupName }} {{ t('Module') }}
                                    </h4>
                                    <button
                                        type="button"
                                        @click="toggleGroup(perms)"
                                        class="text-xs font-semibold text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100"
                                    >
                                        {{ isGroupAllSelected(perms) ? t('Deselect All') : t('Select All') }}
                                    </button>
                                </div>

                                <div class="mt-3 grid grid-cols-1 gap-2.5 sm:grid-cols-2 md:grid-cols-3">
                                    <label
                                        v-for="perm in perms"
                                        :key="perm.id"
                                        class="flex items-center gap-2.5 rounded-lg border border-neutral-200/60 bg-white p-2.5 text-xs font-medium text-neutral-700 transition hover:bg-neutral-100/60 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800/60 cursor-pointer select-none"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="perm.name"
                                            v-model="form.permissions"
                                            class="h-4 w-4 shrink-0 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-800"
                                        />
                                        <span class="truncate">{{ perm.name }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3">
                    <Link
                        href="/roles"
                        class="inline-flex h-9 items-center justify-center rounded-lg border border-neutral-200 bg-white px-4 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                    >
                        {{ t('Cancel') }}
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex h-9 items-center justify-center rounded-lg bg-neutral-900 px-5 text-xs font-semibold text-white transition hover:bg-neutral-800 disabled:opacity-50 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200"
                    >
                        {{ t('Save Role') }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
