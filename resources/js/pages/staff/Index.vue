<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
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
};

const props = defineProps<{
    staff: { data: StaffRow[] };
    filters: { search: string; role: string; status: string };
    roleOptions: { value: string; label: string }[];
    canCreate: boolean;
}>();

const page = usePage();
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
    return Boolean(page.props.can?.impersonate) && member.id !== page.props.auth.user.id;
}

function hasActions(member: StaffRow): boolean {
    return canImpersonate(member) || props.canCreate || member.can_delete;
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
                <Button v-if="page.props.can?.managePermissions" variant="outline" class="rounded-full" as-child>
                    <Link href="/permissions">{{ trans('nav.permissions') }}</Link>
                </Button>
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
                <option v-for="role in roleOptions" :key="role.value" :value="role.value">
                    {{ role.label }}
                </option>
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

        <div
            v-if="staff.data.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center text-sm text-muted-foreground"
        >
            {{ trans('staff.empty') }}
        </div>
        <template v-else>
            <div class="grid gap-4 md:hidden">
                <Card
                    v-for="member in staff.data"
                    :key="member.id"
                    class="h-full shadow-sm"
                >
                    <CardHeader>
                        <CardTitle class="text-lg">{{ member.name }}</CardTitle>
                        <CardDescription>{{ member.email }}</CardDescription>
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
                            <StatusBadge :value="member.status" :tone="userStatusTone(member.status)" />
                        </div>
                        <dl class="grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-muted-foreground">{{ trans('common.branch') }}</dt>
                                <dd class="font-medium">{{ member.branch?.name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">{{ trans('common.department') }}</dt>
                                <dd class="font-medium">{{ member.department?.name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">{{ trans('common.shift') }}</dt>
                                <dd class="font-medium">{{ member.shift?.name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">{{ trans('staff.leave_days') }}</dt>
                                <dd class="font-medium">{{ member.leave_days }}</dd>
                            </div>
                        </dl>
                    </CardContent>
                    <CardFooter
                        v-if="hasActions(member)"
                        class="mt-auto flex flex-wrap gap-2 border-t"
                    >
                        <Button
                            v-if="canImpersonate(member)"
                            variant="secondary"
                            size="sm"
                            class="rounded-full"
                            @click="impersonate(member.id)"
                        >
                            {{ trans('staff.login_as') }}
                        </Button>
                        <Button v-if="canCreate" variant="outline" size="sm" class="rounded-full" as-child>
                            <Link :href="`/staff/${member.id}/edit`">{{ trans('common.edit') }}</Link>
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
                    <thead class="bg-muted/40 text-xs tracking-wide text-muted-foreground uppercase">
                        <tr>
                            <th class="px-4 py-3 font-semibold">{{ trans('common.name') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ trans('common.email') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ trans('common.role') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ trans('common.status') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ trans('common.branch') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ trans('common.department') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ trans('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="member in staff.data"
                            :key="member.id"
                            class="border-t"
                        >
                            <td class="px-4 py-3 font-medium">{{ member.name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ member.email }}</td>
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
                                <StatusBadge :value="member.status" :tone="userStatusTone(member.status)" />
                            </td>
                            <td class="px-4 py-3">{{ member.branch?.name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ member.department?.name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <Button
                                        v-if="canImpersonate(member)"
                                        variant="secondary"
                                        size="sm"
                                        class="rounded-full"
                                        @click="impersonate(member.id)"
                                    >
                                        {{ trans('staff.login_as') }}
                                    </Button>
                                    <Button v-if="canCreate" variant="outline" size="sm" class="rounded-full" as-child>
                                        <Link :href="`/staff/${member.id}/edit`">{{ trans('common.edit') }}</Link>
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
