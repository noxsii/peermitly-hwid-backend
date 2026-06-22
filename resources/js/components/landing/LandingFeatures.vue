<script setup lang="ts">
import { KeyRound, ShieldCheck, Zap } from "@lucide/vue";
import type { LandingFeature } from "@/types/landing";

const features: LandingFeature[] = [
    {
        icon: KeyRound,
        title: "Built for activation",
        description:
            "Keys stay pending until the first API call. Customers never lose days they didn't use.",
    },
    {
        icon: ShieldCheck,
        title: "Hardware-bound",
        description:
            "Optional HWID lock per key. One license, one machine, zero abuse.",
    },
    {
        icon: Zap,
        title: "One simple API",
        description:
            "POST /api/license-keys/check. Token-secured. Drop it into any product.",
    },
];
</script>

<template>
    <section id="features" class="bg-background py-20 md:py-28">
        <div class="mx-auto max-w-6xl px-6">
            <div class="max-w-2xl">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
                >
                    What you get
                </p>
                <h2
                    class="text-foreground mt-3 text-3xl font-semibold tracking-tight md:text-4xl"
                >
                    Built around the way real software ships.
                </h2>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                <article
                    v-for="feature in features"
                    :key="feature.title"
                    class="border-border/70 bg-card text-card-foreground rounded-2xl border p-6 shadow-sm"
                >
                    <div
                        class="bg-primary/10 text-primary mb-5 inline-flex size-10 items-center justify-center rounded-lg"
                        aria-hidden="true"
                    >
                        <component :is="feature.icon" class="size-5" />
                    </div>
                    <h3 class="text-foreground text-lg font-semibold">
                        {{ feature.title }}
                    </h3>
                    <p class="text-muted-foreground mt-2 text-sm leading-6">
                        {{ feature.description }}
                    </p>
                </article>
            </div>
        </div>
    </section>
</template>
