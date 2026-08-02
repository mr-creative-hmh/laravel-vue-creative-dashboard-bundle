<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { computed } from 'vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import { type AccentColor, useAppearance } from '@/composables/useAppearance';
import { useTrans } from '@/composables/useTrans';

const { t, locale } = useTrans();
const { accentColor, updateAccentColor } = useAppearance();

const accents = computed(() => [
    { id: 'neutral' as AccentColor, name: t('Slate'), bgClass: 'bg-zinc-900 dark:bg-zinc-100' },
    { id: 'indigo' as AccentColor, name: t('Indigo'), bgClass: 'bg-indigo-600' },
    { id: 'emerald' as AccentColor, name: t('Emerald'), bgClass: 'bg-emerald-600' },
    { id: 'violet' as AccentColor, name: t('Violet'), bgClass: 'bg-violet-600' },
    { id: 'rose' as AccentColor, name: t('Rose'), bgClass: 'bg-rose-600' },
    { id: 'amber' as AccentColor, name: t('Amber'), bgClass: 'bg-amber-500' },
]);
</script>

<template>
    <Head :title="t('Appearance settings')" />

    <div class="space-y-8">
        <!-- Theme Mode -->
        <div class="space-y-4">
            <Heading
                :key="`theme-${locale}`"
                variant="small"
                :title="t('Theme Mode')"
                :description="t('Switch between light, dark, or system preference theme.')"
            />
            <AppearanceTabs />
        </div>

        <!-- Accent Color Palette -->
        <div class="space-y-4 border-t border-neutral-100 pt-6 dark:border-neutral-800">
            <Heading
                :key="`accent-${locale}`"
                variant="small"
                :title="t('Accent Palette')"
                :description="t('Select your preferred primary color theme accent.')"
            />

            <div class="grid grid-cols-3 gap-3 sm:grid-cols-6">
                <button
                    v-for="color in accents"
                    :key="color.id"
                    type="button"
                    @click="updateAccentColor(color.id)"
                    :class="[
                        'flex flex-col items-center justify-center gap-2 rounded-xl border p-3 transition cursor-pointer',
                        accentColor === color.id
                            ? 'border-neutral-900 bg-neutral-50 dark:border-neutral-100 dark:bg-neutral-800'
                            : 'border-neutral-200 bg-white hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800/50',
                    ]"
                >
                    <span :class="['h-7 w-7 rounded-full flex items-center justify-center shadow-xs', color.bgClass]">
                        <Check v-if="accentColor === color.id" class="h-4 w-4 text-white dark:text-neutral-900" />
                    </span>
                    <span class="text-xs font-semibold text-neutral-800 dark:text-neutral-200">
                        {{ color.name }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>
