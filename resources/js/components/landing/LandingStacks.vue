<script setup lang="ts">
import { Boxes } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";

const stacks = [
    "Laravel",
    "Symfony",
    "WordPress",
    "Statamic",
    "Node.js",
    "Next.js",
    "Nuxt",
    "Django",
    "Flask",
    "Rails",
    "Express",
    "Vite",
    "Astro",
    "Go",
];

// Duplicate the list so the marquee loops seamlessly.
const track = [...stacks, ...stacks];
</script>

<template>
    <section id="stacks" class="scroll-mt-20 py-24">
        <div class="mx-auto max-w-6xl px-6">
            <Reveal class="mx-auto max-w-2xl text-center">
                <span
                    class="text-primary text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    Supported stacks
                </span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    Works with your favourite frameworks
                </h2>
                <p class="text-muted-foreground mt-4 text-lg">
                    20+ frameworks detected and served out of the box, with new
                    ones added every month.
                </p>
            </Reveal>
        </div>

        <div
            class="relative mt-12 overflow-hidden [mask-image:linear-gradient(to_right,transparent,#000_12%,#000_88%,transparent)]"
        >
            <div class="flex w-max animate-marquee gap-4">
                <div
                    v-for="(stack, i) in track"
                    :key="`${stack}-${i}`"
                    class="bg-card border-border/60 flex items-center gap-2.5 rounded-xl border px-5 py-3 whitespace-nowrap"
                >
                    <Boxes class="text-primary size-4" />
                    <span class="text-sm font-semibold">{{ stack }}</span>
                </div>
            </div>
        </div>
    </section>
</template>
