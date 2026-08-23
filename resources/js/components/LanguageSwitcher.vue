<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { trans } from '@/composables/useTrans';

withDefaults(defineProps<{
    compact?: boolean;
}>(), {
    compact: false,
});

const page = usePage();

function setLocale(locale: 'en' | 'ar'): void {
    if (page.props.locale === locale) {
        return;
    }

    router.post('/locale', { locale }, { preserveScroll: true });
}
</script>

<template>
    <div class="inline-flex items-center gap-1 rounded-full bg-muted p-1" :aria-label="trans('common.language')">
        <button
            type="button"
            class="rounded-full px-2.5 py-1 text-xs font-medium transition-colors"
            :class="page.props.locale === 'ar'
                ? 'bg-background text-foreground shadow-sm'
                : 'text-muted-foreground hover:text-foreground'"
            @click="setLocale('ar')"
        >
            {{ trans('common.arabic') }}
        </button>
        <button
            type="button"
            class="rounded-full px-2.5 py-1 text-xs font-medium transition-colors"
            :class="page.props.locale === 'en'
                ? 'bg-background text-foreground shadow-sm'
                : 'text-muted-foreground hover:text-foreground'"
            @click="setLocale('en')"
        >
            EN
        </button>
    </div>
</template>
