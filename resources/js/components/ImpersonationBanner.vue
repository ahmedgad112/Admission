<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { trans } from '@/composables/useTrans';

const page = usePage();
const impersonation = computed(() => page.props.impersonation);

function stop(): void {
    router.delete('/impersonation');
}
</script>

<template>
    <div
        v-if="impersonation?.active"
        class="flex flex-wrap items-center justify-between gap-3 border-b border-amber-500/30 bg-amber-500/10 px-4 py-2 text-sm text-amber-950 sm:px-6 dark:text-amber-100"
    >
        <p>
            {{
                trans('impersonation.banner', {
                    name: page.props.auth.user.name,
                })
            }}
        </p>
        <Button variant="outline" size="sm" class="rounded-full" @click="stop">
            {{ trans('impersonation.stop') }}
        </Button>
    </div>
</template>
