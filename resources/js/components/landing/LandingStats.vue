<script setup lang="ts">
import Reveal from "@/components/landing/Reveal.vue";

const stats = [
    { value: "200+", label: "Days undetected" },
    { value: "12,000+", label: "Active users" },
    { value: "20+", label: "Supported games" },
    { value: "99.9%", label: "Uptime" },
];
</script>

<template>
    <section class="border-border/60 border-y">
        <Reveal>
            <div
                class="mx-auto grid max-w-6xl grid-cols-2 divide-x divide-y divide-border/60 sm:grid-cols-4 sm:divide-y-0"
            >
                <div
                    v-for="stat in stats"
                    :key="stat.label"
                    class="px-6 py-8 text-center"
                >
                    <p
                        class="from-primary bg-gradient-to-r to-orange-400 bg-clip-text text-3xl font-bold tracking-tight text-transparent tabular-nums sm:text-4xl"
                    >
                        {{ stat.value }}
                    </p>
                    <p class="text-muted-foreground mt-1 text-sm">
                        {{ stat.label }}
                    </p>
                </div>
            </div>
        </Reveal>
    </section>
</template>
