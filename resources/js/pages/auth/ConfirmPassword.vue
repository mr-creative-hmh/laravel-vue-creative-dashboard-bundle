<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import {
    index as confirmOptions,
    store as confirmStore,
} from '@/actions/Laravel/Passkeys/Http/Controllers/PasskeyConfirmationController';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/auth/PasskeyVerify.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useTrans } from '@/composables/useTrans';
import { store } from '@/routes/password/confirm';

defineOptions({
    layout: {
        title: 'Confirm your password',
        description:
            'This is a secure area of the application. Please confirm your password before continuing.',
    },
});

const { t } = useTrans();
</script>

<template>
    <Head :title="t('Confirm your password')" />

    <PasskeyVerify
        :routes="{
            options: confirmOptions(),
            submit: confirmStore(),
        }"
        :label="t('Confirm with passkey')"
        :loading-label="t('Confirming...')"
        :separator="t('Or confirm with password')"
    />

    <Form
        v-bind="store.form()"
        reset-on-success
        v-slot="{ errors, processing }"
    >
        <div class="space-y-6">
            <div class="grid gap-2">
                <Label htmlFor="password" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Password') }}</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    autofocus
                    :placeholder="t('Password')"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />

                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center">
                <Button
                    type="submit"
                    class="h-12 w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                    :disabled="processing"
                    data-test="confirm-password-button"
                >
                    <Spinner v-if="processing" />
                    {{ t('Confirm') }}
                </Button>
            </div>
        </div>
    </Form>
</template>
