<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Moon, Sun } from "@lucide/vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import { useAppearance } from "@/composables/useAppearance";

const { toggle: toggleAppearance } = useAppearance();
</script>

<template>
    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-5xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="flex items-center gap-2.5">
                <LogoMark size="size-9" />
                <span class="text-lg font-semibold tracking-tight">
                    Peermitly
                </span>
            </Link>
            <div class="flex items-center gap-2">
                <Button
                    size="icon-sm"
                    variant="ghost"
                    class="rounded-full"
                    aria-label="Toggle dark mode"
                    @click="toggleAppearance"
                >
                    <Sun class="size-4 dark:hidden" />
                    <Moon class="hidden size-4 dark:block" />
                </Button>
                <Link
                    href="/login"
                    class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                >
                    Log in
                </Link>
            </div>
        </header>

        <slot />

        <LandingFooter />
    </div>
</template>
