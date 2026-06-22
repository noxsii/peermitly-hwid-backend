<script setup lang="ts">
import { KeyRound, PlugZap, ShieldCheck } from "@lucide/vue";
import { useScrollReveal } from "@/composables/useScrollReveal";
import type { LandingStep } from "@/types/landing";

const { target, isVisible } = useScrollReveal();

const steps: LandingStep[] = [
    {
        number: "01",
        icon: KeyRound,
        title: "Create a key",
        description:
            "Generate a license key in the dashboard. It stays pending until the first activation.",
    },
    {
        number: "02",
        icon: PlugZap,
        title: "Drop in the API",
        description:
            "Call POST /api/license-keys/check from your product. Token-secured, one request.",
    },
    {
        number: "03",
        icon: ShieldCheck,
        title: "Customer activates",
        description:
            "The first check binds the key, applies the optional HWID lock and starts the clock.",
    },
];
</script>

<template>
    <section id="how" class="bg-background py-20 md:py-28">
        <div
            ref="target"
            :class="[
                'mx-auto max-w-6xl px-6 transition-all duration-700 ease-out',
                isVisible
                    ? 'translate-y-0 opacity-100'
                    : 'translate-y-4 opacity-0',
            ]"
        >
            <div class="max-w-2xl">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
                >
                    How it works
                </p>
                <h2
                    class="text-foreground mt-3 text-3xl font-semibold tracking-tight md:text-4xl"
                >
                    From key to activation in three steps.
                </h2>
            </div>

            <ol class="mt-12 grid gap-10 md:grid-cols-3 md:gap-6">
                <li v-for="step in steps" :key="step.number" class="relative">
                    <span
                        class="text-muted-foreground/30 text-5xl font-semibold tracking-tight tabular-nums"
                        aria-hidden="true"
                        >{{ step.number }}</span
                    >
                    <div
                        class="bg-primary/10 text-primary mt-4 inline-flex size-10 items-center justify-center rounded-lg"
                        aria-hidden="true"
                    >
                        <component :is="step.icon" class="size-5" />
                    </div>
                    <h3 class="text-foreground mt-4 text-lg font-semibold">
                        {{ step.title }}
                    </h3>
                    <p class="text-muted-foreground mt-2 text-sm leading-6">
                        {{ step.description }}
                    </p>
                </li>
            </ol>
        </div>
    </section>
</template>
