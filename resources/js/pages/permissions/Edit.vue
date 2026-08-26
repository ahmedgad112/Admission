<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { trans } from '@/composables/useTrans';

type PermissionOption = { value: string; label: string; description: string };
type RoleOption = { value: string; locked: boolean };

const props = defineProps<{
    permissionOptions: PermissionOption[];
    roles: RoleOption[];
    rolePermissions: Record<string, string[]>;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.permissions', href: '/permissions' },
        ],
    },
});

const form = useForm({
    roles: Object.fromEntries(
        props.roles
            .filter((role) => !role.locked)
            .map((role) => [role.value, [...(props.rolePermissions[role.value] ?? [])]]),
    ) as Record<string, string[]>,
});

function isLocked(role: RoleOption): boolean {
    return role.locked;
}

function isChecked(role: RoleOption, permission: string): boolean {
    if (role.locked) {
        return true;
    }

    return form.roles[role.value]?.includes(permission) ?? false;
}

function toggle(role: RoleOption, permission: string, checked: boolean | 'indeterminate'): void {
    if (role.locked || checked === 'indeterminate') {
        return;
    }

    const current = form.roles[role.value] ?? [];

    if (checked && !current.includes(permission)) {
        form.roles[role.value] = [...current, permission];

        return;
    }

    if (!checked) {
        form.roles[role.value] = current.filter((value) => value !== permission);
    }
}

function submit(): void {
    form.put('/permissions');
}
</script>

<template>
    <Head :title="trans('permissions.title')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('permissions.eyebrow')"
            :title="trans('permissions.title')"
            :description="trans('permissions.description')"
        />

        <div class="grid gap-4">
            <Card v-for="role in roles" :key="role.value" class="shadow-sm">
                <CardHeader>
                    <CardTitle>{{ trans(`roles.${role.value}`) }}</CardTitle>
                    <CardDescription>
                        {{ role.locked ? trans('permissions.locked') : trans('permissions.role_help') }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-3 md:grid-cols-2">
                        <label
                            v-for="permission in permissionOptions"
                            :key="`${role.value}-${permission.value}`"
                            class="flex items-start gap-3 rounded-xl border border-border/80 bg-muted/20 p-3"
                            :class="{ 'opacity-70': isLocked(role) }"
                        >
                            <Checkbox
                                class="mt-0.5"
                                :checked="isChecked(role, permission.value)"
                                :disabled="isLocked(role)"
                                @update:checked="(checked: boolean | 'indeterminate') => toggle(role, permission.value, checked)"
                            />
                            <span class="space-y-1">
                                <span class="block text-sm font-medium">
                                    {{ trans(`permissions.${permission.value}`) }}
                                </span>
                                <span class="block text-xs text-muted-foreground">
                                    {{ trans(`permissions.${permission.value}_help`) }}
                                </span>
                            </span>
                        </label>
                    </div>
                </CardContent>
            </Card>

            <p v-if="form.errors.roles" class="text-sm text-destructive">{{ form.errors.roles }}</p>

            <Button class="w-fit rounded-full" :disabled="form.processing" @click="submit">
                {{ trans('permissions.save') }}
            </Button>
        </div>
    </div>
</template>
