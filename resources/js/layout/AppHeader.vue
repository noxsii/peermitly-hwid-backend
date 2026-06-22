<script setup lang="ts">
import { Monitor, Moon, Sun } from "@lucide/vue";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useAppearance } from "@/composables/useAppearance";
import HeaderSearch from "@/layout/HeaderSearch.vue";
import Logo from "@/layout/Logo.vue";
import NotificationBell from "@/layout/NotificationBell.vue";
import UserMenu from "@/layout/UserMenu.vue";
import type { Appearance } from "@/types";

const { mode, set: setAppearance } = useAppearance();

const appearanceOptions: Array<{
    value: Appearance;
    label: string;
    icon: typeof Sun;
}> = [
    { value: "light", label: "Light", icon: Sun },
    { value: "dark", label: "Dark", icon: Moon },
    { value: "auto", label: "System", icon: Monitor },
];
</script>

<template>
    <header
        class="bg-background flex h-14 shrink-0 items-center gap-2 px-3 sm:gap-3 sm:px-4"
    >
        <div class="flex shrink-0 items-center">
            <Logo />
        </div>

        <div class="min-w-0 flex-1 sm:mx-auto sm:max-w-2xl">
            <HeaderSearch />
        </div>

        <div class="flex shrink-0 items-center gap-1 sm:gap-2">
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button
                        size="icon-sm"
                        variant="ghost"
                        class="rounded-full"
                        aria-label="Toggle appearance"
                    >
                        <Sun class="size-4 dark:hidden" />
                        <Moon class="hidden size-4 dark:block" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-40">
                    <DropdownMenuItem
                        v-for="option in appearanceOptions"
                        :key="option.value"
                        :data-state="
                            mode === option.value ? 'checked' : undefined
                        "
                        class="data-[state=checked]:bg-accent data-[state=checked]:text-accent-foreground"
                        @select="setAppearance(option.value)"
                    >
                        <component :is="option.icon" class="size-4" />
                        {{ option.label }}
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            <NotificationBell />

            <UserMenu />
        </div>
    </header>
</template>
