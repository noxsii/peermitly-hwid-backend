<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ArrowRight } from "@lucide/vue";
</script>

<template>
    <section class="pb-20 md:pb-28">
        <div class="mx-auto max-w-6xl px-6">
            <div
                class="bg-foreground text-background flex flex-col items-start justify-between gap-6 rounded-3xl p-10 md:flex-row md:items-center md:p-14"
            >
                <div class="max-w-xl">
                    <h2
                        class="text-2xl font-semibold tracking-tight md:text-3xl"
                    >
                        Lock down your product today.
                    </h2>
                    <p class="text-background/70 mt-2 text-sm md:text-base">
                        Sign in and start issuing license keys in minutes.
                    </p>
                </div>
                <Link
                    :href="route('login')"
                    class="bg-background text-foreground hover:bg-background/90 inline-flex h-11 shrink-0 items-center gap-2 rounded-md px-5 text-sm font-medium transition-colors"
                >
                    Sign in
                    <ArrowRight class="size-4" />
                </Link>
            </div>
        </div>
    </section>
</template>
