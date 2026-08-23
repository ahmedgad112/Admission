<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';
import { encodeQrSvg } from '@/lib/qrcode';

type BranchOption = { id: number; name: string };
type DaySession = {
    id: number;
    branch_id: number;
    date: string;
    check_in_starts_at: string;
    check_in_ends_at: string;
    check_out_starts_at: string;
    check_out_ends_at: string;
    check_in_is_open: boolean;
    check_out_is_open: boolean;
};
type QrPayload = {
    token: string;
    entry_code: string | null;
    type: string;
    expires_at: string;
    refresh_in_seconds: number;
    day?: DaySession;
    message?: string;
};

const props = defineProps<{
    branches: BranchOption[];
    defaultBranchId: number | null;
    todaySessions: DaySession[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.kiosk', href: '/attendance/kiosk' },
        ],
    },
});

const type = ref<'check_in' | 'check_out'>('check_in');
const branchId = ref<number | null>(props.defaultBranchId ?? props.branches[0]?.id ?? null);
const days = ref<DaySession[]>([...props.todaySessions]);
const session = ref<QrPayload | null>(null);
const qrSvg = ref('');
const remaining = ref(0);
const error = ref('');
const processing = ref(false);
let timer: number | undefined;

const todaySession = computed(() =>
    days.value.find((day) => day.branch_id === branchId.value) ?? null,
);

const isOpen = computed(() => {
    if (!todaySession.value) {
        return false;
    }

    return type.value === 'check_in'
        ? todaySession.value.check_in_is_open
        : todaySession.value.check_out_is_open;
});

function csrfToken(): string {
    const token = document.cookie
        .split('; ')
        .find((row) => row.startsWith('XSRF-TOKEN='))
        ?.split('=')
        .slice(1)
        .join('=');

    return token ? decodeURIComponent(token) : '';
}

function applyDay(day?: DaySession): void {
    if (!day) {
        return;
    }

    days.value = [
        ...days.value.filter((item) => item.id !== day.id),
        day,
    ];
}

function clearQr(): void {
    session.value = null;
    qrSvg.value = '';
    remaining.value = 0;
}

async function requestSession(path: string, method: 'GET' | 'POST'): Promise<Response> {
    const params = new URLSearchParams({
        type: type.value,
    });

    if (branchId.value) {
        params.set('branch_id', String(branchId.value));
    }

    const headers: Record<string, string> = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };

    if (method === 'POST') {
        headers['Content-Type'] = 'application/json';
        headers['X-XSRF-TOKEN'] = csrfToken();
    }

    return fetch(
        method === 'GET' ? `${path}?${params.toString()}` : path,
        {
            method,
            headers,
            credentials: 'same-origin',
            body: method === 'POST'
                ? JSON.stringify({
                    type: type.value,
                    branch_id: branchId.value,
                })
                : undefined,
        },
    );
}

async function loadSession(): Promise<void> {
    if (!todaySession.value || !isOpen.value) {
        clearQr();
        error.value = '';
        return;
    }

    const response = await requestSession('/attendance/qr-sessions/current', 'GET');
    const body = (await response.json()) as QrPayload;

    applyDay(body.day);

    if (!response.ok) {
        clearQr();
        error.value = body.message ?? 'Unable to generate a QR session.';
        return;
    }

    session.value = body;
    qrSvg.value = encodeQrSvg(body.token);
    remaining.value = Math.max(0, Math.ceil(body.refresh_in_seconds));
    error.value = '';
}

async function toggleSession(open: boolean): Promise<void> {
    processing.value = true;

    try {
        const response = await requestSession(
            open ? '/attendance/qr-sessions/open' : '/attendance/qr-sessions/close',
            'POST',
        );
        const body = (await response.json()) as QrPayload;

        applyDay(body.day);

        if (!response.ok) {
            clearQr();
            error.value = body.message ?? 'Unable to update the session.';
            return;
        }

        if (open && body.token) {
            session.value = body;
            qrSvg.value = encodeQrSvg(body.token);
            remaining.value = Math.max(0, Math.ceil(body.refresh_in_seconds));
            error.value = '';
            return;
        }

        clearQr();
        error.value = '';
    } finally {
        processing.value = false;
    }
}

onMounted(async () => {
    await loadSession();

    timer = window.setInterval(async () => {
        if (!isOpen.value) {
            return;
        }

        remaining.value = Math.max(0, remaining.value - 1);

        if (remaining.value <= 0) {
            await loadSession();
        }
    }, 1000);
});

onUnmounted(() => {
    if (timer) {
        window.clearInterval(timer);
    }
});

watch([type, branchId], () => {
    void loadSession();
});
</script>

<template>
    <Head :title="trans('kiosk.head')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('kiosk.eyebrow')"
            :title="trans('kiosk.live_title')"
            :description="trans('kiosk.description')"
        />

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_280px]">
            <div
                class="flex flex-col items-center justify-center rounded-[2rem] border bg-card px-6 py-10 text-center shadow-sm"
            >
                <p class="mb-2 text-xs tracking-[0.3em] text-primary uppercase">
                    {{ type === 'check_in' ? trans('scan.check_in') : trans('scan.check_out') }}
                </p>
                <p
                    class="mb-6 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                    :class="isOpen ? 'bg-emerald-100 text-emerald-800' : 'bg-muted text-muted-foreground'"
                >
                    <span
                        class="size-2 rounded-full"
                        :class="isOpen ? 'bg-emerald-600' : 'bg-muted-foreground/50'"
                    />
                    {{ isOpen ? trans('kiosk.session_open') : trans('kiosk.session_closed') }}
                </p>
                <div
                    v-if="qrSvg"
                    class="flex aspect-square w-full max-w-md items-center justify-center rounded-[1.7rem] border bg-white p-5 text-black"
                    v-html="qrSvg"
                />
                <div
                    v-else
                    class="flex aspect-square w-full max-w-md items-center justify-center rounded-[1.7rem] border border-dashed text-sm text-muted-foreground"
                >
                    {{ todaySession ? trans('kiosk.open_to_show') : trans('kiosk.create_roster_first') }}
                </div>
                <div v-if="session?.entry_code" class="mt-6 space-y-2">
                    <p class="text-xs tracking-[0.2em] text-muted-foreground uppercase">
                        {{ trans('kiosk.code_hint') }}
                    </p>
                    <p class="font-mono text-4xl font-semibold tracking-[0.35em] text-foreground">
                        {{ session.entry_code }}
                    </p>
                </div>
                <div v-if="isOpen && session" class="mt-4 flex items-center gap-3 text-sm text-muted-foreground">
                    <span class="size-2 rounded-full bg-primary" />
                    {{ trans('kiosk.refreshes', { seconds: remaining }) }}
                </div>
                <p v-if="error" class="mt-3 text-sm text-destructive">{{ error }}</p>
            </div>

            <div class="space-y-5 rounded-[2rem] border bg-card p-6 shadow-sm">
                <div class="space-y-2">
                    <Label for="type">{{ trans('kiosk.mode') }}</Label>
                    <select id="type" v-model="type" class="field-control">
                        <option value="check_in">{{ trans('scan.check_in') }}</option>
                        <option value="check_out">{{ trans('scan.check_out') }}</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <Label for="branch">{{ trans('common.branch') }}</Label>
                    <select id="branch" v-model.number="branchId" class="field-control">
                        <option
                            v-for="branch in branches"
                            :key="branch.id"
                            :value="branch.id"
                        >
                            {{ branch.name }}
                        </option>
                    </select>
                </div>
                <Button v-if="!todaySession" variant="outline" class="w-full rounded-full" as-child>
                    <Link href="/attendance/days/create">{{ trans('kiosk.create_today') }}</Link>
                </Button>
                <Button
                    v-if="todaySession && !isOpen"
                    class="w-full rounded-full"
                    :disabled="processing"
                    @click="toggleSession(true)"
                >
                    {{ trans('kiosk.open_session') }}
                </Button>
                <Button
                    v-if="todaySession && isOpen"
                    variant="destructive"
                    class="w-full rounded-full"
                    :disabled="processing"
                    @click="toggleSession(false)"
                >
                    {{ trans('kiosk.close_session') }}
                </Button>
                <Button
                    v-if="isOpen"
                    variant="outline"
                    class="w-full rounded-full"
                    :disabled="processing"
                    @click="loadSession"
                >
                    {{ trans('kiosk.refresh') }}
                </Button>
            </div>
        </div>
    </div>
</template>
