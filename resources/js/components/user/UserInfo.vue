<script setup lang="ts">
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types/auth';

type Props = {
    user: Partial<User> & { name: string };
    showEmail?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const { getInitials } = useInitials();

const avatarSrc = computed(
    () => props.user?.avatar_url || props.user?.avatar || ''
);

const primaryRole = computed(() => {
    if (props.user?.roles && props.user.roles.length > 0) {
        return props.user.roles[0];
    }
    return null;
});
</script>

<template>
    <Avatar class="h-8 w-8 shrink-0 overflow-hidden rounded-lg">
        <AvatarImage v-if="avatarSrc" :src="avatarSrc" :alt="user?.name" />
        <AvatarFallback class="rounded-lg text-black dark:text-white">
            {{ getInitials(user?.name) }}
        </AvatarFallback>
    </Avatar>

    <div class="grid flex-1 min-w-0 text-left text-xs leading-tight">
        <span class="truncate font-semibold text-neutral-900 dark:text-neutral-100">{{ user?.name }}</span>
        <div class="flex items-center space-x-1 space-x-reverse mt-0.5">
            <span v-if="showEmail" class="truncate text-[11px] text-neutral-500 dark:text-neutral-400 me-1">{{ user?.email }}</span>
            <span
                v-else-if="primaryRole"
                class="inline-block rounded bg-neutral-100 px-1.5 py-0.2 text-[10px] font-semibold text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
            >
                {{ primaryRole }}
            </span>
        </div>
    </div>
</template>
