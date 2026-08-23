<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { trans } from '@/composables/useTrans';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

type Props = {
    breadcrumbs: BreadcrumbItemType[];
};

defineProps<Props>();
</script>

<template>
    <Breadcrumb>
        <BreadcrumbList>
            <template v-for="(item, index) in breadcrumbs" :key="index">
                <BreadcrumbItem :class="index === breadcrumbs.length - 1 ? '' : 'hidden sm:block'">
                    <template v-if="index === breadcrumbs.length - 1">
                        <BreadcrumbPage class="max-w-[14rem] truncate sm:max-w-none">
                            {{ trans(item.title) }}
                        </BreadcrumbPage>
                    </template>
                    <template v-else>
                        <BreadcrumbLink as-child>
                            <Link :href="item.href">{{ trans(item.title) }}</Link>
                        </BreadcrumbLink>
                    </template>
                </BreadcrumbItem>
                <BreadcrumbSeparator
                    v-if="index !== breadcrumbs.length - 1"
                    class="hidden sm:block"
                />
            </template>
        </BreadcrumbList>
    </Breadcrumb>
</template>
