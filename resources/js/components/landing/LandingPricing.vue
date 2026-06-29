<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Check } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";
import { Button } from "@/components/ui/button";

const features = [
    "Unlimited local sites",
    "Every stack & runtime",
    "Databases & services",
    "Secure .test HTTPS",
    "macOS, Windows & Linux",
    "All future features",
    "Priority support",
];
</script>

<template>
    <section
        id="pricing"
        class="bg-muted/30 scroll-mt-20 border-y border-border/60 py-24"
    >
        <div class="mx-auto max-w-6xl px-6">
            <Reveal class="mx-auto max-w-2xl text-center">
                <span
                    class="text-primary text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    Pricing
                </span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    One plan. Everything included.
                </h2>
                <p class="text-muted-foreground mt-4 text-lg">
                    No tiers, no add-ons. Every feature for a single flat price.
                </p>
            </Reveal>

            <Reveal class="mx-auto mt-14 max-w-md">
                <div
                    class="border-primary/60 bg-card ring-primary/20 relative flex flex-col rounded-2xl border p-8 shadow-lg ring-1"
                >
                    <span
                        class="bg-primary text-primary-foreground absolute -top-3 left-1/2 -translate-x-1/2 rounded-full px-3 py-1 text-xs font-semibold shadow"
                    >
                        All features included
                    </span>

                    <h3 class="text-base font-semibold">Peermitly Pro</h3>
                    <p class="text-muted-foreground mt-1 text-sm">
                        Everything you need for fast local development.
                    </p>

                    <div class="mt-5 flex items-baseline gap-1">
                        <span class="text-5xl font-bold tracking-tight">
                            €4.99
                        </span>
                        <span class="text-muted-foreground text-sm">
                            / month
                        </span>
                    </div>

                    <ul class="mt-6 space-y-2.5">
                        <li
                            v-for="feature in features"
                            :key="feature"
                            class="flex items-start gap-2 text-sm"
                        >
                            <Check class="text-primary mt-0.5 size-4 shrink-0" />
                            <span>{{ feature }}</span>
                        </li>
                    </ul>

                    <Link href="/login" class="mt-8 block">
                        <Button class="w-full" size="lg">Get started</Button>
                    </Link>
                </div>
            </Reveal>

            <p class="text-muted-foreground mt-6 text-center text-xs">
                Cancel any time. Secure checkout powered by Stripe.
            </p>
        </div>
    </section>
</template>
