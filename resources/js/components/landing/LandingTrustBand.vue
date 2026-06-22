<script setup lang="ts">
import { Activity, Cloud, Plug, ShieldCheck } from "@lucide/vue";
import type { LandingTrustItem } from "@/types/landing";

const items: LandingTrustItem[] = [
    { icon: ShieldCheck, label: "HWID-locked" },
    { icon: Cloud, label: "Fully hosted" },
    { icon: Plug, label: "Activation API" },
    {
        icon: Activity,
        label: "99.9% uptime",
        href: "https://peermitly.betteruptime.com/",
    },
];
</script>

<template>
    <section class="border-border/60 bg-muted/30 border-y">
        <div
            class="text-muted-foreground mx-auto flex max-w-6xl flex-wrap items-center justify-center gap-x-8 gap-y-3 px-6 py-5 text-sm"
        >
            <component
                :is="item.href ? 'a' : 'span'"
                v-for="item in items"
                :key="item.label"
                :href="item.href"
                :target="item.href ? '_blank' : undefined"
                :rel="item.href ? 'noopener noreferrer' : undefined"
                class="hover:text-foreground inline-flex items-center gap-2 transition-colors"
            >
                <component
                    :is="item.icon"
                    class="text-primary size-4"
                    aria-hidden="true"
                />
                {{ item.label }}
            </component>
        </div>
    </section>
</template>
