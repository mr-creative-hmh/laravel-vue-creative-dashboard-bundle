<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { useTrans } from '@/composables/useTrans';

const props = defineProps<{
    settings: {
        app_name: string;
        support_email: string;
        default_locale: string;
        maintenance_mode: boolean;
    };
}>();

const { t, locale, isRtl } = useTrans();

const form = useForm({
    app_name: props.settings.app_name,
    support_email: props.settings.support_email,
    default_locale: props.settings.default_locale,
    maintenance_mode: props.settings.maintenance_mode,
});

function submit() {
    form.put('/settings/system', {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="t('System Settings')" />

    <div class="space-y-6">
        <Heading
            :key="`system-${locale}`"
            :title="t('System Settings')"
            :description="t('Configure general system parameters, default language, and operational status.')"
        />

        <form @submit.prevent="submit" class="space-y-6">
            <!-- App Name -->
            <div>
                <label class="block text-xs font-medium text-neutral-900 dark:text-neutral-100">
                    {{ t('Application Name') }}
                </label>
                <input
                    v-model="form.app_name"
                    type="text"
                    class="mt-1 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                    required
                />
                <p v-if="form.errors.app_name" class="mt-1 text-xs text-red-600 dark:text-red-400">
                    {{ form.errors.app_name }}
                </p>
            </div>

            <!-- Support Email -->
            <div>
                <label class="block text-xs font-medium text-neutral-900 dark:text-neutral-100">
                    {{ t('Support Contact Email') }}
                </label>
                <input
                    v-model="form.support_email"
                    type="email"
                    class="mt-1 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                    required
                />
                <p v-if="form.errors.support_email" class="mt-1 text-xs text-red-600 dark:text-red-400">
                    {{ form.errors.support_email }}
                </p>
            </div>

            <!-- Default Locale -->
            <div>
                <label class="block text-xs font-medium text-neutral-900 dark:text-neutral-100">
                    {{ t('Default Language') }}
                </label>
                <select
                    v-model="form.default_locale"
                    class="mt-1 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                >
                    <option value="en">English (EN)</option>
                    <option value="ar">العربية (AR)</option>
                </select>
                <p v-if="form.errors.default_locale" class="mt-1 text-xs text-red-600 dark:text-red-400">
                    {{ form.errors.default_locale }}
                </p>
            </div>

            <!-- Maintenance Mode Toggle -->
            <div class="rounded-xl border border-neutral-200 bg-neutral-50/50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                <label class="flex items-center justify-between cursor-pointer select-none">
                    <div>
                        <span class="text-xs font-bold text-neutral-900 dark:text-neutral-100">
                            {{ t('Maintenance Mode') }}
                        </span>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                            {{ t('When enabled, non-admin users will be prevented from making system updates.') }}
                        </p>
                    </div>
                    <button
                        type="button"
                        role="switch"
                        :aria-checked="form.maintenance_mode"
                        @click="form.maintenance_mode = !form.maintenance_mode"
                        :class="[
                            'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900',
                            form.maintenance_mode ? 'bg-red-600 dark:bg-red-600' : 'bg-neutral-300 dark:bg-neutral-700',
                        ]"
                    >
                        <span
                            :class="[
                                'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow ring-0 transition-transform duration-200 ease-in-out',
                                form.maintenance_mode ? (isRtl ? '-translate-x-5' : 'translate-x-5') : 'translate-x-0',
                            ]"
                        />
                    </button>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end">
                <Button type="submit" :disabled="form.processing">
                    {{ t('Save Settings') }}
                </Button>
            </div>
        </form>
    </div>
</template>
