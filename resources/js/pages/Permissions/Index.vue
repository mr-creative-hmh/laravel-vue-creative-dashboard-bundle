<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus, Shield, ShieldCheck, Sparkles, X } from '@lucide/vue';
import { ref } from 'vue';
import { useAuth } from '@/composables/useAuth';
import { useTrans } from '@/composables/useTrans';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    groupedPermissions: Record<string, Array<{ id: number; name: string; guard_name: string; created_at?: string }>>;
}>();

const { t } = useTrans();
const { can } = useAuth();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Roles & Permissions', href: '/roles' },
    { title: 'Permissions Directory', href: '/permissions' },
];

const showGenerateModal = ref(false);

const generateForm = useForm({
    module: '',
});

function submitGenerate() {
    generateForm.post('/permissions/generate-module', {
        onSuccess: () => {
            generateForm.reset();
            showGenerateModal.value = false;
        },
    });
}
</script>

<template>
    <Head title="Permissions Directory" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 md:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ t('Permissions Directory') }}
                    </h1>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        {{ t('View system permissions categorized by application module.') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        v-if="can('permissions.manage')"
                        type="button"
                        @click="showGenerateModal = true"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-neutral-900 px-4 text-xs font-semibold text-white transition hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200"
                    >
                        <Sparkles class="h-4 w-4" />
                        <span>{{ t('Generate Module Suite') }}</span>
                    </button>
                    <Link
                        href="/roles"
                        class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-4 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                    >
                        <ArrowLeft class="h-4 w-4 rtl:rotate-180" />
                        <span>{{ t('Back to Roles') }}</span>
                    </Link>
                </div>
            </div>

            <!-- Module Groups Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="(perms, moduleName) in groupedPermissions"
                    :key="moduleName"
                    class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                >
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-800">
                        <div class="flex items-center gap-2">
                            <ShieldCheck class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                            <h3 class="text-sm font-semibold capitalize text-neutral-900 dark:text-neutral-100">
                                {{ moduleName }} {{ t('Module') }}
                            </h3>
                        </div>
                        <span class="rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400">
                            {{ perms.length }} {{ t('Permissions') }}
                        </span>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div
                            v-for="perm in perms"
                            :key="perm.id"
                            class="flex items-center justify-between rounded-lg border border-neutral-100 bg-neutral-50/60 p-2.5 text-xs dark:border-neutral-800/80 dark:bg-neutral-800/40"
                        >
                            <span class="font-mono font-medium text-neutral-800 dark:text-neutral-200">{{ perm.name }}</span>
                            <span class="text-[10px] text-neutral-400 dark:text-neutral-500 uppercase">{{ perm.guard_name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generate Module Permissions Modal -->
            <div
                v-if="showGenerateModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
            >
                <div class="w-full max-w-md rounded-xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-800">
                        <div class="flex items-center gap-2">
                            <Sparkles class="h-5 w-5 text-amber-500" />
                            <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">
                                {{ t('Generate Module Suite') }}
                            </h3>
                        </div>
                        <button
                            type="button"
                            @click="showGenerateModal = false"
                            class="rounded p-1 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>

                    <form @submit.prevent="submitGenerate" class="mt-4 space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-neutral-900 dark:text-neutral-100">
                                {{ t('Module Name') }}
                            </label>
                            <input
                                v-model="generateForm.module"
                                type="text"
                                placeholder="e.g. products, orders, invoices"
                                class="mt-1 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                                required
                            />
                            <p class="mt-1 text-[11px] text-neutral-500 dark:text-neutral-400">
                                {{ t('This will auto-generate view, create, edit, delete, and export permissions.') }}
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-neutral-100 dark:border-neutral-800">
                            <button
                                type="button"
                                @click="showGenerateModal = false"
                                class="inline-flex h-9 items-center justify-center rounded-lg border border-neutral-200 bg-white px-4 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                            >
                                {{ t('Cancel') }}
                            </button>
                            <button
                                type="submit"
                                :disabled="generateForm.processing"
                                class="inline-flex h-9 items-center justify-center rounded-lg bg-neutral-900 px-5 text-xs font-semibold text-white transition hover:bg-neutral-800 disabled:opacity-50 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200"
                            >
                                {{ t('Generate') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
