<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useTrans } from '@/composables/useTrans';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Create an account',
        description: 'Enter your details below to create your account',
    },
});

const { t } = useTrans();
</script>

<template>
    <Head :title="t('Register')" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-4">
            <div class="grid gap-2">
                <Label for="name" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Name') }}</Label>
                <Input
                    id="name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    :placeholder="t('Full name')"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Email address') }}</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="email@example.com"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Password') }}</Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    name="password"
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
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    name="password_confirmation"
                    :placeholder="t('Confirm password')"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-3 h-12 w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                tabindex="5"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                {{ t('Create account') }}
            </Button>
        </div>

        <div class="text-center text-xs text-slate-600 dark:text-slate-400 pt-2">
            {{ t('Already have an account?') }}
            <TextLink
                :href="login()"
                class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 ml-1"
                :tabindex="6"
                >{{ t('Log in') }}</TextLink
            >
        </div>
    </Form>
</template>
