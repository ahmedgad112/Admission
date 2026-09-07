<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import TimerController from '@/actions/App/Http/Controllers/Settings/TimerController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';
import { edit } from '@/routes/timer';

const props = defineProps<{
    qrTtlSeconds: number;
    minTtl: number;
    maxTtl: number;
    presets: number[];
}>();

const selectedTtl = ref(props.qrTtlSeconds);

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'settings.timer.title',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head :title="trans('settings.timer.title')" />

    <h1 class="sr-only">{{ trans('settings.timer.title') }}</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            :title="trans('settings.timer.heading')"
            :description="trans('settings.timer.description')"
        />

        <Form
            v-bind="TimerController.update.form()"
            :options="{ preserveScroll: true }"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="qr_ttl_seconds">{{
                    trans('settings.timer.seconds')
                }}</Label>
                <Input
                    id="qr_ttl_seconds"
                    v-model="selectedTtl"
                    type="number"
                    name="qr_ttl_seconds"
                    :min="minTtl"
                    :max="maxTtl"
                    class="mt-1 block w-full"
                    required
                />
                <p class="text-xs text-muted-foreground">
                    {{
                        trans('settings.timer.hint', {
                            min: minTtl,
                            max: maxTtl,
                        })
                    }}
                </p>
                <InputError :message="errors.qr_ttl_seconds" />
            </div>

            <div v-if="presets.length > 0" class="space-y-2">
                <p class="text-sm font-medium">
                    {{ trans('settings.timer.presets') }}
                </p>
                <div class="flex flex-wrap gap-2">
                    <Button
                        v-for="preset in presets"
                        :key="preset"
                        type="button"
                        :variant="
                            Number(selectedTtl) === preset
                                ? 'default'
                                : 'outline'
                        "
                        class="rounded-full"
                        @click="selectedTtl = preset"
                    >
                        {{ preset }}
                    </Button>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="processing" data-test="update-timer-button">
                    {{ trans('common.save') }}
                </Button>
            </div>
        </Form>
    </div>
</template>
