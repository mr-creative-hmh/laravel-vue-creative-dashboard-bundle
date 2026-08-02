<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { useTrans } from '@/composables/useTrans';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineOptions({
    layout: {
        title: 'Verify email',
        description:
            'Please verify your email address by clicking on the link we just emailed to you.',
    },
});

defineProps<{
    status?: string;
}>();

const { t } = useTrans();
</script>

<template>
    <Head :title="t('Verify email')" />

    <div
        v-if="status === 'verification-link-sent'"
        class="mb-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-3 text-center text-sm font-semibold text-emerald-600 dark:text-emerald-400"
    >
        {{ t('A new verification link has been sent to the email address you provided during registration.') }}
    </div>

    <Form
        v-bind="send.form()"
        class="space-y-6 text-center"
        v-slot="{ processing }"
    >
        <Button
            :disabled="processing"
            class="h-12 w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40"
        >
            <Spinner v-if="processing" />
            {{ t('Resend verification email') }}
        </Button>

        <TextLink :href="logout()" as="button" class="mx-auto block text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
            {{ t('Log out') }}
        </TextLink>
    </Form>
</template>
