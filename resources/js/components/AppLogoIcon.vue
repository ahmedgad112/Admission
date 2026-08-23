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
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 32 32"
        fill="none"
        :class="className"
        v-bind="$attrs"
    >
        <rect x="3" y="5" width="26" height="24" rx="7" fill="currentColor" />
        <path
            d="M10 4.5h12v3H10z"
            fill="currentColor"
            class="opacity-80"
        />
        <path
            d="M11.2 17.4 14.4 20.6 21 14"
            stroke="#f7f3ea"
            stroke-width="2.4"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>
</template>
