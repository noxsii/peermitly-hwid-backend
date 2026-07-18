<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { LayoutDashboard, Settings, UserRound } from "@lucide/vue";
import { computed } from "vue";
import type { LucideIcon, NavItem } from "@/types";

interface ExternalNavItem {
    label: string;
    href: string;
    icon: LucideIcon;
    external: true;
}

const primary: NavItem[] = [
    { label: "Dashboard", href: "/dashboard", icon: LayoutDashboard },
];

const secondary: (NavItem | ExternalNavItem)[] = [
    { label: "Profile", href: "/profile", icon: UserRound },
    { label: "Settings", href: "/settings", icon: Settings },
];

const mobileNavigation = [...primary, ...secondary];

const isExternal = (item: NavItem | ExternalNavItem): item is ExternalNavItem =>
    "external" in item && item.external;

const page = usePage();
const currentUrl = computed(() => page.url);
</script>

<template>
    <aside
        class="border-border bg-background fixed inset-x-0 bottom-0 z-40 border-t md:sticky md:top-20 md:flex md:h-[calc(100vh-5rem)] md:w-60 md:shrink-0 md:flex-col md:justify-between md:border-t-0 md:border-r md:px-5 md:py-8"
    >
        <nav
            aria-label="Workspace navigation"
            class="hidden space-y-1 md:block"
        >
            <Link
                v-for="item in primary"
                :key="item.label"
                :href="item.href"
                :aria-current="currentUrl === item.href ? 'page' : undefined"
                class="text-muted-foreground hover:text-foreground aria-[current=page]:bg-foreground aria-[current=page]:text-background flex min-w-20 items-center justify-center gap-3 rounded-md px-3 py-2.5 text-sm font-medium transition-colors md:justify-start"
            >
                <component :is="item.icon" class="size-4" />
                <span>{{ item.label }}</span>
            </Link>
        </nav>

        <nav
            aria-label="Mobile workspace navigation"
            class="grid grid-cols-3 gap-1 p-2 md:hidden"
        >
            <Link
                v-for="item in mobileNavigation"
                :key="item.label"
                :href="item.href"
                :aria-current="currentUrl === item.href ? 'page' : undefined"
                class="text-muted-foreground hover:text-foreground aria-[current=page]:bg-foreground aria-[current=page]:text-background flex min-w-0 flex-col items-center justify-center gap-1 rounded-md px-2 py-2 text-xs font-medium transition-colors"
            >
                <component :is="item.icon" class="size-4" />
                <span class="truncate">{{ item.label }}</span>
            </Link>
        </nav>

        <nav aria-label="Account navigation" class="hidden space-y-1 md:block">
            <template v-for="item in secondary" :key="item.label">
                <a
                    v-if="isExternal(item)"
                    :href="item.href"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-muted-foreground hover:bg-muted hover:text-foreground flex items-center gap-3 rounded-md px-3 py-2.5 text-sm font-medium transition-colors"
                >
                    <component :is="item.icon" class="size-4" />
                    {{ item.label }}
                </a>
                <Link
                    v-else
                    :href="item.href"
                    :aria-current="
                        currentUrl === item.href ? 'page' : undefined
                    "
                    class="text-muted-foreground hover:bg-muted hover:text-foreground aria-[current=page]:text-foreground flex items-center gap-3 rounded-md px-3 py-2.5 text-sm font-medium transition-colors"
                >
                    <component :is="item.icon" class="size-4" />
                    {{ item.label }}
                </Link>
            </template>
        </nav>
    </aside>
</template>
