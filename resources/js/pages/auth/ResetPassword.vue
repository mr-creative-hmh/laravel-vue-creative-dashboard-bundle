<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useTrans } from '@/composables/useTrans';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Reset password',
        description: 'Please enter your new password below',
    },
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const { t } = useTrans();

const inputEmail = ref(props.email);
</script>

<template>
    <Head :title="t('Reset password')" />

    <Form
        v-bind="update.form()"
        :transform="(data) => ({ ...data, token, email })"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
    >
        <div class="grid gap-5">
            <div class="grid gap-2">
                <Label for="email" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Email address') }}</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    v-model="inputEmail"
                    readonly
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500 opacity-70"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Password') }}</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    autofocus
                    :placeholder="t('Password')"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Confirm password') }}</Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    :placeholder="t('Confirm password')"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-3 h-12 w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                :disabled="processing"
                data-test="reset-password-button"
            >
                <Spinner v-if="processing" />
                {{ t('Reset password') }}
            </Button>
        </div>
    </Form>
</template>
