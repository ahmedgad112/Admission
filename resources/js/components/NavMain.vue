<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarSeparator,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

withDefaults(
    defineProps<{
        items: NavItem[];
        label: string;
        showSeparator?: boolean;
    }>(),
    {
        showSeparator: false,
    },
);

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <div>
        <SidebarSeparator v-if="showSeparator" class="mx-4 my-2" />
        <SidebarGroup class="px-2 py-1">
            <SidebarGroupLabel
                class="mb-1 px-2 text-[11px] font-semibold tracking-wide text-sidebar-foreground/45 uppercase"
            >
                {{ label }}
            </SidebarGroupLabel>
            <SidebarMenu class="gap-0.5">
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton
                        as-child
                        :is-active="isCurrentUrl(item.href)"
                        :tooltip="item.title"
                        class="h-10 rounded-xl data-[active=true]:bg-sidebar-primary data-[active=true]:text-sidebar-primary-foreground"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroup>
    </div>
</template>
