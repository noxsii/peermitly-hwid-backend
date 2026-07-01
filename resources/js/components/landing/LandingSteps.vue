<script setup lang="ts">
import { Download, FolderPlus, Rocket } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";

const steps = [
    {
        icon: Download,
        title: "Install Peermitly",
        description:
            "Grab the app for macOS and install it in under a minute. No dependencies to set up.",
    },
    {
        icon: FolderPlus,
        title: "Add your project",
        description:
            "Point Peermitly at your code folder. It detects the stack and serves it on a .peer domain instantly.",
    },
    {
        icon: Rocket,
        title: "Start building",
        description:
            "Open the site — databases, mail and HTTPS are already wired up. Just code, no setup overhead.",
    },
];
</script>

<template>
    <section
        id="how"
        class="bg-muted/30 scroll-mt-20 border-y border-border/60 py-24"
    >
        <div class="mx-auto max-w-6xl px-6">
            <Reveal class="mx-auto max-w-2xl text-center">
                <span
                    class="text-primary inline-flex items-center gap-2 text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    <span class="bg-primary/60 size-1.5 rounded-full" />
                    How it works
                </span>
                <h2
                    class="from-foreground to-primary/70 mt-3 bg-gradient-to-br bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                >
                    Up and running in three steps
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
                            class="bg-card border-border/60 hover:border-primary/40 hover:shadow-primary/5 relative h-full rounded-2xl border p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
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
