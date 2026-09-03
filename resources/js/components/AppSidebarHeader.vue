<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Calendar } from '@lucide/vue';
import { computed } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const locale = computed(() => page.props.locale || 'ar');

const formattedDate = computed(() => {
    const now = new Date();

    return now.toLocaleDateString(locale.value === 'ar' ? 'ar-EG' : 'en-US', {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
    });
});
</script>

<template>
    <header
        class="sticky top-0 z-10 flex h-14 shrink-0 items-center justify-between gap-2 overflow-hidden border-b border-border/80 bg-background/85 px-3 backdrop-blur-md transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 sm:h-16 sm:px-4 md:px-6"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ms-1 rounded-lg hover:bg-accent/60" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <div
            class="flex items-center gap-3 text-xs font-medium text-muted-foreground"
        >
            <div
                class="hidden items-center gap-1.5 rounded-full border border-border/60 bg-muted/40 px-3 py-1 sm:flex"
            >
                <Calendar class="size-3.5 text-primary" />
                <span>{{ formattedDate }}</span>
            </div>
            <div
                class="flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-emerald-600 dark:text-emerald-400"
            >
                <span class="active-pulse-dot">
                    <span></span>
                    <span></span>
                </span>
                <span class="text-[11px] font-semibold tracking-wide"
                    >أونلاين</span
                >
            </div>
        </div>
    </header>
</template>
