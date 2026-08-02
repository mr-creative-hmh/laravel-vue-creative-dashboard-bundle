<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { LogOut, UserCheck } from '@lucide/vue';
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import type { SharedPageProps } from '@/types/auth';

const page = usePage<SharedPageProps>();
const { t } = useTrans();

const isImpersonating = computed(() => page.props.auth?.is_impersonating || false);
const impersonatorName = computed(() => page.props.auth?.impersonator_name || 'Admin');
const currentUserName = computed(() => page.props.auth?.user?.name || 'User');

function stopImpersonating() {
    router.post('/impersonate/leave');
}
</script>

<template>
    <div
        v-if="isImpersonating"
        class="sticky top-0 z-50 flex items-center justify-between border-b border-amber-300 bg-amber-500 px-4 py-2 text-xs font-semibold text-amber-950 shadow-md dark:border-amber-600 dark:bg-amber-600 dark:text-white"
    >
        <div class="flex items-center gap-2">
            <UserCheck class="h-4 w-4 shrink-0" />
            <span>
                {{ t('You are currently impersonating') }} <strong>{{ currentUserName }}</strong> ({{ t('logged in as') }} {{ impersonatorName }})
            </span>
        </div>

        <button
            type="button"
            @click="stopImpersonating"
            class="inline-flex items-center gap-1 rounded-md bg-amber-950 px-3 py-1 text-[11px] font-bold text-white transition hover:bg-amber-900 dark:bg-neutral-900 dark:hover:bg-neutral-800"
        >
            <LogOut class="h-3.5 w-3.5" />
            <span>{{ t('Exit Impersonation') }}</span>
        </button>
    </div>
</template>
