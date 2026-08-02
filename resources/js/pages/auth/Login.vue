<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/auth/PasskeyVerify.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useTrans } from '@/composables/useTrans';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Enter your email and password below to log in',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const { t } = useTrans();
</script>

<template>
    <Head :title="t('Log in')" />

    <div
        v-if="status"
        class="mb-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-3 text-center text-sm font-semibold text-emerald-600 dark:text-emerald-400"
    >
        {{ status }}
    </div>

    <PasskeyVerify />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-5">
            <div class="grid gap-2">
                <Label for="email" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Email address') }}</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500 focus:ring-indigo-500/20"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password" class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ t('Password') }}</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300"
                        :tabindex="5"
                    >
                        {{ t('Forgot your password?') }}
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    :placeholder="t('Password')"
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center justify-between pt-1">
                <label for="remember" class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                    <Checkbox id="remember" name="remember" :tabindex="3" class="rounded-md border-slate-300 dark:border-slate-700 data-[state=checked]:bg-indigo-600 data-[state=checked]:border-indigo-600" />
                    <span>{{ t('Remember me') }}</span>
                </label>
            </div>

            <Button
                type="submit"
                class="mt-3 h-12 w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                {{ t('Log in') }}
            </Button>
        </div>

        <div class="text-center text-xs text-slate-600 dark:text-slate-400 pt-2">
            {{ t("Don't have an account?") }}
            <TextLink :href="register()" :tabindex="5" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 ml-1">{{ t('Sign up') }}</TextLink>
        </div>
    </Form>
</template>
