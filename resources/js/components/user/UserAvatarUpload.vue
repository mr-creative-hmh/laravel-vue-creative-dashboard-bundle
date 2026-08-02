<script setup lang="ts">
import { Camera, Trash2 } from '@lucide/vue';
import { ref, watch } from 'vue';
import { useTrans } from '@/composables/useTrans';

const props = defineProps<{
    modelValue?: File | null;
    initialUrl?: string | null;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', file: File | null): void;
}>();

const { t } = useTrans();
const previewUrl = ref<string | null>(props.initialUrl || null);
const fileInput = ref<HTMLInputElement | null>(null);

watch(
    () => props.initialUrl,
    (val) => {
        if (!props.modelValue) {
            previewUrl.value = val || null;
        }
    }
);

function onFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        previewUrl.value = URL.createObjectURL(file);
        emit('update:modelValue', file);
    }
}

function removeAvatar() {
    previewUrl.value = null;
    emit('update:modelValue', null);
    if (fileInput.value) {
        fileInput.value.value = '';
    }
}

function triggerSelect() {
    fileInput.value?.click();
}
</script>

<template>
    <div class="flex items-center space-x-4 space-x-reverse">
        <div class="relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-full border-2 border-neutral-200 bg-neutral-100 dark:border-neutral-800 dark:bg-neutral-800">
            <img v-if="previewUrl" :src="previewUrl" class="h-full w-full object-cover" alt="Avatar preview" />
            <Camera v-else class="h-6 w-6 text-neutral-400 dark:text-neutral-500" />
        </div>

        <div class="flex items-center space-x-2 space-x-reverse">
            <input
                ref="fileInput"
                type="file"
                accept="image/*"
                class="hidden"
                @change="onFileChange"
            />
            <button
                type="button"
                @click="triggerSelect"
                class="inline-flex h-8 items-center justify-center rounded-md border border-neutral-200 bg-white px-3 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
            >
                {{ t('Upload Photo') }}
            </button>
            <button
                v-if="previewUrl"
                type="button"
                @click="removeAvatar"
                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 bg-white text-red-600 transition hover:bg-red-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-red-400 dark:hover:bg-red-950/40"
                :title="t('Remove photo')"
            >
                <Trash2 class="h-3.5 w-3.5" />
            </button>
        </div>
    </div>
</template>
