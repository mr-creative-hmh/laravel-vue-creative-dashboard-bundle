<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from '@lucide/vue';
import { useTrans } from '@/composables/useTrans';

export type PaginationMeta = {
    current_page: number;
    from: number | null;
    last_page: number;
    path: string;
    per_page: number;
    to: number | null;
    total: number;
    links?: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
};

const props = defineProps<{
    meta: PaginationMeta;
}>();

const { t } = useTrans();
</script>

<template>
    <div class="flex flex-col items-center justify-between gap-4 px-2 py-3 sm:flex-row">
        <div class="text-xs text-neutral-500 dark:text-neutral-400">
            {{ t('Showing') }}
            <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ meta.from || 0 }}</span>
            {{ t('to') }}
            <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ meta.to || 0 }}</span>
            {{ t('of') }}
            <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ meta.total }}</span>
            {{ t('results') }}
        </div>

        <div v-if="meta.last_page > 1" class="flex items-center gap-1">
            <!-- First Page -->
            <Link
                v-if="meta.current_page > 1"
                :href="`${meta.path}?page=1`"
                preserve-scroll
                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 bg-white text-sm text-neutral-600 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800"
            >
                <ChevronsLeft class="h-4 w-4 rtl:rotate-180" />
            </Link>

            <!-- Previous Page -->
            <Link
                v-if="meta.current_page > 1"
                :href="`${meta.path}?page=${meta.current_page - 1}`"
                preserve-scroll
                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 bg-white text-sm text-neutral-600 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800"
            >
                <ChevronLeft class="h-4 w-4 rtl:rotate-180" />
            </Link>

            <!-- Page Number Indicator -->
            <span class="px-2 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                {{ t('Page') }} {{ meta.current_page }} {{ t('of') }} {{ meta.last_page }}
            </span>

            <!-- Next Page -->
            <Link
                v-if="meta.current_page < meta.last_page"
                :href="`${meta.path}?page=${meta.current_page + 1}`"
                preserve-scroll
                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 bg-white text-sm text-neutral-600 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800"
            >
                <ChevronRight class="h-4 w-4 rtl:rotate-180" />
            </Link>

            <!-- Last Page -->
            <Link
                v-if="meta.current_page < meta.last_page"
                :href="`${meta.path}?page=${meta.last_page}`"
                preserve-scroll
                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 bg-white text-sm text-neutral-600 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800"
            >
                <ChevronsRight class="h-4 w-4 rtl:rotate-180" />
            </Link>
        </div>
    </div>
</template>
