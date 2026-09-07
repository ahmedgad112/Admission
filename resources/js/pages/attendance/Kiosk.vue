<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';
import { kioskScanUrl } from '@/lib/qr-scan';
import { encodeQrPng } from '@/lib/qrcode';
import { edit as editTimer } from '@/routes/timer';

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
    scan_path?: string | null;
    expires_at: string;
    refresh_in_seconds: number;
    day?: DaySession;
    message?: string;
};
type PendingPerson = {
    id: number;
    name: string;
    department?: { id: number; name: string } | null;
};

const props = defineProps<{
    branches: BranchOption[];
    defaultBranchId: number | null;
    todaySessions: DaySession[];
    qrTtlSeconds: number;
    minTtl: number;
    maxTtl: number;
    presets: number[];
    entryCodeLength: number;
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
const branchId = ref<number | null>(
    props.defaultBranchId ?? props.branches[0]?.id ?? null,
);
const days = ref<DaySession[]>([...props.todaySessions]);
const session = ref<QrPayload | null>(null);
const qrPng = ref('');
const remaining = ref(0);
const ttlSeconds = ref(props.qrTtlSeconds);
const ttlSaving = ref(false);
const ttlError = ref('');
const error = ref('');
const processing = ref(false);
const pending = ref<PendingPerson[]>([]);
let timer: number | undefined;
let pendingTimer: number | undefined;

const todaySession = computed(
    () => days.value.find((day) => day.branch_id === branchId.value) ?? null,
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

async function saveTimer(): Promise<void> {
    ttlSaving.value = true;
    ttlError.value = '';

    try {
        const response = await fetch('/settings/timer', {
            method: 'PUT',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-XSRF-TOKEN': csrfToken(),
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                qr_ttl_seconds: Number(ttlSeconds.value),
            }),
        });
        const body = (await response.json()) as {
            qr_ttl_seconds?: number;
            message?: string;
            errors?: { qr_ttl_seconds?: string[] };
        };

        if (!response.ok) {
            ttlError.value =
                body.errors?.qr_ttl_seconds?.[0] ??
                body.message ??
                trans('kiosk.timer_settings');

            return;
        }

        ttlSeconds.value = body.qr_ttl_seconds ?? Number(ttlSeconds.value);
        await loadSession();
    } finally {
        ttlSaving.value = false;
    }
}

function applyDay(day?: DaySession): void {
    if (!day) {
        return;
    }

    days.value = [...days.value.filter((item) => item.id !== day.id), day];
}

function clearQr(): void {
    session.value = null;
    qrPng.value = '';
    remaining.value = 0;
}

async function renderQr(payload: QrPayload): Promise<void> {
    session.value = payload;
    const qrValue = payload.scan_path
        ? new URL(payload.scan_path, window.location.origin).toString()
        : kioskScanUrl(payload.entry_code || payload.token);
    qrPng.value = await encodeQrPng(qrValue);
    remaining.value = Math.max(0, Math.ceil(payload.refresh_in_seconds));
    error.value = '';
}

async function requestSession(
    path: string,
    method: 'GET' | 'POST',
): Promise<Response> {
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

    return fetch(method === 'GET' ? `${path}?${params.toString()}` : path, {
        method,
        headers,
        credentials: 'same-origin',
        body:
            method === 'POST'
                ? JSON.stringify({
                      type: type.value,
                      branch_id: branchId.value,
                  })
                : undefined,
    });
}

async function loadSession(): Promise<void> {
    if (!todaySession.value || !isOpen.value) {
        clearQr();
        error.value = '';

        return;
    }

    const response = await requestSession(
        '/attendance/qr-sessions/current',
        'GET',
    );
    const body = (await response.json()) as QrPayload;

    applyDay(body.day);

    if (!response.ok) {
        clearQr();
        error.value = body.message ?? 'Unable to generate a QR session.';

        return;
    }

    await renderQr(body);
}

async function loadPending(): Promise<void> {
    if (!todaySession.value) {
        pending.value = [];

        return;
    }

    const params = new URLSearchParams({
        type: type.value,
    });

    if (branchId.value) {
        params.set('branch_id', String(branchId.value));
    }

    const response = await fetch(
        `/attendance/kiosk/pending?${params.toString()}`,
        {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        },
    );

    if (!response.ok) {
        return;
    }

    const body = (await response.json()) as { pending: PendingPerson[] };
    pending.value = body.pending ?? [];
}

async function toggleSession(open: boolean): Promise<void> {
    processing.value = true;

    try {
        const response = await requestSession(
            open
                ? '/attendance/qr-sessions/open'
                : '/attendance/qr-sessions/close',
            'POST',
        );
        const body = (await response.json()) as QrPayload;

        applyDay(body.day);

        if (!response.ok) {
            clearQr();
            error.value = body.message ?? 'Unable to update the session.';

            return;
        }

        if (open && (body.entry_code || body.token)) {
            await renderQr(body);

            return;
        }

        clearQr();
        error.value = '';
    } finally {
        processing.value = false;
    }
}

onMounted(async () => {
    await Promise.all([loadSession(), loadPending()]);

    let refreshing = false;

    timer = window.setInterval(async () => {
        if (!isOpen.value || refreshing) {
            return;
        }

        remaining.value = Math.max(0, remaining.value - 1);

        if (remaining.value > 2) {
            return;
        }

        refreshing = true;

        try {
            await loadSession();
        } finally {
            refreshing = false;
        }
    }, 1000);

    pendingTimer = window.setInterval(() => {
        void loadPending();
    }, 3000);
});

onUnmounted(() => {
    if (timer) {
        window.clearInterval(timer);
    }

    if (pendingTimer) {
        window.clearInterval(pendingTimer);
    }
});

watch([type, branchId], () => {
    void loadSession();
    void loadPending();
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
                    {{
                        type === 'check_in'
                            ? trans('scan.check_in')
                            : trans('scan.check_out')
                    }}
                </p>
                <p
                    class="mb-6 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                    :class="
                        isOpen
                            ? 'bg-emerald-100 text-emerald-800'
                            : 'bg-muted text-muted-foreground'
                    "
                >
                    <span
                        class="size-2 rounded-full"
                        :class="
                            isOpen ? 'bg-emerald-600' : 'bg-muted-foreground/50'
                        "
                    />
                    {{
                        isOpen
                            ? trans('kiosk.session_open')
                            : trans('kiosk.session_closed')
                    }}
                </p>
                <div
                    v-if="qrPng"
                    class="flex aspect-square w-full max-w-xl items-center justify-center overflow-hidden rounded-[1.7rem] border bg-white p-6"
                >
                    <img
                        :src="qrPng"
                        alt=""
                        class="size-full bg-white object-contain [image-rendering:pixelated]"
                    />
                </div>
                <div
                    v-else
                    class="flex aspect-square w-full max-w-xl items-center justify-center rounded-[1.7rem] border border-dashed text-sm text-muted-foreground"
                >
                    {{ trans('kiosk.open_to_show') }}
                </div>
                <div v-if="session?.entry_code" class="mt-6 space-y-2">
                    <p
                        class="text-xs tracking-[0.2em] text-muted-foreground uppercase"
                    >
                        {{ trans('kiosk.code_hint') }}
                    </p>
                    <p
                        class="font-mono text-4xl font-semibold tracking-[0.35em] text-foreground"
                    >
                        {{ session.entry_code }}
                    </p>
                </div>
                <div
                    v-if="isOpen && session"
                    class="mt-4 space-y-1 text-sm text-muted-foreground"
                >
                    <div class="flex items-center gap-3">
                        <span class="size-2 rounded-full bg-primary" />
                        {{ trans('kiosk.refreshes', { seconds: remaining }) }}
                    </div>
                    <p class="text-xs">
                        {{
                            trans('kiosk.ttl_hint', {
                                seconds: ttlSeconds,
                                digits: props.entryCodeLength,
                            })
                        }}
                    </p>
                </div>
                <p v-if="error" class="mt-3 text-sm text-destructive">
                    {{ error }}
                </p>
            </div>

            <div class="space-y-5 rounded-[2rem] border bg-card p-6 shadow-sm">
                <div class="space-y-2">
                    <Label for="type">{{ trans('kiosk.mode') }}</Label>
                    <select id="type" v-model="type" class="field-control">
                        <option value="check_in">
                            {{ trans('scan.check_in') }}
                        </option>
                        <option value="check_out">
                            {{ trans('scan.check_out') }}
                        </option>
                    </select>
                </div>
                <div class="space-y-2">
                    <Label for="branch">{{ trans('common.branch') }}</Label>
                    <select
                        id="branch"
                        v-model.number="branchId"
                        class="field-control"
                    >
                        <option
                            v-for="branch in branches"
                            :key="branch.id"
                            :value="branch.id"
                        >
                            {{ branch.name }}
                        </option>
                    </select>
                </div>
                <Button
                    v-if="!isOpen"
                    class="w-full rounded-full"
                    :disabled="processing"
                    @click="toggleSession(true)"
                >
                    {{ trans('kiosk.open_session') }}
                </Button>
                <Button
                    v-if="isOpen"
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
                <div class="space-y-3 border-t pt-4">
                    <div class="flex items-center justify-between gap-2">
                        <Label for="qr_ttl_seconds">{{
                            trans('settings.timer.heading')
                        }}</Label>
                        <Link
                            :href="editTimer()"
                            class="text-xs text-muted-foreground underline-offset-4 hover:underline"
                        >
                            {{ trans('kiosk.timer_settings') }}
                        </Link>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Button
                            v-for="preset in presets"
                            :key="preset"
                            type="button"
                            :variant="
                                Number(ttlSeconds) === preset
                                    ? 'default'
                                    : 'outline'
                            "
                            class="rounded-full"
                            :disabled="ttlSaving"
                            @click="ttlSeconds = preset"
                        >
                            {{ preset }}
                        </Button>
                    </div>
                    <Input
                        id="qr_ttl_seconds"
                        v-model="ttlSeconds"
                        type="number"
                        :min="minTtl"
                        :max="maxTtl"
                    />
                    <p class="text-xs text-muted-foreground">
                        {{
                            trans('settings.timer.hint', {
                                min: minTtl,
                                max: maxTtl,
                            })
                        }}
                    </p>
                    <p v-if="ttlError" class="text-sm text-destructive">
                        {{ ttlError }}
                    </p>
                    <Button
                        variant="outline"
                        class="w-full rounded-full"
                        :disabled="ttlSaving"
                        @click="saveTimer"
                    >
                        {{ trans('common.save') }}
                    </Button>
                </div>
            </div>
        </div>

        <div
            v-if="todaySession"
            class="rounded-[2rem] border bg-card p-6 shadow-sm"
        >
            <div
                class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between"
            >
                <div>
                    <p class="text-xs tracking-[0.3em] text-primary uppercase">
                        {{
                            type === 'check_in'
                                ? trans('kiosk.pending_check_in_title')
                                : trans('kiosk.pending_check_out_title')
                        }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        {{
                            type === 'check_in'
                                ? trans('kiosk.pending_check_in_hint')
                                : trans('kiosk.pending_check_out_hint')
                        }}
                    </p>
                </div>
                <p class="text-sm font-medium text-muted-foreground">
                    {{
                        trans('kiosk.pending_count', { count: pending.length })
                    }}
                </p>
            </div>

            <div
                v-if="pending.length === 0"
                class="rounded-2xl border border-dashed px-4 py-8 text-center text-sm text-muted-foreground"
            >
                {{
                    type === 'check_in'
                        ? trans('kiosk.pending_check_in_empty')
                        : trans('kiosk.pending_check_out_empty')
                }}
            </div>

            <div
                v-else
                class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5"
            >
                <div
                    v-for="(person, index) in pending"
                    :key="person.id"
                    class="flex items-center gap-3 rounded-2xl border bg-muted/20 px-4 py-3"
                >
                    <span
                        class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary tabular-nums"
                    >
                        {{ index + 1 }}
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm leading-5 font-medium">
                            {{ person.name }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            {{
                                person.department?.name ??
                                trans('common.no_department')
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
