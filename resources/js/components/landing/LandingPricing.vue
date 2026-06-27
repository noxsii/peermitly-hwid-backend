<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Check } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";
import { Button } from "@/components/ui/button";

const baseFeatures = [
    "Unlimited local sites",
    "All stacks & services",
    "Secure .test HTTPS",
    "Local databases",
];

const plans = [
    {
        name: "Day Pass",
        price: "€4.99",
        period: "/ 24 hours",
        description: "Try it out for a single day.",
        features: baseFeatures,
        popular: false,
    },
    {
        name: "Weekly",
        price: "€14.99",
        period: "/ 7 days",
        description: "For the occasional side project.",
        features: [...baseFeatures, "Priority support"],
        popular: false,
    },
    {
        name: "Monthly",
        price: "€29.99",
        period: "/ 30 days",
        description: "Best value for daily drivers.",
        features: [
            ...baseFeatures,
            "Priority support",
            "Early updates",
            "All future features",
        ],
        popular: true,
    },
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
                    Simple plans, no subscriptions hidden
                </h2>
                <p class="text-muted-foreground mt-4 text-lg">
                    Pick a duration. Instant delivery, cancel any time.
                </p>
            </Reveal>

            <div class="mx-auto mt-14 grid max-w-4xl gap-4 sm:grid-cols-3">
                <Reveal
                    v-for="(plan, i) in plans"
                    :key="plan.name"
                    :delay="(i % 3) * 80"
                >
                    <div
                        class="relative flex h-full flex-col rounded-2xl border p-6 transition-all duration-300"
                        :class="
                            plan.popular
                                ? 'border-primary/60 bg-card ring-primary/20 shadow-lg ring-1'
                                : 'border-border/60 bg-card hover:border-primary/30'
                        "
                    >
                        <span
                            v-if="plan.popular"
                            class="bg-primary text-primary-foreground absolute -top-3 left-1/2 -translate-x-1/2 rounded-full px-3 py-1 text-xs font-semibold shadow"
                        >
                            Most popular
                        </span>

                        <h3 class="text-base font-semibold">
                            {{ plan.name }}
                        </h3>
                        <p class="text-muted-foreground mt-1 text-sm">
                            {{ plan.description }}
                        </p>

                        <div class="mt-5 flex items-baseline gap-1">
                            <span class="text-4xl font-bold tracking-tight">{{
                                plan.price
                            }}</span>
                            <span class="text-muted-foreground text-sm">{{
                                plan.period
                            }}</span>
                        </div>

                        <ul class="mt-6 space-y-2.5">
                            <li
                                v-for="feature in plan.features"
                                :key="feature"
                                class="flex items-start gap-2 text-sm"
                            >
                                <Check
                                    class="text-primary mt-0.5 size-4 shrink-0"
                                />
                                <span>{{ feature }}</span>
                            </li>
                        </ul>

                        <Link href="/login" class="mt-7 block">
                            <Button
                                class="w-full"
                                :variant="plan.popular ? 'default' : 'outline'"
                            >
                                Get {{ plan.name }}
                            </Button>
                        </Link>
                    </div>
                </Reveal>
            </div>

            <p class="text-muted-foreground mt-6 text-center text-xs">
                Prices shown are examples. Secure checkout powered by Stripe.
            </p>
        </div>
    </section>
</template>
