<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Shift = {
    id: number;
    name: string;
    start_time: string;
    end_time: string;
    grace_period_minutes: number;
};

const props = defineProps<{
    shift?: Shift;
}>();

const form = useForm({
    name: props.shift?.name ?? '',
    start_time: props.shift?.start_time ?? '09:00',
    end_time: props.shift?.end_time ?? '17:00',
    grace_period_minutes: props.shift?.grace_period_minutes ?? 15,
});

function submit(): void {
    if (props.shift) {
        form.put(`/shifts/${props.shift.id}`);

        return;
    }

    form.post('/shifts');
}
</script>

<template>
    <Card class="mx-auto w-full max-w-2xl shadow-sm">
        <CardContent class="space-y-4 pt-6">
            <div class="space-y-2">
                <Label for="name">{{ trans('shifts.name') }}</Label>
                <Input id="name" v-model="form.name" />
                <p v-if="form.errors.name" class="text-sm text-destructive">
                    {{ form.errors.name }}
                </p>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <Label for="start_time">{{
                        trans('shifts.start_time')
                    }}</Label>
                    <Input
                        id="start_time"
                        v-model="form.start_time"
                        type="time"
                    />
                    <p
                        v-if="form.errors.start_time"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.start_time }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="end_time">{{ trans('shifts.end_time') }}</Label>
                    <Input id="end_time" v-model="form.end_time" type="time" />
                    <p
                        v-if="form.errors.end_time"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.end_time }}
                    </p>
                </div>
            </div>
            <p class="text-sm text-muted-foreground">
                {{ trans('shifts.overnight_help') }}
            </p>
            <div class="space-y-2">
                <Label for="grace_period_minutes">{{
                    trans('shifts.grace')
                }}</Label>
                <Input
                    id="grace_period_minutes"
                    v-model="form.grace_period_minutes"
                    type="number"
                    min="0"
                    max="180"
                />
                <p class="text-sm text-muted-foreground">
                    {{ trans('shifts.grace_help') }}
                </p>
                <p
                    v-if="form.errors.grace_period_minutes"
                    class="text-sm text-destructive"
                >
                    {{ form.errors.grace_period_minutes }}
                </p>
            </div>
            <Button
                class="rounded-full"
                :disabled="form.processing"
                @click="submit"
            >
                {{ shift ? trans('shifts.update') : trans('shifts.save') }}
            </Button>
        </CardContent>
    </Card>
</template>
