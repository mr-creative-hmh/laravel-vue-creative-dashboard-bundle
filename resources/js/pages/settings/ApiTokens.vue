<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Copy, Key, Plus, Trash2 } from '@lucide/vue';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { useTrans } from '@/composables/useTrans';

type Token = {
    id: number;
    name: string;
    abilities: string[];
    last_used_at: string;
    created_at: string;
};

const props = defineProps<{
    tokens: Token[];
}>();

const { t } = useTrans();
const page = usePage();

const showCreateModal = ref(false);
const plainTextToken = ref<string | null>(null);
const copied = ref(false);

const form = useForm({
    name: '',
});

function createToken() {
    form.post('/settings/api-tokens', {
        onSuccess: () => {
            const flashToken = (page.props.flash as any)?.plainTextToken;
            if (flashToken) {
                plainTextToken.value = flashToken;
            }
            form.reset();
            showCreateModal.value = false;
        },
    });
}

function revokeToken(token: Token) {
    if (confirm(`Revoke token "${token.name}"?`)) {
        router.delete(`/settings/api-tokens/${token.id}`);
    }
}

function copyToken() {
    if (plainTextToken.value) {
        navigator.clipboard.writeText(plainTextToken.value);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2000);
    }
}
</script>

<template>
    <Head title="API Tokens" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <Heading
                :title="t('API Tokens')"
                :description="t('Generate Personal Access Tokens to authenticate with system APIs.')"
            />
            <Button type="button" @click="showCreateModal = true" class="gap-1.5">
                <Plus class="h-4 w-4" />
                <span>{{ t('Create Token') }}</span>
            </Button>
        </div>

        <!-- Display PlainText Token Modal / Banner -->
        <div
            v-if="plainTextToken"
            class="rounded-xl border border-amber-300 bg-amber-50 p-4 dark:border-amber-700/60 dark:bg-amber-950/40"
        >
            <div class="flex items-start gap-3">
                <Key class="h-5 w-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-amber-900 dark:text-amber-100">
                        {{ t('API Token Generated Successfully') }}
                    </h4>
                    <p class="mt-0.5 text-xs text-amber-700 dark:text-amber-300">
                        {{ t('Please copy your token now. For security reasons, it will not be shown again!') }}
                    </p>

                    <div class="mt-3 flex items-center gap-2">
                        <input
                            type="text"
                            readonly
                            :value="plainTextToken"
                            class="h-9 w-full rounded-md border border-amber-300 bg-white px-3 font-mono text-xs text-neutral-900 focus:outline-none dark:border-amber-700 dark:bg-neutral-900 dark:text-neutral-100"
                        />
                        <Button type="button" variant="secondary" size="sm" @click="copyToken" class="shrink-0 gap-1">
                            <Copy class="h-3.5 w-3.5" />
                            <span>{{ copied ? t('Copied!') : t('Copy') }}</span>
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tokens List -->
        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="border-b border-neutral-100 px-6 py-4 dark:border-neutral-800">
                <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                    {{ t('Active API Tokens') }}
                </h3>
            </div>

            <div v-if="tokens.length > 0" class="divide-y divide-neutral-100 dark:divide-neutral-800">
                <div
                    v-for="token in tokens"
                    :key="token.id"
                    class="flex items-center justify-between p-4 px-6 text-xs"
                >
                    <div>
                        <h4 class="font-bold text-neutral-900 dark:text-neutral-100">
                            {{ token.name }}
                        </h4>
                        <p class="mt-0.5 text-neutral-500 dark:text-neutral-400">
                            {{ t('Last used') }}: {{ token.last_used_at }} • {{ t('Created') }}: {{ token.created_at }}
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="revokeToken(token)"
                        class="rounded p-1.5 text-neutral-400 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400"
                        title="Revoke Token"
                    >
                        <Trash2 class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <div v-else class="p-8 text-center text-xs text-neutral-500 dark:text-neutral-400">
                {{ t('No API tokens created yet.') }}
            </div>
        </div>
    </div>

    <!-- Create Token Modal -->
    <div
        v-if="showCreateModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
    >
        <div class="w-full max-w-md rounded-xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900">
            <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">
                {{ t('Create API Token') }}
            </h3>
            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                {{ t('API tokens allow third-party services to authenticate on your behalf.') }}
            </p>

            <form @submit.prevent="createToken" class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-neutral-900 dark:text-neutral-100">
                        {{ t('Token Name') }}
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="e.g. GitHub Integration, Mobile App"
                        class="mt-1 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm focus:border-neutral-400 focus:outline-none dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"
                        required
                    />
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <Button type="button" variant="outline" @click="showCreateModal = false">
                        {{ t('Cancel') }}
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ t('Generate Token') }}
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
