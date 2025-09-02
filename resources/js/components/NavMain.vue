<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();

const isActiveRoute = computed(() => (item: NavItem) => {
    const currentUrl = page.url;
    const itemUrl = typeof item.href === 'string' ? item.href : item.href?.url || '';

    if (itemUrl === '/dashboard' || itemUrl === '/') {
        return currentUrl === itemUrl;
    }

    if (itemUrl && itemUrl !== '/') {
        return currentUrl === itemUrl || currentUrl.startsWith(itemUrl + '/');
    }

    return false;
});
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton as-child :is-active="isActiveRoute(item)" :tooltip="item.title">
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
