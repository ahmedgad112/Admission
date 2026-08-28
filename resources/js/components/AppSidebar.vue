<script setup lang="ts">
import { computed } from 'vue';
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
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
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
} from '@/components/ui/sidebar';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { trans } from '@/composables/useTrans';
import type { NavItem } from '@/types';

const page = usePage();
const sidebarSide = computed(() => (page.props.dir === 'rtl' ? 'right' : 'left'));
const home = computed(() => page.props.home || '/dashboard');

const mainNavItems = computed<NavItem[]>(() => {
    page.props.locale;
    page.props.translations;

    const can = page.props.can;
    const items: NavItem[] = [];

    if (can?.viewDashboard) {
        items.push({ title: trans('nav.dashboard'), href: '/dashboard', icon: LayoutGrid });
    }

    if (can?.scanAttendance) {
        items.push({ title: trans('nav.scan'), href: '/attendance/scan', icon: ScanLine });
    }

    if (can?.viewStaff) {
        items.push({ title: trans('nav.staff'), href: '/staff', icon: Users });
    }

    if (can?.manageStaff) {
        items.push({ title: trans('nav.departments'), href: '/departments', icon: Building2 });
    }

    if (can?.managePermissions) {
        items.push({ title: trans('nav.permissions'), href: '/permissions', icon: ShieldCheck });
    }

    if (can?.manageShifts) {
        items.push({ title: trans('nav.shifts'), href: '/shifts', icon: Clock });
    }

    if (can?.viewRoster) {
        items.push({ title: trans('nav.roster'), href: '/attendance/days', icon: CalendarDays });
    }

    if (can?.manageBranches) {
        items.push({ title: trans('nav.branches'), href: '/branches', icon: MapPin });
    }

    if (can?.manageKiosk) {
        items.push({ title: trans('nav.kiosk'), href: '/attendance/kiosk', icon: QrCode });
    }

    if (can?.viewAttendance) {
        items.push({ title: trans('nav.records'), href: '/attendance', icon: Timer });
    }

    if (can?.viewActivityLog) {
        items.push({ title: trans('nav.activity_log'), href: '/activity-logs', icon: History });
    }

    if (can?.viewTasks) {
        items.push({ title: trans('nav.tasks'), href: '/tasks', icon: ClipboardList });
    }

    if (can?.viewLeaveRequests) {
        items.push({ title: trans('nav.leave'), href: '/leave-requests', icon: CalendarOff });
    }

    return items;
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" :side="sidebarSide">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="home">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <div class="px-2 pb-1 group-data-[collapsible=icon]:hidden">
                <LanguageSwitcher />
            </div>
            <NavFooter :items="[]" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
