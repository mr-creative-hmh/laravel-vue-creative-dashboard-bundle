<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { Check, Globe } from '@lucide/vue';
import { computed } from 'vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import 'flag-icons/css/flag-icons.min.css';
import type { SharedPageProps } from '@/types/auth';

const page = usePage<SharedPageProps>();

const currentLocale = computed(() => page.props.locale || 'en');

const languages = [
    { code: 'en', name: 'English', dir: 'ltr', flag: 'us' },
    { code: 'ar', name: 'العربية', dir: 'rtl', flag: 'sa' },
];

function setLocale(code: string) {
    if (code === currentLocale.value) return;

    router.post('/locale', { locale: code }, {
        preserveScroll: true,
        onSuccess: () => {
            const langObj = languages.find((l) => l.code === code);
            if (langObj) {
                document.documentElement.dir = langObj.dir;
                document.documentElement.lang = code;
            }
        },
    });
}
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger :as-child="true">
            <button
                type="button"
                class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900/90 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100 backdrop-blur-md cursor-pointer"
                title="Switch Language & Text Direction"
            >
                <span
                    :class="`fi fi-${currentLocale === 'en' ? 'us' : 'sa'} rounded-sm shrink-0`"
                    style="width:16px;height:12px"
                ></span>

                <span>{{ currentLocale.toUpperCase() }}</span>
            </button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end" class="w-36">
            <DropdownMenuItem
                v-for="lang in languages"
                :key="lang.code"
                @click="setLocale(lang.code)"
                class="flex items-center justify-between cursor-pointer text-xs"
            >
                <span class="flex items-center gap-2">
                    <span
                        :class="`fi fi-${lang.flag} rounded-sm shrink-0`"
                        style="width:16px;height:12px"
                    ></span>
                    <span>{{ lang.name }}</span>
                </span>
                <Check v-if="currentLocale === lang.code" class="h-3.5 w-3.5 shrink-0 text-neutral-900 dark:text-neutral-100" />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
