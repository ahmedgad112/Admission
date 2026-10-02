<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { trans } from '@/composables/useTrans';
import { userRoleTone, userStatusTone } from '@/lib/status';

type StaffRow = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    role: string;
    role_label: string;
    status: string;
    branch?: { id: number; name: string } | null;
    department?: { id: number; name: string } | null;
    shift?: { id: number; name: string } | null;
    leave_days: number;
    can_delete: boolean;
    can_adjust_leave: boolean;
};

const props = defineProps<{
    staff: { data: StaffRow[] };
    filters: {
        search: string;
        role: string;
        status: string;
        department_id: string;
    };
    roleOptions: { value: string; label: string }[];
    departments: {
        id: number;
        name: string;
        branch_id: number;
        branch: string | null;
    }[];
    canCreate: boolean;
}>();

const page = usePage();
const search = ref(props.filters.search);
const importDepartmentId = ref('');
const importInput = ref<HTMLInputElement | null>(null);
const leaveInput = ref<HTMLInputElement | null>(null);
const selected = ref<number[]>([]);
const bulk = useForm({
    user_ids: [] as number[],
    direction: 'add' as 'add' | 'deduct',
    days: 1,
    note: '',
});

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.staff', href: '/staff' },
        ],
    },
});

const hasActiveFilters = computed(
    () =>
        Boolean(props.filters.search) ||
        Boolean(props.filters.role) ||
        Boolean(props.filters.status) ||
        Boolean(props.filters.department_id),
);

function filter(
    key: 'search' | 'role' | 'status' | 'department_id',
    value: string,
): void {
    router.get(
        '/staff',
        {
            search:
                key === 'search'
                    ? value || undefined
                    : props.filters.search || undefined,
            role:
                key === 'role'
                    ? value || undefined
                    : props.filters.role || undefined,
            status:
                key === 'status'
                    ? value || undefined
                    : props.filters.status || undefined,
            department_id:
                key === 'department_id'
                    ? value || undefined
                    : props.filters.department_id || undefined,
        },
        { preserveState: true, replace: true },
    );
}

function destroy(member: StaffRow): void {
    if (!confirm(trans('staff.delete_confirm', { name: member.name }))) {
        return;
    }

    router.delete(`/staff/${member.id}`);
}

function impersonate(id: number): void {
    router.post(`/staff/${id}/impersonate`);
}

function canImpersonate(member: StaffRow): boolean {
    return (
        Boolean(page.props.can?.impersonate) &&
        member.id !== page.props.auth.user.id
    );
}

function pickImportFile(): void {
    if (!importDepartmentId.value) {
        toast.error(trans('staff.import_pick_department'));

        return;
    }

    importInput.value?.click();
}

const adjustableIds = computed(() =>
    props.staff.data
        .filter((member) => member.can_adjust_leave)
        .map((member) => member.id),
);

const selectedOnPage = computed(() =>
    selected.value.filter((id) => adjustableIds.value.includes(id)),
);

const allSelected = computed(
    () =>
        adjustableIds.value.length > 0 &&
        adjustableIds.value.every((id) => selected.value.includes(id)),
);

function toggleMember(id: number): void {
    if (selected.value.includes(id)) {
        selected.value = selected.value.filter((current) => current !== id);

        return;
    }

    selected.value = [...selected.value, id];
}

function toggleAll(): void {
    if (allSelected.value) {
        selected.value = selected.value.filter(
            (id) => !adjustableIds.value.includes(id),
        );

        return;
    }

    selected.value = [
        ...new Set([...selected.value, ...adjustableIds.value]),
    ];
}

function applyBulk(): void {
    bulk.user_ids = selectedOnPage.value;
    bulk.post('/staff/leave-days', {
        preserveScroll: true,
        onSuccess: () => {
            selected.value = [];
            bulk.reset('days', 'note');
            bulk.days = 1;
        },
    });
}

function importLeaveSheet(event: Event): void {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (!file) {
        return;
    }

    router.post(
        '/staff/leave-days/import',
        { file },
        {
            forceFormData: true,
            onFinish: () => {
                input.value = '';
            },
        },
    );
}

function importSheet(event: Event): void {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (!file) {
        return;
    }

    router.post(
        '/staff/import',
        {
            file,
            department_id: importDepartmentId.value,
        },
        {
            forceFormData: true,
            onFinish: () => {
                input.value = '';
            },
        },
    );
}

function hasActions(member: StaffRow): boolean {
    return true;
}
</script>

<template>
    <Head :title="trans('staff.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('staff.eyebrow')"
            :title="trans('staff.title')"
            :description="trans('staff.description')"
        >
            <template #actions>
                <Button
                    v-if="page.props.can?.managePermissions"
                    variant="outline"
                    class="rounded-full"
                    as-child
                >
                    <Link href="/permissions">{{
                        trans('nav.permissions')
                    }}</Link>
                </Button>
                <Button v-if="canCreate" class="rounded-full" as-child>
                    <Link href="/staff/create">{{ trans('staff.new') }}</Link>
                </Button>
                <Button
                    v-if="canCreate"
                    variant="outline"
                    class="rounded-full"
                    as-child
                >
                    <a href="/staff/import/template">{{
                        trans('staff.import_template')
                    }}</a>
                </Button>
                <select
                    v-if="canCreate"
                    v-model="importDepartmentId"
                    class="field-control max-w-52"
                >
                    <option value="">
                        {{ trans('staff.import_department') }}
                    </option>
                    <option
                        v-for="department in departments"
                        :key="department.id"
                        :value="String(department.id)"
                    >
                        {{
                            department.branch
                                ? `${department.name} — ${department.branch}`
                                : department.name
                        }}
                    </option>
                </select>
                <Button
                    v-if="canCreate"
                    variant="outline"
                    class="rounded-full"
                    type="button"
                    @click="pickImportFile"
                >
                    {{ trans('staff.import') }}
                </Button>
                <input
                    v-if="canCreate"
                    ref="importInput"
                    type="file"
                    accept=".xlsx,.csv,text/csv"
                    class="hidden"
                    @change="importSheet"
                />
                <Button
                    v-if="canCreate"
                    variant="outline"
                    class="rounded-full"
                    as-child
                >
                    <a href="/staff/leave-days/template">{{
                        trans('staff.leave_import_template')
                    }}</a>
                </Button>
                <Button
                    v-if="canCreate"
                    variant="outline"
                    class="rounded-full"
                    type="button"
                    @click="leaveInput?.click()"
                >
                    {{ trans('staff.leave_import') }}
                </Button>
                <input
                    v-if="canCreate"
                    ref="leaveInput"
                    type="file"
                    accept=".xlsx,.csv,text/csv"
                    class="hidden"
                    @change="importLeaveSheet"
                />
            </template>
        </PageHeader>

        <div class="flex flex-wrap gap-3">
            <Input
                v-model="search"
                type="search"
                class="max-w-64"
                :placeholder="trans('staff.search')"
                @keyup.enter="filter('search', search)"
                @change="filter('search', search)"
            />
            <select
                v-if="departments.length > 0"
                :value="filters.department_id"
                class="field-control max-w-52"
                @change="
                    filter(
                        'department_id',
                        ($event.target as HTMLSelectElement).value,
                    )
                "
            >
                <option value="">{{ trans('staff.all_departments') }}</option>
                <option
                    v-for="department in departments"
                    :key="department.id"
                    :value="String(department.id)"
                >
                    {{
                        department.branch
                            ? `${department.name} — ${department.branch}`
                            : department.name
                    }}
                </option>
            </select>
            <select
                :value="filters.role"
                class="field-control max-w-48"
                @change="
                    filter('role', ($event.target as HTMLSelectElement).value)
                "
            >
                <option value="">{{ trans('staff.all_roles') }}</option>
                <option
                    v-for="role in roleOptions"
                    :key="role.value"
                    :value="role.value"
                >
                    {{ role.label }}
                </option>
            </select>
            <select
                :value="filters.status"
                class="field-control max-w-48"
                @change="
                    filter('status', ($event.target as HTMLSelectElement).value)
                "
            >
                <option value="">{{ trans('staff.all_statuses') }}</option>
                <option value="active">{{ trans('status.active') }}</option>
                <option value="inactive">{{ trans('status.inactive') }}</option>
                <option value="suspended">
                    {{ trans('status.suspended') }}
                </option>
            </select>
        </div>

        <div
            v-if="canCreate && adjustableIds.length > 0"
            class="flex flex-wrap items-end gap-3 rounded-2xl border p-4"
        >
            <label class="flex items-center gap-2 text-sm">
                <input
                    type="checkbox"
                    class="size-4"
                    :checked="allSelected"
                    @change="toggleAll"
                />
                {{ trans('staff.select_all') }}
            </label>
            <p class="text-sm text-muted-foreground">
                {{
                    trans('staff.selected_count', {
                        count: selectedOnPage.length,
                    })
                }}
            </p>
            <select v-model="bulk.direction" class="field-control max-w-40">
                <option value="add">{{ trans('staff.add_days') }}</option>
                <option value="deduct">
                    {{ trans('staff.deduct_days') }}
                </option>
            </select>
            <Input
                v-model.number="bulk.days"
                type="number"
                min="1"
                max="365"
                class="max-w-28"
                :placeholder="trans('staff.days_count')"
            />
            <Button
                class="rounded-full"
                type="button"
                :disabled="bulk.processing || selectedOnPage.length === 0"
                @click="applyBulk"
            >
                {{ trans('staff.apply_to_selected') }}
            </Button>
            <p v-if="bulk.errors.days" class="w-full text-sm text-destructive">
                {{ bulk.errors.days }}
            </p>
            <p
                v-if="bulk.errors.user_ids"
                class="w-full text-sm text-destructive"
            >
                {{ bulk.errors.user_ids }}
            </p>
        </div>

        <div
            v-if="staff.data.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{
                hasActiveFilters
                    ? trans('staff.no_match')
                    : trans('staff.empty')
            }}
        </div>
        <template v-else>
            <div class="grid gap-4 md:hidden">
                <Card
                    v-for="member in staff.data"
                    :key="member.id"
                    class="h-full shadow-sm"
                >
                    <CardHeader>
                        <div class="flex items-start gap-3">
                            <input
                                v-if="member.can_adjust_leave"
                                type="checkbox"
                                class="mt-1 size-4"
                                :checked="selected.includes(member.id)"
                                :aria-label="member.name"
                                @change="toggleMember(member.id)"
                            />
                            <div>
                                <CardTitle class="text-lg">
                                    <Link
                                        :href="`/staff/${member.id}`"
                                        class="hover:underline"
                                    >
                                        {{ member.name }}
                                    </Link>
                                </CardTitle>
                                <CardDescription>{{
                                    member.email
                                }}</CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <span
                                :class="[
                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
                                    userRoleTone(member.role),
                                ]"
                            >
                                {{ member.role_label }}
                            </span>
                            <StatusBadge
                                :value="member.status"
                                :tone="userStatusTone(member.status)"
                            />
                        </div>
                        <dl class="grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    {{ trans('common.branch') }}
                                </dt>
                                <dd class="font-medium">
                                    {{ member.branch?.name ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    {{ trans('common.department') }}
                                </dt>
                                <dd class="font-medium">
                                    {{ member.department?.name ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    {{ trans('common.shift') }}
                                </dt>
                                <dd class="font-medium">
                                    {{ member.shift?.name ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    {{ trans('staff.leave_days') }}
                                </dt>
                                <dd class="font-medium">
                                    {{ member.leave_days }}
                                </dd>
                            </div>
                        </dl>
                    </CardContent>
                    <CardFooter
                        v-if="hasActions(member)"
                        class="mt-auto flex flex-wrap gap-2 border-t"
                    >
                        <Button
                            variant="outline"
                            size="sm"
                            class="rounded-full"
                            as-child
                        >
                            <Link :href="`/staff/${member.id}`">{{
                                trans('staff.profile')
                            }}</Link>
                        </Button>
                        <Button
                            v-if="canImpersonate(member)"
                            variant="secondary"
                            size="sm"
                            class="rounded-full"
                            @click="impersonate(member.id)"
                        >
                            {{ trans('staff.login_as') }}
                        </Button>
                        <Button
                            v-if="canCreate"
                            variant="outline"
                            size="sm"
                            class="rounded-full"
                            as-child
                        >
                            <Link :href="`/staff/${member.id}/edit`">{{
                                trans('common.edit')
                            }}</Link>
                        </Button>
                        <Button
                            v-if="member.can_delete"
                            variant="destructive"
                            size="sm"
                            class="rounded-full"
                            @click="destroy(member)"
                        >
                            {{ trans('common.delete') }}
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <div class="hidden overflow-x-auto rounded-2xl border md:block">
                <table class="w-full min-w-[52rem] text-start text-sm">
                    <thead
                        class="bg-muted/40 text-xs tracking-wide text-muted-foreground uppercase"
                    >
                        <tr>
                            <th
                                v-if="adjustableIds.length > 0"
                                class="px-4 py-3 font-semibold"
                            >
                                <input
                                    type="checkbox"
                                    class="size-4"
                                    :checked="allSelected"
                                    :aria-label="trans('staff.select_all')"
                                    @change="toggleAll"
                                />
                            </th>
                            <th class="px-4 py-3 font-semibold">
                                {{ trans('common.name') }}
                            </th>
                            <th class="px-4 py-3 font-semibold">
                                {{ trans('common.email') }}
                            </th>
                            <th class="px-4 py-3 font-semibold">
                                {{ trans('common.role') }}
                            </th>
                            <th class="px-4 py-3 font-semibold">
                                {{ trans('common.status') }}
                            </th>
                            <th class="px-4 py-3 font-semibold">
                                {{ trans('common.branch') }}
                            </th>
                            <th class="px-4 py-3 font-semibold">
                                {{ trans('common.department') }}
                            </th>
                            <th class="px-4 py-3 font-semibold">
                                {{ trans('common.action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="member in staff.data"
                            :key="member.id"
                            class="border-t"
                        >
                            <td
                                v-if="adjustableIds.length > 0"
                                class="px-4 py-3"
                            >
                                <input
                                    v-if="member.can_adjust_leave"
                                    type="checkbox"
                                    class="size-4"
                                    :checked="selected.includes(member.id)"
                                    :aria-label="member.name"
                                    @change="toggleMember(member.id)"
                                />
                            </td>
                            <td class="px-4 py-3 font-medium">
                                <Link
                                    :href="`/staff/${member.id}`"
                                    class="hover:underline"
                                >
                                    {{ member.name }}
                                </Link>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">
                                {{ member.email }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    :class="[
                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
                                        userRoleTone(member.role),
                                    ]"
                                >
                                    {{ member.role_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <StatusBadge
                                    :value="member.status"
                                    :tone="userStatusTone(member.status)"
                                />
                            </td>
                            <td class="px-4 py-3">
                                {{ member.branch?.name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ member.department?.name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        class="rounded-full"
                                        as-child
                                    >
                                        <Link :href="`/staff/${member.id}`">{{
                                            trans('staff.profile')
                                        }}</Link>
                                    </Button>
                                    <Button
                                        v-if="canImpersonate(member)"
                                        variant="secondary"
                                        size="sm"
                                        class="rounded-full"
                                        @click="impersonate(member.id)"
                                    >
                                        {{ trans('staff.login_as') }}
                                    </Button>
                                    <Button
                                        v-if="canCreate"
                                        variant="outline"
                                        size="sm"
                                        class="rounded-full"
                                        as-child
                                    >
                                        <Link
                                            :href="`/staff/${member.id}/edit`"
                                            >{{ trans('common.edit') }}</Link
                                        >
                                    </Button>
                                    <Button
                                        v-if="member.can_delete"
                                        variant="destructive"
                                        size="sm"
                                        class="rounded-full"
                                        @click="destroy(member)"
                                    >
                                        {{ trans('common.delete') }}
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </div>
</template>
