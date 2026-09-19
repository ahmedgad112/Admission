<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { UserPlus } from '@lucide/vue';
import { computed, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Candidate = {
    id: number;
    name: string;
    department?: { id: number; name: string } | null;
};

const props = defineProps<{
    date: string;
    candidates: Candidate[];
    attendanceDayId?: number | null;
}>();

function localTime(): string {
    const now = new Date();

    return `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
}

function defaultCheckIn(date: string): string {
    return date === nowYearMonthDay() ? localTime() : '09:00';
}

function nowYearMonthDay(): string {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

const form = useForm({
    user_id: '',
    check_in: defaultCheckIn(props.date),
    check_out: '',
});

const firstError = computed(() => Object.values(form.errors)[0] ?? '');
const canSubmit = computed(
    () => Boolean(form.user_id) && Boolean(form.check_in) && !form.processing,
);

watch(
    () => props.date,
    (date) => {
        form.check_in = defaultCheckIn(date);
        form.check_out = '';
    },
);

watch(
    () => props.candidates,
    (candidates) => {
        if (
            form.user_id !== '' &&
            !candidates.some((person) => String(person.id) === String(form.user_id))
        ) {
            form.user_id = '';
        }
    },
);

function submit(): void {
    form
        .transform(() => ({
            date: props.date,
            attendance_day_id: props.attendanceDayId || null,
            entries: [
                {
                    user_id: Number(form.user_id),
                    check_in: form.check_in || null,
                    check_out: form.check_out || null,
                },
            ],
        }))
        .put('/attendance/entries', {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                form.check_in = defaultCheckIn(props.date);
            },
        });
}
</script>

<template>
    <div
        v-if="candidates.length > 0"
        class="rounded-2xl border bg-muted/20 p-4"
    >
        <div class="mb-3 flex items-start gap-2">
            <UserPlus class="mt-0.5 size-4 shrink-0 text-muted-foreground" />
            <div>
                <p class="text-sm font-medium">
                    {{ trans('attendance.manual_title') }}
                </p>
                <p class="text-xs text-muted-foreground">
                    {{ trans('attendance.manual_description') }}
                </p>
            </div>
        </div>
        <p v-if="firstError" class="pb-3 text-sm text-destructive">
            {{ firstError }}
        </p>
        <div class="grid gap-3 md:grid-cols-[1.4fr_repeat(2,minmax(0,8rem))_auto]">
            <div class="space-y-1">
                <Label for="manual-person">{{
                    trans('attendance.manual_person')
                }}</Label>
                <select
                    id="manual-person"
                    v-model="form.user_id"
                    class="field-control"
                >
                    <option value="">
                        {{ trans('attendance.choose_person') }}
                    </option>
                    <option
                        v-for="person in candidates"
                        :key="person.id"
                        :value="String(person.id)"
                    >
                        {{ person.name
                        }}{{
                            person.department
                                ? ` · ${person.department.name}`
                                : ''
                        }}
                    </option>
                </select>
            </div>
            <div class="space-y-1">
                <Label for="manual-in">{{ trans('common.in') }}</Label>
                <Input
                    id="manual-in"
                    v-model="form.check_in"
                    type="time"
                    class="tabular-nums"
                />
            </div>
            <div class="space-y-1">
                <Label for="manual-out">{{ trans('common.out') }}</Label>
                <Input
                    id="manual-out"
                    v-model="form.check_out"
                    type="time"
                    class="tabular-nums"
                />
            </div>
            <div class="flex items-end">
                <Button
                    class="w-full rounded-full"
                    :disabled="!canSubmit"
                    @click="submit"
                >
                    {{ trans('attendance.manual_submit') }}
                </Button>
            </div>
        </div>
    </div>
</template>
