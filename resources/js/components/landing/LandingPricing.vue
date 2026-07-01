<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Check } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";
import { Button } from "@/components/ui/button";

const features = [
    "Unlimited local sites",
    "Every stack & runtime",
    "Databases & services",
    "Secure .peer HTTPS",
    "macOS (Apple Silicon & Intel)",
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
                    class="text-primary inline-flex items-center gap-2 text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    <span class="bg-primary/60 size-1.5 rounded-full" />
                    Pricing
                </span>
                <h2
                    class="from-foreground to-primary/70 mt-3 bg-gradient-to-br bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                >
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

                    <div class="mt-5 flex items-baseline gap-2">
                        <span
                            class="text-muted-foreground/60 text-2xl font-semibold line-through"
                        >
                            €4.99
                        </span>
                        <span class="text-5xl font-bold tracking-tight">
                            Free
                        </span>
                        <span class="text-muted-foreground text-sm">
                            during the test phase
                        </span>
                    </div>

                    <ul class="mt-6 space-y-2.5">
                        <li
                            v-for="feature in features"
                            :key="feature"
                            class="flex items-start gap-2 text-sm"
                        >
                            <Check
                                class="text-primary mt-0.5 size-4 shrink-0"
                            />
                            <span>{{ feature }}</span>
                        </li>
                    </ul>

                    <Link href="/login" class="mt-8 block">
                        <Button class="w-full" size="lg">Get started</Button>
                    </Link>
                </div>
            </Reveal>

            <div
                class="mx-auto mt-8 flex max-w-md flex-col items-center text-center"
            >
                <p class="text-muted-foreground text-sm">
                    Peermitly is currently in an open
                    <span class="text-foreground font-medium">test phase</span>
                    — free to use while we're in beta. Got feedback or need a
                    hand?
                </p>
                <a
                    href="https://discord.gg/GhKjAES2z"
                    target="_blank"
                    rel="noopener"
                    class="mt-4 inline-flex items-center gap-2 rounded-full border border-[#5865F2]/30 bg-[#5865F2]/10 px-4 py-2 text-sm font-semibold text-[#5865F2] shadow-sm transition-all hover:-translate-y-0.5 hover:bg-[#5865F2] hover:text-white hover:shadow-md hover:shadow-[#5865F2]/25"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="size-[18px]"
                        aria-hidden="true"
                    >
                        <path
                            d="M20.317 4.3698a19.7913 19.7913 0 0 0-4.8851-1.5152.0741.0741 0 0 0-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 0 0-.0785-.037 19.7363 19.7363 0 0 0-4.8852 1.515.0699.0699 0 0 0-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 0 0 .0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 0 0 .0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 0 0-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 0 1-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 0 1 .0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 0 1 .0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 0 1-.0066.1276 12.2986 12.2986 0 0 1-1.873.8914.0766.0766 0 0 0-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 0 0 .0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 0 0 .0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 0 0-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"
                        />
                    </svg>
                    Join our Discord
                </a>
            </div>
        </div>
    </section>
</template>
