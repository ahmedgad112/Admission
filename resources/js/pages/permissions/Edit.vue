<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';

type PermissionOption = {
    value: string;
    label: string;
    description: string;
    group: string;
};
type RoleOption = {
    id: number;
    value: string;
    slug: string;
    name: string;
    label: string;
    locked: boolean;
    is_system: boolean;
};
type HomePageOption = { value: string; label: string };

const props = defineProps<{
    permissionOptions: PermissionOption[];
    homePageOptions: HomePageOption[];
    roles: RoleOption[];
    rolePermissions: Record<string, string[]>;
    roleHomes: Record<string, string>;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.permissions', href: '/permissions' },
        ],
    },
});

const homePermissionMap: Record<string, string[]> = {
    dashboard: ['view_dashboard'],
    'attendance.scan': ['scan_attendance'],
    'staff.index': ['view_staff', 'manage_staff', 'view_team_attendance'],
    'permissions.edit': ['manage_permissions'],
    'shifts.index': ['manage_shifts'],
    'attendance.days.index': ['view_roster', 'manage_roster'],
    'branches.index': ['manage_branches'],
    'attendance.kiosk': ['manage_kiosk'],
    'attendance.index': ['view_attendance'],
    'tasks.index': ['view_tasks', 'manage_tasks'],
    'leave-requests.index': ['view_leave_requests', 'review_leave_requests'],
    'activity-logs.index': ['view_activity_log'],
};

const groups = computed(() => {
    const order = ['workspace', 'people', 'operations', 'records', 'work'];

    return order
        .map((group) => ({
            group,
            permissions: props.permissionOptions.filter(
                (permission) => permission.group === group,
            ),
        }))
        .filter((item) => item.permissions.length > 0);
});

const form = useForm({
    roles: Object.fromEntries(
        props.roles
            .filter((role) => !role.locked)
            .map((role) => [
                role.value,
                [...(props.rolePermissions[role.value] ?? [])],
            ]),
    ) as Record<string, string[]>,
    homes: Object.fromEntries(
        props.roles
            .filter((role) => !role.locked)
            .map((role) => [
                role.value,
                props.roleHomes[role.value] ?? 'dashboard',
            ]),
    ) as Record<string, string>,
    names: Object.fromEntries(
        props.roles
            .filter((role) => !role.locked)
            .map((role) => [role.value, role.name]),
    ) as Record<string, string>,
});

const createForm = useForm({
    name: '',
    permissions: [...(props.rolePermissions.employee ?? [])],
    home_page: 'dashboard',
});

const showCreate = ref(false);

function isLocked(role: RoleOption): boolean {
    return role.locked;
}

function isChecked(role: RoleOption, permission: string): boolean {
    if (role.locked) {
        return true;
    }

    return form.roles[role.value]?.includes(permission) ?? false;
}

function toggle(
    role: RoleOption,
    permission: string,
    checked: boolean | 'indeterminate',
): void {
    if (role.locked || checked === 'indeterminate') {
        return;
    }

    const current = form.roles[role.value] ?? [];

    if (checked && !current.includes(permission)) {
        form.roles[role.value] = [...current, permission];

        return;
    }

    if (!checked) {
        form.roles[role.value] = current.filter(
            (value) => value !== permission,
        );
        const allowed = allowedHomes(role);

        if (!allowed.some((page) => page.value === form.homes[role.value])) {
            form.homes[role.value] = allowed[0]?.value ?? 'dashboard';
        }
    }
}

function homeValue(role: RoleOption): string {
    if (role.locked) {
        return 'dashboard';
    }

    return form.homes[role.value] ?? 'dashboard';
}

function allowedHomes(role: RoleOption): HomePageOption[] {
    if (role.locked) {
        return props.homePageOptions.filter(
            (page) => page.value === 'dashboard',
        );
    }

    const permissions = form.roles[role.value] ?? [];

    return props.homePageOptions.filter((page) => {
        const required = homePermissionMap[page.value] ?? [];

        return required.some((permission) => permissions.includes(permission));
    });
}

function roleTitle(role: RoleOption): string {
    return form.names[role.value] || role.label || role.name;
}

function submit(): void {
    form.put('/permissions');
}

function createRole(): void {
    createForm.post('/permissions/roles', {
        preserveScroll: true,
        onSuccess: () => {
            showCreate.value = false;
            createForm.reset();
            createForm.permissions = [
                ...(props.rolePermissions.employee ?? []),
            ];
            createForm.home_page = 'dashboard';
        },
    });
}

function destroyRole(role: RoleOption): void {
    if (role.is_system || role.locked) {
        return;
    }

    if (!confirm(trans('permissions.delete_confirm', { name: role.name }))) {
        return;
    }

    router.delete(`/permissions/roles/${role.id}`, { preserveScroll: true });
}
</script>

<template>
    <Head :title="trans('permissions.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('permissions.eyebrow')"
            :title="trans('permissions.title')"
            :description="trans('permissions.description')"
        >
            <template #actions>
                <Button
                    class="rounded-full"
                    variant="outline"
                    @click="showCreate = !showCreate"
                >
                    {{
                        showCreate
                            ? trans('common.cancel')
                            : trans('permissions.add_role')
                    }}
                </Button>
            </template>
        </PageHeader>

        <Card v-if="showCreate" class="mb-4 shadow-sm">
            <CardHeader>
                <CardTitle>{{ trans('permissions.add_role') }}</CardTitle>
                <CardDescription>{{
                    trans('permissions.add_role_help')
                }}</CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="max-w-md space-y-2">
                    <Label for="new-role-name">{{
                        trans('permissions.role_name')
                    }}</Label>
                    <Input id="new-role-name" v-model="createForm.name" />
                    <p
                        v-if="createForm.errors.name"
                        class="text-sm text-destructive"
                    >
                        {{ createForm.errors.name }}
                    </p>
                </div>
                <Button
                    class="rounded-full"
                    :disabled="createForm.processing"
                    @click="createRole"
                >
                    {{ trans('permissions.create_role') }}
                </Button>
            </CardContent>
        </Card>

        <div class="grid gap-4">
            <Card v-for="role in roles" :key="role.id" class="shadow-sm">
                <CardHeader
                    class="flex flex-row items-start justify-between gap-3 space-y-0"
                >
                    <div class="space-y-1.5">
                        <CardTitle>{{ roleTitle(role) }}</CardTitle>
                        <CardDescription>
                            {{
                                role.locked
                                    ? trans('permissions.locked')
                                    : trans('permissions.role_help')
                            }}
                        </CardDescription>
                    </div>
                    <Button
                        v-if="!role.is_system && !role.locked"
                        variant="destructive"
                        size="sm"
                        class="rounded-full"
                        @click="destroyRole(role)"
                    >
                        {{ trans('common.delete') }}
                    </Button>
                </CardHeader>
                <CardContent class="space-y-6">
                    <div v-if="!isLocked(role)" class="max-w-md space-y-2">
                        <Label :for="`name-${role.value}`">{{
                            trans('permissions.role_name')
                        }}</Label>
                        <Input
                            :id="`name-${role.value}`"
                            v-model="form.names[role.value]"
                        />
                        <p
                            v-if="form.errors[`names.${role.value}`]"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors[`names.${role.value}`] }}
                        </p>
                    </div>

                    <div class="max-w-md space-y-2">
                        <Label :for="`home-${role.value}`">{{
                            trans('permissions.home_page')
                        }}</Label>
                        <select
                            :id="`home-${role.value}`"
                            class="field-control"
                            :value="homeValue(role)"
                            :disabled="isLocked(role)"
                            @change="
                                form.homes[role.value] = (
                                    $event.target as HTMLSelectElement
                                ).value
                            "
                        >
                            <option
                                v-for="page in allowedHomes(role)"
                                :key="page.value"
                                :value="page.value"
                            >
                                {{ page.label }}
                            </option>
                        </select>
                        <p class="text-xs text-muted-foreground">
                            {{ trans('permissions.home_page_help') }}
                        </p>
                        <p
                            v-if="form.errors[`homes.${role.value}`]"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors[`homes.${role.value}`] }}
                        </p>
                    </div>

                    <div
                        v-for="group in groups"
                        :key="`${role.value}-${group.group}`"
                        class="space-y-3"
                    >
                        <h3 class="text-sm font-semibold text-muted-foreground">
                            {{ trans(`permissions.group.${group.group}`) }}
                        </h3>
                        <div class="grid gap-3 md:grid-cols-2">
                            <label
                                v-for="permission in group.permissions"
                                :key="`${role.value}-${permission.value}`"
                                class="flex items-start gap-3 rounded-xl border border-border/80 bg-muted/20 p-3"
                                :class="{ 'opacity-70': isLocked(role) }"
                            >
                                <Checkbox
                                    class="mt-0.5"
                                    :checked="isChecked(role, permission.value)"
                                    :disabled="isLocked(role)"
                                    @update:checked="
                                        (checked: boolean | 'indeterminate') =>
                                            toggle(
                                                role,
                                                permission.value,
                                                checked,
                                            )
                                    "
                                />
                                <span class="space-y-1">
                                    <span class="block text-sm font-medium">
                                        {{
                                            trans(
                                                `permissions.${permission.value}`,
                                            )
                                        }}
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
                    </div>
                </CardContent>
            </Card>

            <p v-if="form.errors.roles" class="text-sm text-destructive">
                {{ form.errors.roles }}
            </p>

            <Button
                class="w-fit rounded-full"
                :disabled="form.processing"
                @click="submit"
            >
                {{ trans('permissions.save') }}
            </Button>
        </div>
    </div>
</template>
