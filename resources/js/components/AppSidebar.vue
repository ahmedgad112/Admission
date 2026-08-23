<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { CalendarDays, CalendarOff, ClipboardList, Clock, LayoutGrid, MapPin, QrCode, ScanLine, Timer, Users } from '@lucide/vue';
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

const mainNavItems = computed<NavItem[]>(() => {
    page.props.locale;
    page.props.translations;

    const items: NavItem[] = [
        { title: trans('nav.dashboard'), href: '/dashboard', icon: LayoutGrid },
        { title: trans('nav.scan'), href: '/attendance/scan', icon: ScanLine },
    ];

    if (page.props.can?.manageStaff || page.props.can?.viewTeamAttendance) {
        items.push({
            title: trans('nav.staff'),
            href: '/staff',
            icon: Users,
        });
    }

    if (page.props.can?.manageStaff) {
        items.push({
            title: trans('nav.shifts'),
            href: '/shifts',
            icon: Clock,
        });
    }

    if (page.props.can?.manageKiosk) {
        items.push(
            {
                title: trans('nav.branches'),
                href: '/branches',
                icon: MapPin,
            },
            {
                title: trans('nav.roster'),
                href: '/attendance/days',
                icon: CalendarDays,
            },
            {
                title: trans('nav.kiosk'),
                href: '/attendance/kiosk',
                icon: QrCode,
            },
        );
    }

    items.push(
        { title: trans('nav.records'), href: '/attendance', icon: Timer },
        { title: trans('nav.tasks'), href: '/tasks', icon: ClipboardList },
        { title: trans('nav.leave'), href: '/leave-requests', icon: CalendarOff },
    );

    return items;
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" :side="sidebarSide">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/dashboard">
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
