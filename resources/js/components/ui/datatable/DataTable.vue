<script setup lang="ts" generic="T extends Record<string, any>">
import { ArrowDown, ArrowUp, ArrowUpDown } from '@lucide/vue';
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';

export type Column = {
    key: string;
    label: string;
    sortable?: boolean;
    class?: string;
    align?: 'left' | 'center' | 'right';
};

const props = withDefaults(
    defineProps<{
        columns: Column[];
        data: T[];
        loading?: boolean;
        selectable?: boolean;
        selectedKeys?: Array<string | number>;
        keyField?: string;
        sortField?: string;
        sortDirection?: 'asc' | 'desc';
    }>(),
    {
        loading: false,
        selectable: false,
        selectedKeys: () => [],
        keyField: 'id',
        sortField: '',
        sortDirection: 'asc',
    }
);

const emit = defineEmits<{
    (e: 'sort', field: string): void;
    (e: 'update:selectedKeys', keys: Array<string | number>): void;
}>();

const { t } = useTrans();

const allSelected = computed(() => {
    if (!props.data || props.data.length === 0) return false;
    return props.data.every((item) => props.selectedKeys.includes(item[props.keyField]));
});

function toggleAll() {
    if (allSelected.value) {
        emit('update:selectedKeys', []);
    } else {
        const allKeys = props.data.map((item) => item[props.keyField]);
        emit('update:selectedKeys', allKeys);
    }
}

function toggleRow(key: string | number) {
    const newKeys = [...props.selectedKeys];
    const index = newKeys.indexOf(key);
    if (index > -1) {
        newKeys.splice(index, 1);
    } else {
        newKeys.push(key);
    }
    emit('update:selectedKeys', newKeys);
}

function handleSort(col: Column) {
    if (col.sortable) {
        emit('sort', col.key);
    }
}
</script>

<template>
    <div class="relative w-full overflow-hidden rounded-lg border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-start text-sm text-neutral-600 dark:text-neutral-300">
                <thead class="bg-neutral-50/80 text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:bg-neutral-800/60 dark:text-neutral-400">
                    <tr>
                        <th v-if="selectable" class="w-10 px-4 py-3 text-center">
                            <input
                                type="checkbox"
                                :checked="allSelected"
                                @change="toggleAll"
                                class="h-4 w-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:checked:bg-neutral-100"
                            />
                        </th>
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            :class="[
                                'px-4 py-3',
                                col.sortable ? 'cursor-pointer select-none hover:text-neutral-900 dark:hover:text-neutral-100' : '',
                                col.align === 'center' ? 'text-center' : col.align === 'right' ? 'text-end' : 'text-start',
                                col.class || '',
                            ]"
                            @click="handleSort(col)"
                        >
                            <div
                                :class="[
                                    'inline-flex items-center gap-1.5',
                                    col.align === 'right' ? 'justify-end w-full' : col.align === 'center' ? 'justify-center w-full' : ''
                                ]"
                            >
                                <span>{{ col.label }}</span>
                                <template v-if="col.sortable">
                                    <ArrowUp v-if="sortField === col.key && sortDirection === 'asc'" class="h-3.5 w-3.5 text-neutral-900 dark:text-neutral-100" />
                                    <ArrowDown v-else-if="sortField === col.key && sortDirection === 'desc'" class="h-3.5 w-3.5 text-neutral-900 dark:text-neutral-100" />
                                    <ArrowUpDown v-else class="h-3.5 w-3.5 opacity-40" />
                                </template>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                    <!-- Loading Skeleton -->
                    <template v-if="loading">
                        <tr v-for="i in 5" :key="i" class="animate-pulse">
                            <td v-if="selectable" class="px-4 py-3">
                                <div class="h-4 w-4 rounded bg-neutral-200 dark:bg-neutral-800"></div>
                            </td>
                            <td v-for="col in columns" :key="col.key" class="px-4 py-3">
                                <div class="h-4 rounded bg-neutral-200 dark:bg-neutral-800" :style="{ width: `${Math.floor(Math.random() * 40) + 50}%` }"></div>
                            </td>
                        </tr>
                    </template>

                    <!-- Data Rows -->
                    <template v-else-if="data && data.length > 0">
                        <tr
                            v-for="(row, idx) in data"
                            :key="row[keyField] ?? idx"
                            class="transition-colors hover:bg-neutral-50/70 dark:hover:bg-neutral-800/50"
                        >
                            <td v-if="selectable" class="px-4 py-3 text-center">
                                <input
                                    type="checkbox"
                                    :checked="selectedKeys.includes(row[keyField])"
                                    @change="toggleRow(row[keyField])"
                                    class="h-4 w-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:checked:bg-neutral-100"
                                />
                            </td>
                            <td
                                v-for="col in columns"
                                :key="col.key"
                                :class="[
                                    'px-4 py-3 align-middle',
                                    col.align === 'center' ? 'text-center' : col.align === 'right' ? 'text-end' : 'text-start',
                                    col.class || '',
                                ]"
                            >
                                <slot :name="col.key" :row="row" :value="row[col.key]">
                                    {{ row[col.key] }}
                                </slot>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty State -->
                    <template v-else>
                        <tr>
                            <td :colspan="columns.length + (selectable ? 1 : 0)" class="px-4 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                <slot name="empty">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <p class="font-medium text-neutral-600 dark:text-neutral-400">{{ t('No records found') }}</p>
                                        <p class="text-xs text-neutral-400 dark:text-neutral-500">{{ t('Try adjusting your filters or search terms.') }}</p>
                                    </div>
                                </slot>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>
