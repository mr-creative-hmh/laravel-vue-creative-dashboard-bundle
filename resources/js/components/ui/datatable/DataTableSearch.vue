<script setup lang="ts">
import { Search, X } from '@lucide/vue';
import { ref, watch } from 'vue';
import { useTrans } from '@/composables/useTrans';

const props = withDefaults(
    defineProps<{
        modelValue?: string;
        placeholder?: string;
        debounceMs?: number;
    }>(),
    {
        modelValue: '',
        placeholder: 'Search...',
        debounceMs: 300,
    }
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'search', value: string): void;
}>();

const { t } = useTrans();
const query = ref(props.modelValue);
let timer: ReturnType<typeof setTimeout> | null = null;

watch(query, (val) => {
    emit('update:modelValue', val);
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
        emit('search', val);
    }, props.debounceMs);
});

watch(
    () => props.modelValue,
    (val) => {
        if (val !== query.value) {
            query.value = val;
        }
    }
);

function clear() {
    query.value = '';
    emit('update:modelValue', '');
    emit('search', '');
}
</script>

<template>
    <div class="relative w-full max-w-sm">
        <Search class="absolute left-3 rtl:left-auto rtl:right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400 dark:text-neutral-500" />
        <input
            v-model="query"
            type="text"
            :placeholder="placeholder ? t(placeholder, placeholder) : t('Search...')"
            class="h-9 w-full rounded-md border border-neutral-200 bg-white pl-9 pr-8 rtl:pl-8 rtl:pr-9 text-sm transition-colors placeholder:text-neutral-400 focus:border-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-400 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder:text-neutral-500 dark:focus:border-neutral-700 dark:focus:ring-neutral-700"
        />
        <button
            v-if="query"
            type="button"
            @click="clear"
            class="absolute right-2 rtl:right-auto rtl:left-2 top-1/2 -translate-y-1/2 rounded-full p-1 text-neutral-400 hover:text-neutral-600 dark:text-neutral-500 dark:hover:text-neutral-300"
        >
            <X class="h-3.5 w-3.5" />
        </button>
    </div>
</template>
