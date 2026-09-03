<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    Building2,
    CalendarDays,
    CalendarOff,
    ClipboardList,
    Clock,
    History,
    LayoutGrid,
    MapPin,
    QrCode,
    ScanLine,
    ShieldCheck,
    Timer,
    Users,
} from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarRail,
    useSidebar,
} from '@/components/ui/sidebar';
import { trans } from '@/composables/useTrans';
import type { NavItem } from '@/types';

type NavGroup = {
    label: string;
    items: NavItem[];
};

const page = usePage();
const { setOpenMobile } = useSidebar();
const documentDir = computed<'ltr' | 'rtl'>(() =>
    page.props.dir === 'rtl' ? 'rtl' : 'ltr',
);
const sidebarSide = computed(() =>
    documentDir.value === 'rtl' ? 'right' : 'left',
);
const home = computed(() => page.props.home || '/dashboard');

const navGroups = computed<NavGroup[]>(() => {
    void page.props.locale;
    void page.props.translations;

    const can = page.props.can;

    const workspace: NavItem[] = [];
    const attendance: NavItem[] = [];
    const organization: NavItem[] = [];
    const settings: NavItem[] = [];

    if (can?.viewDashboard) {
        workspace.push({
            title: trans('nav.dashboard'),
            href: '/dashboard',
            icon: LayoutGrid,
        });
    }

    if (can?.scanAttendance) {
        attendance.push({
            title: trans('nav.scan'),
            href: '/attendance/scan',
            icon: ScanLine,
        });
    }

    if (can?.viewRoster) {
        attendance.push({
            title: trans('nav.roster'),
            href: '/attendance/days',
            icon: CalendarDays,
        });
    }

    if (can?.viewAttendance) {
        attendance.push({
            title: trans('nav.records'),
            href: '/attendance',
            icon: Timer,
        });
    }

    if (can?.manageKiosk) {
        attendance.push({
            title: trans('nav.kiosk'),
            href: '/attendance/kiosk',
            icon: QrCode,
        });
    }

    if (can?.viewStaff) {
        organization.push({
            title: trans('nav.staff'),
            href: '/staff',
            icon: Users,
        });
    }

    if (can?.manageStaff) {
        organization.push({
            title: trans('nav.departments'),
            href: '/departments',
            icon: Building2,
        });
    }

    if (can?.manageShifts) {
        organization.push({
            title: trans('nav.shifts'),
            href: '/shifts',
            icon: Clock,
        });
    }

    if (can?.manageBranches) {
        organization.push({
            title: trans('nav.branches'),
            href: '/branches',
            icon: MapPin,
        });
    }

    if (can?.viewTasks) {
        organization.push({
            title: trans('nav.tasks'),
            href: '/tasks',
            icon: ClipboardList,
        });
    }

    if (can?.viewLeaveRequests) {
        organization.push({
            title: trans('nav.leave'),
            href: '/leave-requests',
            icon: CalendarOff,
        });
    }

    if (can?.managePermissions) {
        settings.push({
            title: trans('nav.permissions'),
            href: '/permissions',
            icon: ShieldCheck,
        });
    }

    if (can?.viewActivityLog) {
        settings.push({
            title: trans('nav.activity_log'),
            href: '/activity-logs',
            icon: History,
        });
    }

    return [
        { label: trans('nav.workspace'), items: workspace },
        { label: trans('nav.attendance'), items: attendance },
        { label: trans('nav.organization'), items: organization },
        { label: trans('nav.settings'), items: settings },
    ].filter((group) => group.items.length > 0);
});
</script>

<template>
    <Sidebar
        collapsible="icon"
        variant="inset"
        :side="sidebarSide"
        :dir="documentDir"
    >
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="home" @click="setOpenMobile(false)">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="gap-0">
            <NavMain
                v-for="(group, index) in navGroups"
                :key="group.label"
                :label="group.label"
                :items="group.items"
                :show-separator="index > 0"
            />
        </SidebarContent>

        <SidebarFooter>
            <div class="px-2 pb-1 group-data-[collapsible=icon]:hidden">
                <LanguageSwitcher />
            </div>
            <NavUser />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
    <slot />
</template>
