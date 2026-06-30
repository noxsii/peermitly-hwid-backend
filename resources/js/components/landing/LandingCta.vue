<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { ArrowRight } from "@lucide/vue";
import { computed } from "vue";
import Reveal from "@/components/landing/Reveal.vue";
import { Button } from "@/components/ui/button";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();
const isAuthenticated = computed(() => page.props.auth?.user != null);
</script>

<template>
    <section class="px-6 py-24">
        <Reveal class="mx-auto max-w-5xl">
            <div
                class="border-primary/30 bg-card relative overflow-hidden rounded-3xl border px-6 py-16 text-center"
            >
                <div
                    class="landing-grid pointer-events-none absolute inset-0"
                />
                <div
                    class="bg-primary/25 pointer-events-none absolute -bottom-24 left-1/2 size-96 -translate-x-1/2 rounded-full blur-[100px]"
                />

                <div class="relative">
                    <h2
                        class="from-foreground to-primary/70 mx-auto max-w-2xl bg-gradient-to-br bg-clip-text text-3xl font-bold tracking-tight text-balance text-transparent sm:text-4xl"
                    >
                        Ready to ditch your old dev setup?
                    </h2>
                    <p
                        class="text-muted-foreground mx-auto mt-4 max-w-xl text-lg"
                    >
                        Join 12,000+ developers building faster with Peermitly.
                        Instant setup, every stack.
                    </p>
                    <div
                        class="mt-8 flex flex-wrap items-center justify-center gap-3"
                    >
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
                                {{ isAuthenticated ? "Dashboard" : "Log in" }}
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </Reveal>
    </section>
</template>
