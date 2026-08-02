<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useTrans } from '@/composables/useTrans';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Forgot password',
        description: 'Enter your email to receive a password reset link',
    },
});

defineProps<{
    status?: string;
}>();

const { t } = useTrans();
</script>

<template>
    <Head :title="t('Forgot password')" />

    <div
        v-if="status"
        class="mb-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-3 text-center text-sm font-semibold text-emerald-600 dark:text-emerald-400"
    >
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form v-bind="email.form()" v-slot="{ errors, processing }">
            <div class="grid gap-2">
                <Label for="email" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Email address') }}</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="off"
                    autofocus
                    placeholder="email@example.com"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="my-6 flex items-center justify-start">
                <Button
                    class="h-12 w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                    :disabled="processing"
                    data-test="email-password-reset-link-button"
                >
                    <Spinner v-if="processing" />
                    {{ t('Email password reset link') }}
                </Button>
            </div>
        </Form>

        <div class="text-center text-xs text-slate-600 dark:text-slate-400 pt-2">
            <span>{{ t('Or, return to') }}</span>
            <TextLink :href="login()" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 ml-1">{{ t('Log in') }}</TextLink>
        </div>
    </div>
</template>
