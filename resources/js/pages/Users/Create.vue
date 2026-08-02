<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import UserAvatarUpload from '@/components/user/UserAvatarUpload.vue';
import { useTrans } from '@/composables/useTrans';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    roles: string[];
}>();

const { t } = useTrans();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'User Management', href: '/users' },
    { title: 'Add User', href: '/users/create' },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    avatar: null as File | null,
    is_active: true,
    roles: ['User'] as string[],
});

function submit() {
    form.post('/users', {
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="Add New User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-3xl px-4 py-6 md:px-8">
            <!-- Header -->
            <div class="mb-6">
                <Link
                    href="/users"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100"
                >
                    <ArrowLeft class="h-3.5 w-3.5 rtl:rotate-180" />
                    <span>{{ t('Back to Users') }}</span>
                </Link>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    {{ t('Add New User') }}
                </h1>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <!-- Avatar Upload -->
                    <div class="mb-6">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-500 mb-2">
                            {{ t('Profile Avatar') }}
                        </label>
                        <UserAvatarUpload v-model="form.avatar" />
                        <p v-if="form.errors.avatar" class="mt-1 text-xs text-red-600 dark:text-red-400">
                            {{ form.errors.avatar }}
                        </p>
                    </div>

                    <!-- Input Fields -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-medium text-neutral-900 dark:text-neutral-100">
                                {{ t('Full Name') }}
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="John Doe"
                                class="mt-1 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                                required
                            />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-600 dark:text-red-400">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-medium text-neutral-900 dark:text-neutral-100">
                                {{ t('Email Address') }}
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="john@example.com"
                                class="mt-1 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                                required
                            />
                            <p v-if="form.errors.email" class="mt-1 text-xs text-red-600 dark:text-red-400">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Password -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-neutral-900 dark:text-neutral-100">
                                {{ t('Temporary Password') }}
                            </label>
                            <input
                                v-model="form.password"
                                type="password"
                                placeholder="••••••••"
                                class="mt-1 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                                required
                            />
                            <p v-if="form.errors.password" class="mt-1 text-xs text-red-600 dark:text-red-400">
                                {{ form.errors.password }}
                            </p>
                        </div>
                    </div>

                    <!-- Roles Selection -->
                    <div class="mt-6 border-t border-neutral-100 pt-5 dark:border-neutral-800">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-500 mb-2">
                            {{ t('Assign Roles') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-3">
                            <label
                                v-for="r in roles"
                                :key="r"
                                class="flex items-center gap-2.5 rounded-lg border border-neutral-200/60 bg-white p-2.5 text-xs font-medium text-neutral-700 transition hover:bg-neutral-100/60 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800/60 cursor-pointer select-none"
                            >
                                <input
                                    type="checkbox"
                                    :value="r"
                                    v-model="form.roles"
                                    class="h-4 w-4 shrink-0 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-800"
                                />
                                <span>{{ r }}</span>
                            </label>
                        </div>
                        <p v-if="form.errors.roles" class="mt-1 text-xs text-red-600 dark:text-red-400">
                            {{ form.errors.roles }}
                        </p>
                    </div>

                    <!-- Account Status Toggle -->
                    <div class="mt-6 border-t border-neutral-100 pt-5 dark:border-neutral-800">
                        <label class="flex items-center justify-between cursor-pointer select-none">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                                    {{ t('Account Status') }}
                                </span>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ t('Active users can log into the system.') }}
                                </p>
                            </div>
                            <input
                                type="checkbox"
                                v-model="form.is_active"
                                class="h-5 w-5 shrink-0 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-800"
                            />
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3">
                    <Link
                        href="/users"
                        class="inline-flex h-9 items-center justify-center rounded-lg border border-neutral-200 bg-white px-4 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                    >
                        {{ t('Cancel') }}
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex h-9 items-center justify-center rounded-lg bg-neutral-900 px-5 text-xs font-semibold text-white transition hover:bg-neutral-800 disabled:opacity-50 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-200"
                    >
                        {{ t('Add User') }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
