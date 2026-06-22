<script setup lang="ts">
import { Gamepad2 } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";

const games = [
    "Valorant",
    "Call of Duty",
    "Fortnite",
    "Apex Legends",
    "Rust",
    "PUBG",
    "CS2",
    "Rainbow Six",
    "EA FC",
    "FiveM",
    "Escape from Tarkov",
    "Dead by Daylight",
];

// Duplicate the list so the marquee loops seamlessly.
const track = [...games, ...games];
</script>

<template>
    <section id="games" class="scroll-mt-20 py-24">
        <div class="mx-auto max-w-6xl px-6">
            <Reveal class="mx-auto max-w-2xl text-center">
                <span
                    class="text-primary text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    Supported games
                </span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    Works where bans hurt most
                </h2>
                <p class="text-muted-foreground mt-4 text-lg">
                    20+ titles supported and new ones added every month.
                </p>
            </Reveal>
        </div>

        <div
            class="relative mt-12 overflow-hidden [mask-image:linear-gradient(to_right,transparent,#000_12%,#000_88%,transparent)]"
        >
            <div class="flex w-max animate-marquee gap-4">
                <div
                    v-for="(game, i) in track"
                    :key="`${game}-${i}`"
                    class="bg-card border-border/60 flex items-center gap-2.5 rounded-xl border px-5 py-3 whitespace-nowrap"
                >
                    <Gamepad2 class="text-primary size-4" />
                    <span class="text-sm font-semibold">{{ game }}</span>
                </div>
            </div>
        </div>
    </section>
</template>
