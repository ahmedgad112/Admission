<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Branch = {
    id: number;
    name: string;
};

const props = defineProps<{
    branch?: Branch;
}>();

const form = useForm({
    name: props.branch?.name ?? '',
});

function submit(): void {
    if (props.branch) {
        form.put(`/branches/${props.branch.id}`);

        return;
    }

    form.post('/branches');
}
</script>

<template>
    <Card class="mx-auto w-full max-w-2xl shadow-sm">
        <CardContent class="space-y-4 pt-6">
            <div class="space-y-2">
                <Label for="name">{{ trans('branches.name') }}</Label>
                <Input id="name" v-model="form.name" />
                <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
            </div>
            <Button class="rounded-full" :disabled="form.processing" @click="submit">
                {{ branch ? trans('branches.update') : trans('branches.save') }}
            </Button>
        </CardContent>
    </Card>
</template>
