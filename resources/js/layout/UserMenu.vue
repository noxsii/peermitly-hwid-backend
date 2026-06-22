<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { LogOut, Settings, Users } from "@lucide/vue";
import { computed } from "vue";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();
const user = computed(() => page.props.auth.user);
const initials = computed(() => {
    if (!user.value) return "";
    return user.value.name
        .split(/\s+/)
        .map((part) => part.charAt(0).toUpperCase())
        .slice(0, 2)
        .join("");
});
const canManageTeam = computed(() => {
    const role = user.value?.role;
    return role === "admin" || role === "super_admin";
});
</script>

<template>
    <DropdownMenu v-if="user">
        <DropdownMenuTrigger as-child>
            <button
                type="button"
                :aria-label="`Open user menu for ${user.name}`"
                class="bg-primary text-primary-foreground focus-visible:ring-ring/40 ml-1 flex size-8 cursor-pointer items-center justify-center rounded-full text-xs font-semibold outline-none focus-visible:ring-2"
            >
                {{ initials }}
            </button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end" class="w-56">
            <DropdownMenuLabel class="flex flex-col gap-0.5">
                <span class="text-foreground truncate text-sm font-medium">
                    {{ user.name }}
                </span>
                <span
                    class="text-muted-foreground truncate text-xs font-normal"
                >
                    {{ user.email }}
                </span>
            </DropdownMenuLabel>

            <DropdownMenuSeparator />

            <DropdownMenuItem as-child>
                <Link href="/settings" class="w-full">
                    <Settings class="size-4" />
                    Settings
                </Link>
            </DropdownMenuItem>

            <DropdownMenuItem v-if="canManageTeam" as-child>
                <Link href="/team" class="w-full">
                    <Users class="size-4" />
                    Team
                </Link>
            </DropdownMenuItem>

            <DropdownMenuSeparator />

            <DropdownMenuItem
                as-child
                class="text-destructive focus:text-destructive"
            >
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    type="button"
                    class="w-full"
                >
                    <LogOut class="size-4" />
                    Log out
                </Link>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
