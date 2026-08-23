<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';
import { getDeviceUuid } from '@/lib/device';

type DaySession = {
    date: string;
    check_in_starts_at: string;
    check_in_ends_at: string;
    check_out_starts_at: string;
    check_out_ends_at: string;
    check_in_is_open: boolean;
    check_out_is_open: boolean;
};

defineProps<{
    day: DaySession | null;
    isScheduled: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.scan', href: '/attendance/scan' },
        ],
    },
});

const mode = ref<'check-in' | 'check-out'>('check-in');
const status = ref(trans('scan.requesting'));
const video = ref<HTMLVideoElement | null>(null);
let stream: MediaStream | null = null;
let scanTimer: number | undefined;

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
    stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'environment' },
    });

    if (video.value) {
        video.value.srcObject = stream;
        await video.value.play();
    }

    const Detector = (
        window as Window & {
            BarcodeDetector?: new (options: { formats: string[] }) => {
                detect: (source: ImageBitmapSource) => Promise<Array<{ rawValue: string }>>;
            };
        }
    ).BarcodeDetector;

    if (!Detector || !video.value) {
        status.value = trans('scan.camera_ready');
        return;
    }

    const detector = new Detector({ formats: ['qr_code'] });
    status.value = trans('scan.point_camera');

    scanTimer = window.setInterval(async () => {
        if (!video.value || form.token) {
            return;
        }

        try {
            const codes = await detector.detect(video.value);
            const value = codes[0]?.rawValue;

            if (value) {
                form.token = value;
                status.value = trans('scan.qr_detected');
            }
        } catch {
            // Keep scanning.
        }
    }, 700);
}

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
        status.value =
            error instanceof Error
                ? error.message
                : trans('scan.camera_failed');
    }
});

onUnmounted(() => {
    if (scanTimer) {
        window.clearInterval(scanTimer);
    }

    stream?.getTracks().forEach((track) => track.stop());
});

function submit(): void {
    const url = mode.value === 'check-in' ? '/attendance/check-in' : '/attendance/check-out';
    form.post(url, { preserveScroll: true });
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
                    muted
                    playsinline
                />
                <div class="pointer-events-none absolute inset-4 rounded-3xl border-2 border-white/70 sm:inset-8" />
                <p class="absolute inset-x-0 bottom-0 bg-black/55 px-4 py-3 text-sm text-white">
                    {{ status }}
                </p>
            </div>

            <div class="space-y-4 rounded-[2rem] border bg-card p-6 shadow-sm">
                <p v-if="!day" class="text-sm text-destructive">
                    {{ trans('scan.no_session') }}
                </p>
                <p v-if="day && !isScheduled" class="text-sm text-destructive">
                    {{ trans('scan.not_rostered') }}
                </p>
                <p
                    v-if="day && mode === 'check-in' && !day.check_in_is_open"
                    class="text-sm text-destructive"
                >
                    {{ trans('scan.check_in_closed') }}
                </p>
                <p
                    v-if="day && mode === 'check-out' && !day.check_out_is_open"
                    class="text-sm text-destructive"
                >
                    {{ trans('scan.check_out_closed') }}
                </p>
                <p v-if="(form.errors as Record<string, string>).attendance" class="text-sm text-destructive">
                    {{ (form.errors as Record<string, string>).attendance }}
                </p>
                <div class="space-y-2">
                    <Label for="mode">{{ trans('common.action') }}</Label>
                    <select id="mode" v-model="mode" class="field-control">
                        <option value="check-in">{{ trans('scan.check_in') }}</option>
                        <option value="check-out">{{ trans('scan.check_out') }}</option>
                    </select>
                </div>
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
                    {{ mode === 'check-in' ? trans('scan.check_in') : trans('scan.check_out') }}
                </Button>
            </div>
        </div>
    </div>
</template>
