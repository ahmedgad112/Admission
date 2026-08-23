<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import StaffForm from '@/components/StaffForm.vue';
import { trans } from '@/composables/useTrans';

type Option = { id: number; name: string };
type DepartmentOption = { id: number; name: string; branch_id: number };
type LabeledOption = { value: string; label: string };
type PermissionOption = { value: string; label: string; description: string };
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

defineProps<{
    member: Member;
    branches: Option[];
    departments: DepartmentOption[];
    shifts: Option[];
    roles: LabeledOption[];
    statuses: LabeledOption[];
    permissionOptions: PermissionOption[];
    rolePermissions: Record<string, string[]>;
    grantablePermissions: string[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.staff', href: '/staff' },
            { title: 'common.edit', href: '/staff' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('staff.edit')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('staff.eyebrow')"
            :title="trans('staff.edit')"
            :description="trans('staff.edit_description')"
        />

        <StaffForm
            :member="member"
            :branches="branches"
            :departments="departments"
            :shifts="shifts"
            :roles="roles"
            :statuses="statuses"
            :permission-options="permissionOptions"
            :role-permissions="rolePermissions"
            :grantable-permissions="grantablePermissions"
        />
    </div>
</template>
