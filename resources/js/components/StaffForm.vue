<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type Option = { id: number; name: string };
type DepartmentOption = { id: number; name: string; branch_id: number };
type LabeledOption = { value: string; label: string };
type PermissionOption = {
    value: string;
    label: string;
    description: string;
    group?: string;
};
type Member = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    role: string;
    branch_id: number | null;
    department_id: number | null;
    shift_id: number | null;
    status: string;
    leave_days: number;
    permissions: string[];
};

const props = defineProps<{
    branches: Option[];
    departments: DepartmentOption[];
    shifts: Option[];
    roles: LabeledOption[];
    statuses: LabeledOption[];
    permissionOptions: PermissionOption[];
    rolePermissions: Record<string, string[]>;
    grantablePermissions: string[];
    defaultBranchId?: number | null;
    member?: Member;
}>();

const form = useForm({
    name: props.member?.name ?? '',
    email: props.member?.email ?? '',
    phone: props.member?.phone ?? '',
    password: '',
    role: props.member?.role ?? 'employee',
    branch_id:
        props.member?.branch_id ??
        props.defaultBranchId ??
        props.branches[0]?.id ??
        '',
    department_id: props.member?.department_id ?? '',
    shift_id: props.member?.shift_id ?? '',
    status: props.member?.status ?? 'active',
    leave_days: props.member?.leave_days ?? 21,
    permissions: [...(props.member?.permissions ?? [])],
});

const branchDepartments = computed(() =>
    props.departments.filter(
        (department) => Number(department.branch_id) === Number(form.branch_id),
    ),
);

const roleDefaults = computed(() => props.rolePermissions[form.role] ?? []);

watch(
    () => form.branch_id,
    () => {
        const stillValid = branchDepartments.value.some(
            (department) =>
                Number(department.id) === Number(form.department_id),
        );

        if (!stillValid) {
            form.department_id = '';
        }
    },
);

watch(
    () => form.role,
    () => {
        form.permissions = form.permissions.filter(
            (permission) => !roleDefaults.value.includes(permission),
        );
    },
);

function isRoleDefault(permission: string): boolean {
    return roleDefaults.value.includes(permission);
}

function isPermissionChecked(permission: string): boolean {
    return isRoleDefault(permission) || form.permissions.includes(permission);
}

function isPermissionLocked(permission: string): boolean {
    if (isRoleDefault(permission)) {
        return true;
    }

    return !props.grantablePermissions.includes(permission);
}

function togglePermission(
    permission: string,
    checked: boolean | 'indeterminate',
): void {
    if (isPermissionLocked(permission) || checked === 'indeterminate') {
        return;
    }

    if (checked && !form.permissions.includes(permission)) {
        form.permissions = [...form.permissions, permission];

        return;
    }

    if (!checked) {
        form.permissions = form.permissions.filter(
            (value) => value !== permission,
        );
    }
}

function submit(createAnother = false): void {
    form.transform((data) => ({
        ...data,
        phone: data.phone || null,
        department_id: data.department_id || null,
        shift_id: data.shift_id || null,
        password: data.password || null,
        create_another: createAnother,
    }));

    if (props.member) {
        form.put(`/staff/${props.member.id}`);

        return;
    }

    form.post('/staff');
}
</script>

<template>
    <Card class="mx-auto w-full max-w-3xl shadow-sm">
        <CardContent class="space-y-4 pt-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <Label for="name">{{ trans('common.name') }}</Label>
                    <Input id="name" v-model="form.name" autocomplete="name" />
                    <p v-if="form.errors.name" class="text-sm text-destructive">
                        {{ form.errors.name }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="email">{{ trans('common.email') }}</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        autocomplete="username"
                    />
                    <p
                        v-if="form.errors.email"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="phone">{{ trans('common.phone') }}</Label>
                    <Input id="phone" v-model="form.phone" />
                    <p
                        v-if="form.errors.phone"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.phone }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="password">
                        {{ trans('common.password') }}
                        <span
                            v-if="member"
                            class="font-normal text-muted-foreground"
                        >
                            {{ trans('staff.keep_password') }}</span
                        >
                    </Label>
                    <Input
                        id="password"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                    />
                    <p
                        v-if="form.errors.password"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="role">{{ trans('common.role') }}</Label>
                    <select id="role" v-model="form.role" class="field-control">
                        <option
                            v-for="role in roles"
                            :key="role.value"
                            :value="role.value"
                        >
                            {{ trans(`roles.${role.value}`) }}
                        </option>
                    </select>
                    <p v-if="form.errors.role" class="text-sm text-destructive">
                        {{ form.errors.role }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="status">{{ trans('common.status') }}</Label>
                    <select
                        id="status"
                        v-model="form.status"
                        class="field-control"
                    >
                        <option
                            v-for="status in statuses"
                            :key="status.value"
                            :value="status.value"
                        >
                            {{ trans(`status.${status.value}`) }}
                        </option>
                    </select>
                </div>
                <div class="space-y-2">
                    <Label for="branch_id">{{ trans('common.branch') }}</Label>
                    <select
                        id="branch_id"
                        v-model="form.branch_id"
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
                    <p
                        v-if="form.errors.branch_id"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.branch_id }}
                    </p>
                </div>
                <div class="space-y-2">
                    <Label for="department_id">{{
                        trans('common.department')
                    }}</Label>
                    <select
                        id="department_id"
                        v-model="form.department_id"
                        class="field-control"
                    >
                        <option value="">{{ trans('common.none') }}</option>
                        <option
                            v-for="department in branchDepartments"
                            :key="department.id"
                            :value="department.id"
                        >
                            {{ department.name }}
                        </option>
                    </select>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <Label for="shift_id">{{
                            trans('common.shift')
                        }}</Label>
                        <Link href="/shifts" class="text-xs text-primary">{{
                            trans('shifts.manage')
                        }}</Link>
                    </div>
                    <select
                        id="shift_id"
                        v-model="form.shift_id"
                        class="field-control"
                    >
                        <option value="">{{ trans('common.none') }}</option>
                        <option
                            v-for="shift in shifts"
                            :key="shift.id"
                            :value="shift.id"
                        >
                            {{ shift.name }}
                        </option>
                    </select>
                </div>
                <div class="space-y-2">
                    <Label for="leave_days">{{
                        trans('staff.leave_days')
                    }}</Label>
                    <Input
                        id="leave_days"
                        v-model.number="form.leave_days"
                        type="number"
                        min="0"
                        max="365"
                    />
                    <p class="text-xs text-muted-foreground">
                        {{ trans('staff.leave_days_help') }}
                    </p>
                    <p
                        v-if="form.errors.leave_days"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.leave_days }}
                    </p>
                </div>
                <div class="space-y-3 md:col-span-2">
                    <div class="space-y-1">
                        <Label>{{ trans('staff.permissions') }}</Label>
                        <p class="text-xs text-muted-foreground">
                            {{ trans('staff.permissions_help') }}
                        </p>
                    </div>
                    <div class="grid gap-3 md:grid-cols-2">
                        <label
                            v-for="permission in permissionOptions"
                            :key="permission.value"
                            class="flex items-start gap-3 rounded-xl border border-border/80 bg-muted/20 p-3"
                            :class="{
                                'opacity-70': isPermissionLocked(
                                    permission.value,
                                ),
                            }"
                        >
                            <Checkbox
                                class="mt-0.5"
                                :checked="isPermissionChecked(permission.value)"
                                :disabled="isPermissionLocked(permission.value)"
                                @update:checked="
                                    (checked: boolean | 'indeterminate') =>
                                        togglePermission(
                                            permission.value,
                                            checked,
                                        )
                                "
                            />
                            <span class="space-y-1">
                                <span
                                    class="flex items-center gap-2 text-sm font-medium"
                                >
                                    {{
                                        trans(`permissions.${permission.value}`)
                                    }}
                                    <span
                                        v-if="isRoleDefault(permission.value)"
                                        class="rounded-full bg-muted px-2 py-0.5 text-[10px] font-normal text-muted-foreground"
                                    >
                                        {{
                                            trans('staff.permission_from_role')
                                        }}
                                    </span>
                                </span>
                                <span
                                    class="block text-xs text-muted-foreground"
                                >
                                    {{
                                        trans(
                                            `permissions.${permission.value}_help`,
                                        )
                                    }}
                                </span>
                            </span>
                        </label>
                    </div>
                    <p
                        v-if="form.errors.permissions"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.permissions }}
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <Button
                    class="rounded-full"
                    :disabled="form.processing"
                    @click="submit(false)"
                >
                    {{ member ? trans('staff.update') : trans('staff.save') }}
                </Button>
                <Button
                    v-if="!member"
                    type="button"
                    variant="outline"
                    class="rounded-full"
                    :disabled="form.processing"
                    @click="submit(true)"
                >
                    {{ trans('staff.save_and_create') }}
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
