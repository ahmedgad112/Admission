<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import StaffForm from '@/components/StaffForm.vue';
import { trans } from '@/composables/useTrans';

type Option = { id: number; name: string };
type DepartmentOption = { id: number; name: string; branch_id: number };
type LabeledOption = { value: string; label: string };

type PermissionOption = { value: string; label: string; description: string };

defineProps<{
    branches: Option[];
    departments: DepartmentOption[];
    shifts: Option[];
    roles: LabeledOption[];
    statuses: LabeledOption[];
    permissionOptions: PermissionOption[];
    rolePermissions: Record<string, string[]>;
    grantablePermissions: string[];
    defaultBranchId: number | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.staff', href: '/staff' },
            { title: 'common.create', href: '/staff/create' },
        ],
    },
});
</script>

<template>
    <Head :title="trans('staff.new')" />

    <div class="page-shell">
        <PageHeader
            :eyebrow="trans('staff.eyebrow')"
            :title="trans('staff.add')"
            :description="trans('staff.add_description')"
        />

        <StaffForm
            :branches="branches"
            :departments="departments"
            :shifts="shifts"
            :roles="roles"
            :statuses="statuses"
            :permission-options="permissionOptions"
            :role-permissions="rolePermissions"
            :grantable-permissions="grantablePermissions"
            :default-branch-id="defaultBranchId"
        />
    </div>
</template>
