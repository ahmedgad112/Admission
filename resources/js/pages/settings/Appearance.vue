<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { trans } from '@/composables/useTrans';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'settings.appearance.title',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head :title="trans('settings.appearance.title')" />

    <h1 class="sr-only">{{ trans('settings.appearance.title') }}</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            :title="trans('settings.appearance.title')"
            :description="trans('settings.appearance.description')"
        />
        <AppearanceTabs />
        <Heading
            variant="small"
            :title="trans('settings.appearance.language')"
            :description="trans('settings.appearance.language_description')"
        />
        <LanguageSwitcher />
    </div>
</template>
