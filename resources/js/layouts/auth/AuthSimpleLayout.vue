<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { trans } from '@/composables/useTrans';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-svh items-center justify-center overflow-hidden bg-background p-6 md:p-10"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(45,191,163,0.12),_transparent_42%),radial-gradient(circle_at_bottom_right,_rgba(247,211,140,0.16),_transparent_32%)]"
        />
        <div class="relative w-full max-w-md">
            <div class="rounded-3xl border bg-card p-8 shadow-lg">
                <div class="mb-8 flex flex-col items-center gap-4 text-center">
                    <Link :href="home()" class="flex flex-col items-center gap-3">
                        <div
                            class="flex size-12 items-center justify-center rounded-2xl bg-primary text-primary-foreground shadow-sm"
                        >
                            <AppLogoIcon class="size-7" />
                        </div>
                        <span class="text-sm font-semibold tracking-[0.2em] text-primary uppercase">
                            Hadir
                        </span>
                    </Link>
                    <div class="space-y-2">
                        <h1 class="text-2xl font-semibold tracking-tight">{{ trans(title ?? '') }}</h1>
                        <p class="text-sm text-muted-foreground">{{ trans(description ?? '') }}</p>
                    </div>
                    <LanguageSwitcher />
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
