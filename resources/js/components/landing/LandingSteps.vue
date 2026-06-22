<script setup lang="ts">
import { Download, MousePointerClick, Rocket } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";

const steps = [
    {
        icon: Download,
        title: "Download the tool",
        description:
            "Buy a plan and get instant access. Download the spoofer from your dashboard in under a minute.",
    },
    {
        icon: MousePointerClick,
        title: "Click spoof",
        description:
            "Run it as admin and hit Spoof. Every hardware identifier is rewritten and old traces are cleaned.",
    },
    {
        icon: Rocket,
        title: "Launch & play",
        description:
            "Your machine now looks brand new. Start your game and play — no ban, no trace, no stress.",
    },
];
</script>

<template>
    <section id="how" class="bg-muted/30 scroll-mt-20 border-y border-border/60 py-24">
        <div class="mx-auto max-w-6xl px-6">
            <Reveal class="mx-auto max-w-2xl text-center">
                <span
                    class="text-primary text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    How it works
                </span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    Back in the game in three steps
                </h2>
            </Reveal>

            <div class="relative mt-14 grid gap-6 md:grid-cols-3">
                <Reveal
                    v-for="(step, i) in steps"
                    :key="step.title"
                    :delay="i * 100"
                >
                    <div class="relative h-full">
                        <span
                            class="text-primary/15 absolute -top-6 right-2 text-7xl font-black tabular-nums select-none"
                        >
                            {{ i + 1 }}
                        </span>
                        <div
                            class="bg-card border-border/60 relative h-full rounded-2xl border p-6"
                        >
                            <span
                                class="bg-primary/10 text-primary inline-flex size-11 items-center justify-center rounded-xl"
                            >
                                <component :is="step.icon" class="size-5" />
                            </span>
                            <h3 class="mt-5 text-lg font-semibold">
                                {{ step.title }}
                            </h3>
                            <p
                                class="text-muted-foreground mt-2 text-sm leading-6"
                            >
                                {{ step.description }}
                            </p>
                        </div>
                    </div>
                </Reveal>
            </div>
        </div>
    </section>
</template>
