<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        viewBox="0 0 100 100"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        :class="['shrink-0', className]"
        v-bind="$attrs"
    >
        <defs>
            <linearGradient id="logo-grad-primary" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#6366F1" />
                <stop offset="50%" stop-color="#8B5CF6" />
                <stop offset="100%" stop-color="#EC4899" />
            </linearGradient>
            <linearGradient id="logo-grad-secondary" x1="100%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stop-color="#06B6D4" />
                <stop offset="100%" stop-color="#3B82F6" />
            </linearGradient>
            <filter id="logo-glow" x="-20%" y="-20%" width="140%" height="140%">
                <feGaussianBlur stdDeviation="3.5" result="blur" />
                <feComposite in="SourceGraphic" in2="blur" operator="over" />
            </filter>
        </defs>

        <!-- Outer Expanded Glowing Hexagon Frame -->
        <path
            d="M50 4 L94 27 V73 L50 96 L6 73 V27 Z"
            stroke="url(#logo-grad-primary)"
            stroke-width="6.5"
            stroke-linecap="round"
            stroke-linejoin="round"
            fill="none"
            filter="url(#logo-glow)"
        />

        <!-- Inner Stylized Creative 'C' Ribbon -->
        <path
            d="M68 35 C60 26 40 26 32 35 C23 45 23 57 32 66 C40 75 60 75 68 66"
            stroke="url(#logo-grad-primary)"
            stroke-width="8.5"
            stroke-linecap="round"
            fill="none"
        />

        <!-- Central Spark Dot -->
        <circle cx="68" cy="50" r="5.5" fill="url(#logo-grad-secondary)" />
    </svg>
</template>
