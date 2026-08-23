<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import StatusBadge from '@/components/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { trans } from '@/composables/useTrans';
import { userRoleTone, userStatusTone } from '@/lib/status';

type StaffRow = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    role: string;
    status: string;
    branch?: { id: number; name: string } | null;
    department?: { id: number; name: string } | null;
    shift?: { id: number; name: string } | null;
    leave_days: number;
};

const props = defineProps<{
    staff: { data: StaffRow[] };
    filters: { search: string; role: string; status: string };
    canCreate: boolean;
}>();

const search = ref(props.filters.search);

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'nav.dashboard', href: '/dashboard' },
            { title: 'nav.staff', href: '/staff' },
        ],
    },
});

function filter(key: 'search' | 'role' | 'status', value: string): void {
    router.get(
        '/staff',
        {
            search: key === 'search' ? value || undefined : props.filters.search || undefined,
            role: key === 'role' ? value || undefined : props.filters.role || undefined,
            status: key === 'status' ? value || undefined : props.filters.status || undefined,
        },
        { preserveState: true, replace: true },
    );
}

function destroy(id: number): void {
    router.delete(`/staff/${id}`);
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
                <Button v-if="canCreate" class="rounded-full" as-child>
                    <Link href="/staff/create">{{ trans('staff.new') }}</Link>
                </Button>
            </template>
        </PageHeader>

        <div class="flex flex-wrap gap-3">
            <Input
                v-model="search"
                class="max-w-64"
                :placeholder="trans('staff.search')"
                @keyup.enter="filter('search', search)"
            />
            <select
                :value="filters.role"
                class="field-control max-w-48"
                @change="filter('role', ($event.target as HTMLSelectElement).value)"
            >
                <option value="">{{ trans('staff.all_roles') }}</option>
                <option value="super_admin">{{ trans('roles.super_admin') }}</option>
                <option value="branch_admin">{{ trans('roles.branch_admin') }}</option>
                <option value="manager">{{ trans('roles.manager') }}</option>
                <option value="employee">{{ trans('roles.employee') }}</option>
            </select>
            <select
                :value="filters.status"
                class="field-control max-w-48"
                @change="filter('status', ($event.target as HTMLSelectElement).value)"
            >
                <option value="">{{ trans('staff.all_statuses') }}</option>
                <option value="active">{{ trans('status.active') }}</option>
                <option value="inactive">{{ trans('status.inactive') }}</option>
                <option value="suspended">{{ trans('status.suspended') }}</option>
            </select>
        </div>

        <Card class="shadow-sm">
            <CardContent class="overflow-x-auto pt-6">
                <table class="w-full text-sm">
                    <thead class="text-start text-muted-foreground">
                        <tr>
                            <th class="pb-3 font-medium">{{ trans('common.name') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.branch') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.department') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.shift') }}</th>
                            <th class="pb-3 font-medium">{{ trans('staff.leave_days') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.role') }}</th>
                            <th class="pb-3 font-medium">{{ trans('common.status') }}</th>
                            <th class="pb-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="staff.data.length === 0">
                            <td colspan="8" class="py-10 text-center text-muted-foreground">
                                {{ trans('staff.empty') }}
                            </td>
                        </tr>
                        <tr v-for="member in staff.data" :key="member.id" class="border-t border-border/70">
                            <td class="py-3.5">
                                <p class="font-medium">{{ member.name }}</p>
                                <p class="text-xs text-muted-foreground">{{ member.email }}</p>
                            </td>
                            <td class="py-3.5">{{ member.branch?.name ?? '—' }}</td>
                            <td class="py-3.5">{{ member.department?.name ?? '—' }}</td>
                            <td class="py-3.5">{{ member.shift?.name ?? '—' }}</td>
                            <td class="py-3.5">{{ member.leave_days }}</td>
                            <td class="py-3.5">
                                <StatusBadge :value="member.role" :tone="userRoleTone(member.role)" />
                            </td>
                            <td class="py-3.5">
                                <StatusBadge :value="member.status" :tone="userStatusTone(member.status)" />
                            </td>
                            <td class="py-3.5 text-right">
                                <div v-if="canCreate" class="flex justify-end gap-2">
                                    <Button variant="outline" size="sm" class="rounded-full" as-child>
                                        <Link :href="`/staff/${member.id}/edit`">{{ trans('common.edit') }}</Link>
                                    </Button>
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        class="rounded-full"
                                        @click="destroy(member.id)"
                                    >
                                        {{ trans('common.delete') }}
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardContent>
        </Card>
    </div>
</template>
