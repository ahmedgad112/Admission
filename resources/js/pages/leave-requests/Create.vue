<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

defineProps<{
    types: Array<{ value: string; label: string }>;
    leaveBalance: {
        allocated: number;
        used: number;
        remaining: number;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.leave', href: '/leave-requests' },
            { title: 'common.create', href: '/leave-requests/create' },
        ],
    },
});

const form = useForm({
    start_date: '',
    end_date: '',
    type: 'permission',
    reason: '',
});

function submit(): void {
    form.post('/leave-requests');
}
</script>

<template>
    <Head :title="trans('leave.request')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('leave.eyebrow')"
            :title="trans('leave.request')"
            :description="trans('leave.request_description')"
        />
        <Card class="mx-auto w-full max-w-2xl shadow-sm">
            <CardContent class="space-y-4 pt-6">
                <p
                    class="rounded-2xl bg-muted/60 px-4 py-3 text-sm text-muted-foreground"
                >
                    {{
                        trans('leave.balance', {
                            remaining: leaveBalance.remaining,
                            allocated: leaveBalance.allocated,
                            used: leaveBalance.used,
                        })
                    }}
                </p>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="start_date">{{
                            trans('common.from')
                        }}</Label>
                        <Input
                            id="start_date"
                            v-model="form.start_date"
                            type="date"
                            dir="ltr"
                        />
                        <p
                            v-if="form.errors.start_date"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.start_date }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <Label for="end_date">{{ trans('common.to') }}</Label>
                        <Input
                            id="end_date"
                            v-model="form.end_date"
                            type="date"
                            dir="ltr"
                        />
                        <p
                            v-if="form.errors.end_date"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.end_date }}
                        </p>
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="type">{{ trans('common.type') }}</Label>
                    <select id="type" v-model="form.type" class="field-control">
                        <option
                            v-for="type in types"
                            :key="type.value"
                            :value="type.value"
                        >
                            {{ trans(`leave.type.${type.value}`) }}
                        </option>
                    </select>
                    <p v-if="form.errors.type" class="text-sm text-destructive">
                        {{ form.errors.type }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="reason">{{ trans('common.reason') }}</Label>
                    <textarea
                        id="reason"
                        v-model="form.reason"
                        class="field-control min-h-28 py-3"
                        :placeholder="trans('leave.reason_placeholder')"
                    />
                    <p
                        v-if="form.errors.reason"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.reason }}
                    </p>
                </div>
                <Button
                    class="rounded-full"
                    :disabled="form.processing"
                    @click="submit"
                >
                    {{ trans('leave.submit') }}
                </Button>
            </CardContent>
        </Card>
    </div>
</template>
