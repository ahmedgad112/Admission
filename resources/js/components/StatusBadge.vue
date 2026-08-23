<script setup lang="ts">
import { computed } from 'vue';
import { trans } from '@/composables/useTrans';
import { humanize } from '@/lib/status';

const props = defineProps<{
    value: string;
    tone: string;
}>();

const label = computed(() => trans(`status.${props.value}`, {}, humanize(props.value)));
</script>

<template>
    <span
        :class="[
            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
            tone,
        ]"
    >
        {{ label }}
    </span>
</template>
