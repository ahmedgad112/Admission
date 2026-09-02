<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { CheckCircle2 } from '@lucide/vue';
import { onMounted, onUnmounted, ref, watch } from 'vue';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';
import { getDeviceUuid } from '@/lib/device';
import {
    createQrDetector,
    drawVideoFrame,
    normalizeScannedValue,
    startRearCamera,
} from '@/lib/qr-scan';
import type { QrFrameDetector } from '@/lib/qr-scan';

type DaySession = {
    date: string;
    check_in_starts_at: string;
    check_in_ends_at: string;
    check_out_starts_at: string;
    check_out_ends_at: string;
    check_in_is_open: boolean;
    check_out_is_open: boolean;
};

const props = defineProps<{
    day: DaySession | null;
    recorded: 'check_in' | 'check_out' | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.scan', href: '/attendance/scan' },
        ],
    },
});

const status = ref(trans('scan.requesting'));
const cameraFailed = ref(false);
const successOpen = ref(props.recorded !== null);
const video = ref<HTMLVideoElement | null>(null);
const canvas = document.createElement('canvas');
let stream: MediaStream | null = null;
let scanTimer: number | undefined;
let detectQr: QrFrameDetector | null = null;
let scanning = false;
let detecting = false;
let lastScanAt = 0;
let retryAfter = 0;

const form = useForm({
    token: '',
    latitude: 0,
    longitude: 0,
    device_uuid: '',
});

async function locate(): Promise<void> {
    await new Promise<void>((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                form.latitude = position.coords.latitude;
                form.longitude = position.coords.longitude;
                resolve();
            },
            () => reject(new Error(trans('scan.location_required'))),
            { enableHighAccuracy: true, timeout: 10000 },
        );
    });
}

async function startCamera(): Promise<void> {
    stream?.getTracks().forEach((track) => track.stop());
    stream = await startRearCamera();

    if (!video.value) {
        throw new Error(trans('scan.camera_failed'));
    }

    video.value.srcObject = stream;
    await video.value.play();

    detectQr ??= await createQrDetector();
    cameraFailed.value = false;
    status.value = trans('scan.point_camera');
    startScanLoop();
}

function startScanLoop(): void {
    stopScanLoop();
    scanning = true;

    const tick = (): void => {
        if (!scanning) {
            return;
        }

        void scanFrame();
        scanTimer = window.requestAnimationFrame(tick);
    };

    scanTimer = window.requestAnimationFrame(tick);
}

function stopScanLoop(): void {
    scanning = false;

    if (scanTimer) {
        window.cancelAnimationFrame(scanTimer);
        scanTimer = undefined;
    }
}

async function scanFrame(): Promise<void> {
    if (
        detecting ||
        form.processing ||
        form.token ||
        !detectQr ||
        !video.value ||
        Date.now() < retryAfter
    ) {
        return;
    }

    const now = performance.now();

    if (now - lastScanAt < 250) {
        return;
    }

    lastScanAt = now;

    if (!drawVideoFrame(video.value, canvas)) {
        return;
    }

    detecting = true;

    try {
        const value = await detectQr(canvas);

        if (value) {
            applyScan(value);
        }
    } catch {
        // Keep scanning.
    } finally {
        detecting = false;
    }
}

function applyScan(value: string): void {
    const token = normalizeScannedValue(value);

    if (token.length < 6 || form.processing || form.token) {
        return;
    }

    form.token = token;
    status.value = trans('scan.qr_detected');
    submit();
}

watch(
    () => props.recorded,
    (value) => {
        successOpen.value = value !== null;
    },
);

onMounted(async () => {
    form.device_uuid = getDeviceUuid();

    try {
        await locate();
    } catch {
        // Location is recorded when available and never blocks check-in.
    }

    try {
        await startCamera();
    } catch (error) {
        cameraFailed.value = true;
        status.value =
            error instanceof Error
                ? error.message
                : trans('scan.camera_failed');
    }
});

onUnmounted(() => {
    stopScanLoop();
    stream?.getTracks().forEach((track) => track.stop());
});

function submit(): void {
    if (!form.token || form.processing) {
        return;
    }

    const url = '/attendance/scan';
    form.post(url, {
        preserveScroll: true,
        onSuccess: () => {
            form.token = '';
            status.value = trans('scan.point_camera');
        },
        onError: () => {
            form.token = '';
            retryAfter = Date.now() + 1500;
            status.value = trans('scan.point_camera');
        },
    });
}

async function retryCamera(): Promise<void> {
    cameraFailed.value = false;
    status.value = trans('scan.requesting');

    try {
        await startCamera();
    } catch (error) {
        cameraFailed.value = true;
        status.value =
            error instanceof Error
                ? error.message
                : trans('scan.camera_failed');
    }
}
</script>

<template>
    <Head :title="trans('scan.head')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('scan.eyebrow')"
            :title="trans('scan.title')"
            :description="trans('scan.description')"
        />

        <div class="mx-auto grid w-full max-w-3xl gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="relative overflow-hidden rounded-[2rem] bg-black shadow-xl">
                <video
                    ref="video"
                    class="aspect-[4/5] w-full object-cover md:aspect-video"
                    autoplay
                    muted
                    playsinline
                    webkit-playsinline
                />
                <div class="pointer-events-none absolute inset-4 rounded-3xl border-2 border-white/70 sm:inset-8" />
                <button
                    v-if="cameraFailed"
                    type="button"
                    class="absolute inset-0 z-10 flex items-center justify-center bg-black/55 px-6 text-sm font-medium text-white"
                    @click="retryCamera"
                >
                    {{ trans('scan.start_camera') }}
                </button>
                <p class="absolute inset-x-0 bottom-0 bg-black/55 px-4 py-3 text-sm text-white">
                    {{ status }}
                </p>
            </div>

            <div class="space-y-4 rounded-[2rem] border bg-card p-6 shadow-sm">
                <p v-if="!day" class="text-sm text-destructive">
                    {{ trans('scan.no_session') }}
                </p>
                <p
                    v-if="day && !day.check_in_is_open && !day.check_out_is_open"
                    class="text-sm text-destructive"
                >
                    {{ trans('scan.sessions_closed') }}
                </p>
                <p v-if="(form.errors as Record<string, string>).attendance" class="text-sm text-destructive">
                    {{ (form.errors as Record<string, string>).attendance }}
                </p>
                <div class="space-y-2">
                    <Label for="token">{{ trans('scan.kiosk_code') }}</Label>
                    <Input
                        id="token"
                        v-model="form.token"
                        maxlength="32"
                        inputmode="numeric"
                        :placeholder="trans('scan.code_placeholder')"
                        class="font-mono tracking-[0.2em]"
                    />
                    <p class="text-xs text-muted-foreground">
                        {{ trans('scan.code_hint') }}
                    </p>
                </div>
                <Button
                    class="w-full rounded-full"
                    :disabled="form.processing || !form.token"
                    @click="submit"
                >
                    {{ trans('scan.submit') }}
                </Button>
            </div>
        </div>

        <Dialog :open="successOpen" @update:open="successOpen = $event">
            <DialogContent class="sm:max-w-sm" :show-close-button="false">
                <div class="flex flex-col items-center gap-4 py-2 text-center">
                    <div
                        class="flex size-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400"
                    >
                        <CheckCircle2 class="size-8" />
                    </div>
                    <DialogHeader class="items-center space-y-2 text-center">
                        <DialogTitle class="text-xl">
                            {{ trans('scan.success_title') }}
                        </DialogTitle>
                        <DialogDescription class="text-base">
                            {{
                                recorded === 'check_out'
                                    ? trans('scan.success_check_out')
                                    : trans('scan.success_check_in')
                            }}
                        </DialogDescription>
                    </DialogHeader>
                    <Button class="w-full rounded-full" @click="successOpen = false">
                        {{ trans('scan.success_ok') }}
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>
