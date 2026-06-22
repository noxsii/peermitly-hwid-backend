<script setup lang="ts">
import { Cpu, EyeOff, Lock, RefreshCw, Trash2, Zap } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";

const features = [
    {
        icon: EyeOff,
        title: "Truly undetectable",
        description:
            "Engineered to stay ahead of anti-cheat HWID checks so your new identity reads as a fresh machine.",
    },
    {
        icon: Zap,
        title: "Instant spoof",
        description:
            "One click rewrites every identifier in seconds. No reboot juggling, no manual registry edits.",
    },
    {
        icon: Cpu,
        title: "Full hardware coverage",
        description:
            "Disk, network adapter, GPU, motherboard, BIOS and more — all components spoofed together.",
    },
    {
        icon: Trash2,
        title: "Trace cleaner",
        description:
            "Wipes leftover ban traces and logs the anti-cheat uses to link your old hardware to the new one.",
    },
    {
        icon: RefreshCw,
        title: "Unlimited rotations",
        description:
            "Spoof as often as you need. Every session can run on a brand-new, randomized identity.",
    },
    {
        icon: Lock,
        title: "Safe & reversible",
        description:
            "Automatic backups let you restore your original identifiers any time, with zero risk to your system.",
    },
];
</script>

<template>
    <section id="features" class="scroll-mt-20 py-24">
        <div class="mx-auto max-w-6xl px-6">
            <Reveal class="mx-auto max-w-2xl text-center">
                <span
                    class="text-primary text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    Features
                </span>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl"
                >
                    Everything you need to stay unbanned
                </h2>
                <p class="text-muted-foreground mt-4 text-lg">
                    A complete spoofing toolkit, wrapped in one calm dashboard.
                </p>
            </Reveal>

            <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Reveal
                    v-for="(feature, i) in features"
                    :key="feature.title"
                    :delay="(i % 3) * 80"
                >
                    <div
                        class="group bg-card border-border/60 hover:border-primary/40 relative h-full overflow-hidden rounded-2xl border p-6 transition-all duration-300 hover:-translate-y-1"
                    >
                        <div
                            class="bg-primary/10 pointer-events-none absolute -top-16 -right-16 size-32 rounded-full opacity-0 blur-2xl transition-opacity duration-300 group-hover:opacity-100"
                        />
                        <span
                            class="bg-primary/10 text-primary ring-primary/15 inline-flex size-11 items-center justify-center rounded-xl ring-1"
                        >
                            <component :is="feature.icon" class="size-5" />
                        </span>
                        <h3 class="mt-5 text-lg font-semibold">
                            {{ feature.title }}
                        </h3>
                        <p
                            class="text-muted-foreground mt-2 text-sm leading-6"
                        >
                            {{ feature.description }}
                        </p>
                    </div>
                </Reveal>
            </div>
        </div>
    </section>
</template>
