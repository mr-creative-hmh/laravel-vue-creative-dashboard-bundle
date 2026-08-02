<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Code, LayoutGrid, RotateCcw, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import { useTrans } from '@/composables/useTrans';

const props = defineProps<{
    open: boolean;
    log: {
        id: number;
        description: string;
        event: string | null;
        subject_type: string | null;
        causer?: { name: string; email: string } | null;
        properties?: Record<string, any>;
        created_at: string;
    } | null;
}>();

const emit = defineEmits<{
    (e: 'update:open', val: boolean): void;
}>();

const { t } = useTrans();
const viewMode = ref<'formatted' | 'raw'>('formatted');

const oldAttributes = computed(() => props.log?.properties?.old || null);
const newAttributes = computed(() => props.log?.properties?.attributes || props.log?.properties || null);

// Unified list of keys for diff comparison
const diffKeys = computed(() => {
    if (!oldAttributes.value && !newAttributes.value) return [];
    const keys = new Set([
        ...Object.keys(oldAttributes.value || {}),
        ...Object.keys(newAttributes.value || {}),
    ]);
    return Array.from(keys);
});

function formatKey(key: string): string {
    return key
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

function close() {
    emit('update:open', false);
}

function undoLog() {
    if (props.log && confirm(t('Revert this activity log action?'))) {
        router.post(`/logs/${props.log.id}/undo`, {}, {
            onSuccess: () => close(),
        });
    }
}
</script>

<template>
    <Teleport to="body">
        <div v-if="open && log" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-neutral-950/50 backdrop-blur-sm" @click="close"></div>

            <div class="relative w-full max-w-2xl max-h-[85vh] flex flex-col rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-800 dark:bg-neutral-900">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4 dark:border-neutral-800">
                    <div class="flex items-center gap-3">
                        <span
                            :class="[
                                'inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wider',
                                log.event === 'created'
                                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300'
                                    : log.event === 'updated'
                                    ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300'
                                    : log.event === 'deleted'
                                    ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300'
                                    : 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300',
                            ]"
                        >
                            {{ log.event || 'Log' }}
                        </span>

                        <div>
                            <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">
                                {{ t('Activity Log Details') }} #{{ log.id }}
                            </h3>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                {{ log.description }} • {{ log.created_at }}
                                <span v-if="log.causer"> • {{ t('by') }} {{ log.causer.name }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- View Switcher & Close -->
                    <div class="flex items-center gap-2">
                        <div class="inline-flex rounded-lg border border-neutral-200 bg-neutral-100 p-0.5 dark:border-neutral-800 dark:bg-neutral-800">
                            <button
                                type="button"
                                @click="viewMode = 'formatted'"
                                :class="[
                                    'flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium transition',
                                    viewMode === 'formatted'
                                        ? 'bg-white text-neutral-900 shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                                        : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200',
                                ]"
                            >
                                <LayoutGrid class="h-3.5 w-3.5" />
                                <span>{{ t('Formatted') }}</span>
                            </button>
                            <button
                                type="button"
                                @click="viewMode = 'raw'"
                                :class="[
                                    'flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium transition',
                                    viewMode === 'raw'
                                        ? 'bg-white text-neutral-900 shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                                        : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200',
                                ]"
                            >
                                <Code class="h-3.5 w-3.5" />
                                <span>{{ t('Raw JSON') }}</span>
                            </button>
                        </div>

                        <button
                            type="button"
                            @click="close"
                            class="rounded-full p-1 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-600 dark:hover:bg-neutral-800 dark:hover:text-neutral-200"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto py-4 space-y-4">
                    <!-- FORMATTED VIEW -->
                    <template v-if="viewMode === 'formatted'">
                        <!-- Case 1: Diff between Old and New -->
                        <div v-if="oldAttributes" class="space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                                {{ t('Changed Field Values') }}
                            </h4>

                            <div class="divide-y divide-neutral-100 rounded-xl border border-neutral-200 bg-white overflow-hidden text-xs dark:divide-neutral-800 dark:border-neutral-800 dark:bg-neutral-900">
                                <div
                                    v-for="key in diffKeys"
                                    :key="key"
                                    class="grid grid-cols-1 md:grid-cols-3 gap-2 p-3 items-center hover:bg-neutral-50/50 dark:hover:bg-neutral-800/50"
                                >
                                    <span class="font-bold text-neutral-800 dark:text-neutral-200">
                                        {{ formatKey(key) }}
                                    </span>

                                    <!-- Old Value -->
                                    <div class="rounded-md bg-rose-50 px-2.5 py-1 text-rose-800 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200/50 dark:border-rose-900/50">
                                        <span class="text-[10px] uppercase font-bold text-rose-500 block mb-0.5">{{ t('Before') }}</span>
                                        <span class="font-mono line-through">{{ oldAttributes[key] ?? 'null' }}</span>
                                    </div>

                                    <!-- New Value -->
                                    <div class="rounded-md bg-emerald-50 px-2.5 py-1 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/50">
                                        <span class="text-[10px] uppercase font-bold text-emerald-500 block mb-0.5">{{ t('After') }}</span>
                                        <span class="font-mono font-semibold">{{ newAttributes[key] ?? 'null' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Case 2: Single Payload (e.g. Created / Properties) -->
                        <div v-else-if="newAttributes && typeof newAttributes === 'object'" class="space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                                {{ t('Payload Data') }}
                            </h4>

                            <div class="divide-y divide-neutral-100 rounded-xl border border-neutral-200 bg-white overflow-hidden text-xs dark:divide-neutral-800 dark:border-neutral-800 dark:bg-neutral-900">
                                <div
                                    v-for="(val, key) in newAttributes"
                                    :key="key"
                                    class="flex items-center justify-between p-3.5 hover:bg-neutral-50/50 dark:hover:bg-neutral-800/50"
                                >
                                    <span class="font-bold text-neutral-700 dark:text-neutral-300">
                                        {{ formatKey(String(key)) }}
                                    </span>

                                    <!-- Value Formatting -->
                                    <div>
                                        <span v-if="val === true" class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-bold text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            True / Active
                                        </span>
                                        <span v-else-if="val === false" class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-bold text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">
                                            False / Disabled
                                        </span>
                                        <span v-else-if="val === null" class="inline-flex items-center rounded-full bg-neutral-100 px-2.5 py-0.5 text-[11px] font-medium text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                                            Null / Empty
                                        </span>
                                        <span v-else class="font-mono font-medium text-neutral-900 dark:text-neutral-100">
                                            {{ val }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- RAW JSON VIEW -->
                    <template v-else>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2" v-if="oldAttributes">
                            <!-- Old Values -->
                            <div class="rounded-xl border border-red-200 bg-red-50/40 p-3.5 dark:border-red-950/60 dark:bg-red-950/20">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-red-700 dark:text-red-400 mb-2">
                                    {{ t('Previous State (Old)') }}
                                </h4>
                                <pre class="overflow-x-auto text-[11px] font-mono text-red-900 dark:text-red-200 leading-relaxed">{{ JSON.stringify(oldAttributes, null, 2) }}</pre>
                            </div>

                            <!-- New Values -->
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50/40 p-3.5 dark:border-emerald-950/60 dark:bg-emerald-950/20">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 mb-2">
                                    {{ t('Updated State (New)') }}
                                </h4>
                                <pre class="overflow-x-auto text-[11px] font-mono text-emerald-900 dark:text-emerald-200 leading-relaxed">{{ JSON.stringify(newAttributes, null, 2) }}</pre>
                            </div>
                        </div>

                        <div v-else class="rounded-xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-800/40">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-700 dark:text-neutral-300 mb-2">
                                {{ t('Raw JSON Payload') }}
                            </h4>
                            <pre class="overflow-x-auto text-[11px] font-mono text-neutral-800 dark:text-neutral-200 leading-relaxed">{{ JSON.stringify(newAttributes, null, 2) }}</pre>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-between border-t border-neutral-100 pt-4 dark:border-neutral-800">
                    <button
                        v-if="log.event === 'updated' || log.event === 'created'"
                        type="button"
                        @click="undoLog"
                        class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-amber-300 bg-amber-50 px-3.5 text-xs font-bold text-amber-900 transition hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-950/60 dark:text-amber-200 dark:hover:bg-amber-900/80 cursor-pointer"
                    >
                        <RotateCcw class="h-3.5 w-3.5" />
                        <span>{{ t('Undo Action') }}</span>
                    </button>
                    <div v-else></div>

                    <button
                        type="button"
                        @click="close"
                        class="inline-flex h-9 items-center justify-center rounded-xl border border-neutral-200 bg-white px-4 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800 cursor-pointer"
                    >
                        {{ t('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
