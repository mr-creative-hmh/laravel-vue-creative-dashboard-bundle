<script setup lang="ts">
import { Form, Head, setLayoutProps } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { useTrans } from '@/composables/useTrans';
import { store } from '@/routes/two-factor/login';
import type { TwoFactorConfigContent } from '@/types';

const { t } = useTrans();

const showRecoveryInput = ref<boolean>(false);
const code = ref<string>('');

const authConfigContent = computed<TwoFactorConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: t('Recovery code'),
            description: t(
                'Please confirm access to your account by entering one of your emergency recovery codes.',
            ),
            buttonText: t('Use an authentication code'),
        };
    }

    return {
        title: t('Authentication code'),
        description: t(
            'Please confirm access to your account by entering the authentication code provided by your authenticator application.',
        ),
        buttonText: t('Use a recovery code'),
    };
});

watchEffect(() => {
    setLayoutProps({
        title: authConfigContent.value.title,
        description: authConfigContent.value.description,
    });
});

const toggleRecoveryMode = (clearErrors: () => void): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    clearErrors();
    code.value = '';
};
</script>

<template>
    <Head :title="t('Two-factor authentication')" />

    <div class="space-y-6">
        <template v-if="!showRecoveryInput">
            <Form
                v-bind="store.form()"
                class="space-y-4"
                reset-on-error
                @error="code = ''"
                #default="{ errors, processing, clearErrors }"
            >
                <input type="hidden" name="code" :value="code" />
                <div
                    class="flex flex-col items-center justify-center space-y-3 text-center"
                >
                    <div class="flex w-full items-center justify-center">
                        <InputOTP
                            id="otp"
                            v-model="code"
                            :maxlength="6"
                            :disabled="processing"
                            autofocus
                        >
                            <InputOTPGroup>
                                <InputOTPSlot
                                    v-for="index in 6"
                                    :key="index"
                                    :index="index - 1"
                                />
                            </InputOTPGroup>
                        </InputOTP>
                    </div>
                    <InputError :message="errors.code" />
                </div>
                <Button
                    type="submit"
                    class="h-12 w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                    :disabled="processing"
                >
                    {{ t('Confirm') }}
                </Button>
                <div class="text-center text-xs text-slate-600 dark:text-slate-400">
                    <span>{{ t('or you can') }} </span>
                    <button
                        type="button"
                        class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 underline underline-offset-4"
                        @click="() => toggleRecoveryMode(clearErrors)"
                    >
                        {{ authConfigContent.buttonText }}
                    </button>
                </div>
            </Form>
        </template>

        <template v-else>
            <Form
                v-bind="store.form()"
                class="space-y-4"
                reset-on-error
                #default="{ errors, processing, clearErrors }"
            >
                <Input
                    name="recovery_code"
                    type="text"
                    :placeholder="t('Enter recovery code')"
                    :autofocus="showRecoveryInput"
                    required
                    class="h-11 rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950/60 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-500 focus:border-indigo-500"
                />
                <InputError :message="errors.recovery_code" />
                <Button
                    type="submit"
                    class="h-12 w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
                    :disabled="processing"
                >
                    {{ t('Confirm') }}
                </Button>

                <div class="text-center text-xs text-slate-600 dark:text-slate-400">
                    <span>{{ t('or you can') }} </span>
                    <button
                        type="button"
                        class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 underline underline-offset-4"
                        @click="() => toggleRecoveryMode(clearErrors)"
                    >
                        {{ authConfigContent.buttonText }}
                    </button>
                </div>
            </Form>
        </template>
    </div>
</template>
