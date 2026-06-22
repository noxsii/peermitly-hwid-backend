<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ArrowRight } from "@lucide/vue";
import { Button } from "@/components/ui/button";
</script>

<template>
    <section
        class="from-background to-muted/40 relative isolate overflow-hidden bg-gradient-to-b"
    >
        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 -z-10"
            style="
                background-image:
                    radial-gradient(
                        circle at 18% 12%,
                        rgba(99, 102, 241, 0.12),
                        transparent 55%
                    ),
                    radial-gradient(
                        circle at 82% 0%,
                        rgba(56, 189, 248, 0.1),
                        transparent 50%
                    );
            "
        ></div>

        <div class="mx-auto max-w-6xl px-6 pt-24 pb-20 md:pt-32 md:pb-28">
            <p
                class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
            >
                License Management
            </p>
            <h1
                class="text-foreground mt-4 max-w-3xl text-5xl font-semibold tracking-tight text-balance md:text-6xl"
            >
                Ship software.<br />Control access.
            </h1>
            <p class="text-muted-foreground mt-6 max-w-2xl text-lg md:text-xl">
                License keys, HWID binding, and an activation API for your own
                products — in one calm dashboard.
            </p>

            <div class="mt-10 flex flex-wrap items-center gap-3">
                <Button as-child size="lg">
                    <Link :href="route('login')" class="gap-2">
                        Sign in
                        <ArrowRight class="size-4" />
                    </Link>
                </Button>
                <Button as-child size="lg" variant="outline">
                    <a href="#features">How it works</a>
                </Button>
            </div>

            <div
                class="border-border/70 bg-card text-card-foreground mt-14 inline-flex max-w-full items-center gap-3 rounded-2xl border px-4 py-3 shadow-sm"
            >
                <span class="relative inline-flex size-2.5" aria-hidden="true">
                    <span
                        class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"
                    ></span>
                    <span
                        class="relative inline-flex size-2.5 rounded-full bg-emerald-500"
                    ></span>
                </span>
                <span class="text-foreground font-mono text-sm tracking-wide"
                    >PRMT-9F2A-4E11-XR07</span
                >
                <span class="text-muted-foreground hidden text-xs sm:inline"
                    >·</span
                >
                <span class="text-muted-foreground hidden text-xs sm:inline"
                    >active · HWID locked</span
                >
            </div>
        </div>
    </section>
</template>
