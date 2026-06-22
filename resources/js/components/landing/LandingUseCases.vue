<script setup lang="ts">
import { Building2, MonitorSmartphone, Puzzle, Rocket } from "@lucide/vue";
import { useScrollReveal } from "@/composables/useScrollReveal";
import type { LandingUseCase } from "@/types/landing";

const { target, isVisible } = useScrollReveal();

const useCases: LandingUseCase[] = [
    {
        icon: MonitorSmartphone,
        title: "Desktop apps",
        description: "Lock native apps to a single machine with HWID binding.",
    },
    {
        icon: Puzzle,
        title: "Plugins & extensions",
        description: "Gate premium add-ons behind a license key.",
    },
    {
        icon: Rocket,
        title: "Indie SaaS",
        description:
            "Issue keys per customer without building billing-grade infra.",
    },
    {
        icon: Building2,
        title: "Internal tools",
        description: "Control who is allowed to run your in-house software.",
    },
];
</script>

<template>
    <section class="bg-muted/30 border-border/60 border-y py-20 md:py-28">
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
                    Who it's for
                </p>
                <h2
                    class="text-foreground mt-3 text-3xl font-semibold tracking-tight md:text-4xl"
                >
                    One license layer for any product you ship.
                </h2>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <article
                    v-for="useCase in useCases"
                    :key="useCase.title"
                    class="border-border/70 bg-card text-card-foreground rounded-2xl border p-6 shadow-sm"
                >
                    <div
                        class="bg-primary/10 text-primary mb-5 inline-flex size-10 items-center justify-center rounded-lg"
                        aria-hidden="true"
                    >
                        <component :is="useCase.icon" class="size-5" />
                    </div>
                    <h3 class="text-foreground text-base font-semibold">
                        {{ useCase.title }}
                    </h3>
                    <p class="text-muted-foreground mt-2 text-sm leading-6">
                        {{ useCase.description }}
                    </p>
                </article>
            </div>
        </div>
    </section>
</template>
