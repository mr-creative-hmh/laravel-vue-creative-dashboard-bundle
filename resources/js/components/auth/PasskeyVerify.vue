<script setup lang="ts">
import type { UrlMethodPair } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';
import { usePasskeyVerify } from '@laravel/passkeys/vue';
import { KeyRound } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { useTrans } from '@/composables/useTrans';

type Props = {
    routes?: {
        options: UrlMethodPair;
        submit: UrlMethodPair;
    };
    label?: string;
    loadingLabel?: string;
    separator?: string;
};

const props = defineProps<Props>();
const { t } = useTrans();

const { verify, isLoading, error, isSupported } = usePasskeyVerify({
    ...(props.routes
        ? {
              routes: {
                  options: props.routes.options.url,
                  submit: props.routes.submit.url,
              },
          }
        : {}),
    onSuccess: (response) => {
        router.visit(response.redirect ?? '/dashboard');
    },
});
</script>

<template>
    <div v-if="isSupported" class="w-full">
        <div class="grid gap-2">
            <button
                type="button"
                class="flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/80 px-4 text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm transition hover:border-slate-400 dark:hover:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white disabled:opacity-50"
                @click="verify"
                :disabled="isLoading"
            >
                <Spinner v-if="isLoading" />
                <KeyRound v-else class="h-4 w-4 text-indigo-600 dark:text-indigo-400 shrink-0" />
                <span>
                    {{
                        isLoading
                            ? t(props.loadingLabel ?? 'Authenticating...')
                            : t(props.label ?? 'Sign in with a passkey')
                    }}
                </span>
            </button>

            <div v-if="error" class="text-center">
                <InputError :message="error" />
            </div>
        </div>

        <div class="relative my-6 flex items-center justify-center">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-300 dark:border-slate-800"></div>
            </div>
            <div class="relative bg-white dark:bg-slate-900 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                {{ t(props.separator ?? 'Or continue with email') }}
            </div>
        </div>
    </div>
</template>
