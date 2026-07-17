<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

defineProps<{
    title: string;
}>();
</script>

<template>
    <Head :title="title" />

    <div class="mx-auto flex h-full w-full max-w-7xl flex-col">
        <header
            class="border-border flex flex-wrap items-end justify-between gap-5 border-b pb-8 md:pb-10"
        >
            <div>
                <p
                    class="text-primary mb-3 text-xs font-semibold tracking-[0.18em] uppercase"
                >
                    Overview
                </p>
                <h1
                    class="max-w-4xl text-4xl font-medium tracking-[-0.055em] sm:text-5xl lg:text-6xl"
                >
                    {{ title }}
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <slot name="actions" />
            </div>
        </header>

        <div class="flex-1 pt-8 md:pt-10">
            <slot />
        </div>
    </div>
</template>
