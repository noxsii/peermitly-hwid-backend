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
        class="border-border bg-background/95 sticky top-0 z-40 flex h-20 shrink-0 items-center gap-3 border-b px-5 backdrop-blur sm:px-8"
    >
        <div class="flex shrink-0 items-center">
            <Logo />
            <span
                class="text-muted-foreground ml-4 hidden border-l pl-4 text-xs tracking-[0.18em] uppercase sm:inline"
                >Workspace</span
            >
        </div>

        <div class="min-w-0 flex-1"></div>

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
