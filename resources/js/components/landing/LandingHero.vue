<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { ArrowRight, ShieldCheck } from "@lucide/vue";
import { computed } from "vue";
import { Button } from "@/components/ui/button";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();
const isAuthenticated = computed(() => page.props.auth?.user != null);

const games = [
    "Valorant",
    "Call of Duty",
    "Fortnite",
    "Apex Legends",
    "Rust",
    "PUBG",
    "CS2",
    "Rainbow Six",
];
</script>

<template>
    <section class="relative overflow-hidden">
        <!-- background grid -->
        <div class="landing-grid pointer-events-none absolute inset-0" />
        <!-- glow blobs -->
        <div
            class="bg-primary/25 pointer-events-none absolute -top-32 left-1/2 size-[40rem] -translate-x-1/2 rounded-full blur-[120px]"
        />
        <div
            class="pointer-events-none absolute top-40 -right-20 size-80 rounded-full bg-cyan-400/15 blur-[100px]"
        />

        <div
            class="relative mx-auto max-w-4xl px-6 pt-40 pb-24 text-center sm:pt-44"
        >
            <span
                class="border-border/60 bg-background/60 text-muted-foreground inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-medium backdrop-blur"
            >
                <span class="relative flex size-2">
                    <span
                        class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-500 opacity-75"
                    />
                    <span
                        class="relative inline-flex size-2 rounded-full bg-emerald-500"
                    />
                </span>
                Status: Undetected
            </span>

            <h1
                class="mt-6 text-4xl font-bold tracking-tight text-balance sm:text-6xl"
            >
                Stay invisible.
                <span
                    class="from-primary bg-gradient-to-r to-orange-400 bg-clip-text text-transparent"
                >
                    Spoof your HWID
                </span>
                in one click.
            </h1>

            <p
                class="text-muted-foreground mx-auto mt-6 max-w-2xl text-lg text-pretty"
            >
                Peermitly masks and rotates every hardware identifier on your
                machine — disk, network, GPU and board. Beat HWID bans, stay
                undetected, and get back in the game in seconds.
            </p>

            <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                <a href="#pricing">
                    <Button size="lg" class="group h-11 px-5 text-base">
                        Get access now
                        <ArrowRight
                            class="transition-transform group-hover:translate-x-0.5"
                        />
                    </Button>
                </a>
                <Link :href="isAuthenticated ? '/dashboard' : '/login'">
                    <Button
                        size="lg"
                        variant="outline"
                        class="h-11 px-5 text-base"
                    >
                        <ShieldCheck />
                        {{ isAuthenticated ? "Open dashboard" : "Member login" }}
                    </Button>
                </Link>
            </div>

            <p class="text-muted-foreground mt-4 text-xs">
                Instant delivery · Built for Windows 11 · 24/7 support
            </p>

            <!-- supported games strip -->
            <div class="mt-14">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.2em] uppercase"
                >
                    Trusted across
                </p>
                <div
                    class="mt-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-2"
                >
                    <span
                        v-for="game in games"
                        :key="game"
                        class="text-foreground/50 hover:text-foreground text-sm font-semibold transition-colors"
                    >
                        {{ game }}
                    </span>
                </div>
            </div>
        </div>
    </section>
</template>
