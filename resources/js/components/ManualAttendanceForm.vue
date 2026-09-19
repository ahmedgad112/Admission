<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { UserPlus, X } from '@lucide/vue';
import { onClickOutside } from '@vueuse/core';
import { computed, ref, watch } from 'vue';
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

function personLabel(person: Candidate): string {
    return person.department
        ? `${person.name} · ${person.department.name}`
        : person.name;
}

const form = useForm({
    user_id: '',
    check_in: defaultCheckIn(props.date),
    check_out: '',
});

const query = ref('');
const open = ref(false);
const pickerRoot = ref<HTMLElement | null>(null);

const firstError = computed(() => Object.values(form.errors)[0] ?? '');
const canSubmit = computed(
    () => Boolean(form.user_id) && Boolean(form.check_in) && !form.processing,
);

const selectedPerson = computed(
    () =>
        props.candidates.find(
            (person) => String(person.id) === String(form.user_id),
        ) ?? null,
);

const filteredCandidates = computed(() => {
    const needle = query.value.trim().toLowerCase();

    if (needle === '') {
        return props.candidates;
    }

    return props.candidates.filter((person) => {
        const haystack =
            `${person.name} ${person.department?.name ?? ''}`.toLowerCase();

        return haystack.includes(needle);
    });
});

onClickOutside(pickerRoot, () => {
    open.value = false;
});

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
            !candidates.some(
                (person) => String(person.id) === String(form.user_id),
            )
        ) {
            clearPerson();
        }
    },
);

function clearPerson(): void {
    form.user_id = '';
    query.value = '';
    open.value = false;
}

function onQueryInput(): void {
    if (form.user_id !== '') {
        form.user_id = '';
    }

    open.value = true;
}

function selectPerson(person: Candidate): void {
    form.user_id = String(person.id);
    query.value = personLabel(person);
    open.value = false;
}

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
                query.value = '';
                open.value = false;
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
        <div
            class="grid gap-3 md:grid-cols-[1.4fr_repeat(2,minmax(0,8rem))_auto]"
        >
            <div ref="pickerRoot" class="relative space-y-1">
                <Label for="manual-person">{{
                    trans('attendance.manual_person')
                }}</Label>
                <div class="relative">
                    <Input
                        id="manual-person"
                        v-model="query"
                        type="search"
                        autocomplete="off"
                        :placeholder="trans('attendance.search_person')"
                        class="pe-9"
                        @focus="open = true"
                        @input="onQueryInput"
                    />
                    <button
                        v-if="selectedPerson || query !== ''"
                        type="button"
                        class="absolute end-2 top-1/2 -translate-y-1/2 rounded-full p-1 text-muted-foreground hover:bg-muted hover:text-foreground"
                        :aria-label="trans('roster.clear')"
                        @click="clearPerson"
                    >
                        <X class="size-3.5" />
                    </button>
                </div>
                <div
                    v-if="open"
                    class="absolute z-20 mt-1 max-h-56 w-full overflow-y-auto rounded-xl border bg-popover p-1 shadow-md"
                >
                    <p
                        v-if="filteredCandidates.length === 0"
                        class="px-3 py-6 text-center text-sm text-muted-foreground"
                    >
                        {{ trans('attendance.no_person_match') }}
                    </p>
                    <button
                        v-for="person in filteredCandidates"
                        :key="person.id"
                        type="button"
                        class="flex w-full flex-col rounded-lg px-3 py-2 text-start hover:bg-muted/70"
                        :class="
                            String(person.id) === String(form.user_id)
                                ? 'bg-muted'
                                : ''
                        "
                        @mousedown.prevent="selectPerson(person)"
                    >
                        <span class="text-sm font-medium">{{
                            person.name
                        }}</span>
                        <span class="text-xs text-muted-foreground">
                            {{
                                person.department?.name ??
                                trans('common.no_department')
                            }}
                        </span>
                    </button>
                </div>
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
