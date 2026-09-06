<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { CheckCircle2 } from '@lucide/vue';
import { computed, onMounted, ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { trans } from '@/composables/useTrans';
import { getDeviceUuid } from '@/lib/device';

const props = defineProps<{
    token: string | null;
    recorded: 'check_in' | 'check_out' | null;
}>();

defineOptions({
    layout: {
        title: 'scan.head',
        description: 'scan.description',
    },
});

const status = ref(trans('scan.recording'));
const successOpen = ref(props.recorded !== null);

const form = useForm({
    token: props.token ?? '',
    latitude: 0,
    longitude: 0,
    device_uuid: '',
});

const attendanceError = computed(
    () => (form.errors as Record<string, string>).attendance,
);

async function locate(): Promise<void> {
    await new Promise<void>((resolve) => {
        if (!navigator.geolocation) {
            resolve();

            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                form.latitude = position.coords.latitude;
                form.longitude = position.coords.longitude;
                resolve();
            },
            () => resolve(),
            { enableHighAccuracy: true, timeout: 8000 },
        );
    });
}

function submit(): void {
    if (!form.token || form.processing) {
        return;
    }

    status.value = trans('scan.qr_detected');
    form.post('/attendance/open', {
        preserveScroll: true,
        onError: () => {
            status.value = trans('scan.point_camera');
        },
    });
}

onMounted(async () => {
    form.device_uuid = getDeviceUuid();

    if (props.recorded !== null || !form.token) {
        status.value = form.token
            ? trans('scan.point_camera')
            : trans('scan.no_token');

        return;
    }

    try {
        await locate();
    } catch {
        // Location is recorded when available and never blocks check-in.
    }

    submit();
});
</script>

<template>
    <div>
        <Head :title="trans('scan.head')" />

        <div class="space-y-4">
            <p v-if="attendanceError" class="text-sm text-destructive">
                {{ attendanceError }}
            </p>
            <p class="text-sm text-muted-foreground">
                {{ status }}
            </p>
            <p v-if="!token" class="text-sm text-destructive">
                {{ trans('scan.no_token') }}
            </p>
            <Button
                v-if="token && !form.processing && recorded === null"
                class="w-full rounded-full"
                :disabled="form.processing"
                @click="submit"
            >
                {{ trans('scan.submit') }}
            </Button>
            <Button variant="outline" class="w-full rounded-full" as-child>
                <Link href="/login">{{ trans('scan.login_to_record') }}</Link>
            </Button>
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
                    <Button
                        class="w-full rounded-full"
                        @click="successOpen = false"
                    >
                        {{ trans('scan.success_ok') }}
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>
