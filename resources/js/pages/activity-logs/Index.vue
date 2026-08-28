<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { trans } from '@/composables/useTrans';

type LogRow = {
    id: number;
    event: string;
    description: string;
    subject_type: string;
    properties: Record<string, unknown> | null;
    changes: Record<string, unknown> | null;
    causer: { id: number; name: string; email: string } | null;
    ip_address: string | null;
    created_at: string | null;
};

const props = defineProps<{
    logs: {
        data: LogRow[];
        prev_page_url: string | null;
        next_page_url: string | null;
        current_page: number;
        last_page: number;
    };
    filters: { search: string; event: string };
    events: string[];
}>();

const search = ref(props.filters.search);

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.activity_log', href: '/activity-logs' },
        ],
    },
});

function filter(key: 'search' | 'event', value: string): void {
    router.get(
        '/activity-logs',
        {
            search: key === 'search' ? value || undefined : props.filters.search || undefined,
            event: key === 'event' ? value || undefined : props.filters.event || undefined,
        },
        { preserveState: true, replace: true },
    );
}

function formatTime(value: string | null): string {
    if (!value) {
        return '—';
    }

    const parsed = new Date(value);

    if (Number.isNaN(parsed.getTime())) {
        return value;
    }

    return parsed.toLocaleString();
}

function formatChange(value: unknown): string {
    if (value === null || value === undefined) {
        return '—';
    }

    if (typeof value === 'object') {
        return JSON.stringify(value);
    }

    return String(value);
}
</script>

<template>
    <Head :title="trans('activity.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('activity.eyebrow')"
            :title="trans('activity.title')"
            :description="trans('activity.description')"
        />

        <div class="mb-4 flex flex-col gap-3 sm:flex-row">
            <Input
                v-model="search"
                class="sm:max-w-sm"
                :placeholder="trans('activity.search')"
                @keyup.enter="filter('search', search)"
            />
            <select
                class="field-control sm:max-w-xs"
                :value="filters.event"
                @change="filter('event', ($event.target as HTMLSelectElement).value)"
            >
                <option value="">{{ trans('activity.all_events') }}</option>
                <option v-for="event in events" :key="event" :value="event">
                    {{ trans(`activity.events.${event}`) }}
                </option>
            </select>
        </div>

        <div
            v-if="logs.data.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('activity.empty') }}
        </div>
        <div v-else class="grid gap-3">
            <Card v-for="log in logs.data" :key="log.id" class="shadow-sm">
                <CardHeader class="pb-2">
                    <CardTitle class="text-base">{{ log.description }}</CardTitle>
                    <CardDescription>
                        {{ trans(`activity.events.${log.event}`) }}
                        ·
                        {{ trans(`activity.subjects.${log.subject_type}`) }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-3 text-sm text-muted-foreground">
                    <div class="flex flex-wrap gap-x-6 gap-y-1">
                        <span>{{ log.causer?.name ?? trans('activity.system') }}</span>
                        <span>{{ formatTime(log.created_at) }}</span>
                        <span v-if="log.ip_address">{{ log.ip_address }}</span>
                    </div>
                    <div
                        v-if="log.changes && Object.keys(log.changes).length > 0"
                        class="rounded-xl border border-border/70 bg-muted/20 p-3"
                    >
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-foreground">
                            {{ trans('activity.changes') }}
                        </p>
                        <dl class="grid gap-1 sm:grid-cols-2">
                            <div v-for="(value, key) in log.changes" :key="String(key)">
                                <dt class="text-xs text-muted-foreground">{{ key }}</dt>
                                <dd class="font-medium text-foreground">{{ formatChange(value) }}</dd>
                            </div>
                        </dl>
                    </div>
                </CardContent>
            </Card>
        </div>

        <div
            v-if="logs.last_page > 1"
            class="mt-4 flex items-center justify-between gap-3"
        >
            <Button
                variant="outline"
                size="sm"
                class="rounded-full"
                :disabled="!logs.prev_page_url"
                as-child
            >
                <Link
                    v-if="logs.prev_page_url"
                    :href="logs.prev_page_url"
                    preserve-scroll
                >
                    {{ trans('activity.prev') }}
                </Link>
                <span v-else>{{ trans('activity.prev') }}</span>
            </Button>
            <span class="text-sm text-muted-foreground">
                {{ logs.current_page }} / {{ logs.last_page }}
            </span>
            <Button
                variant="outline"
                size="sm"
                class="rounded-full"
                :disabled="!logs.next_page_url"
                as-child
            >
                <Link
                    v-if="logs.next_page_url"
                    :href="logs.next_page_url"
                    preserve-scroll
                >
                    {{ trans('activity.next') }}
                </Link>
                <span v-else>{{ trans('activity.next') }}</span>
            </Button>
        </div>
    </div>
</template>
