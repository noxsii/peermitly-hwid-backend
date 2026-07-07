<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import LandingCta from "@/components/landing/LandingCta.vue";
import LandingDownload from "@/components/landing/LandingDownload.vue";
import LandingFaq from "@/components/landing/LandingFaq.vue";
import LandingFeatures from "@/components/landing/LandingFeatures.vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LandingHero from "@/components/landing/LandingHero.vue";
import LandingNav from "@/components/landing/LandingNav.vue";
import LandingPricing from "@/components/landing/LandingPricing.vue";
import LandingShowcase from "@/components/landing/LandingShowcase.vue";
import LandingStacks from "@/components/landing/LandingStacks.vue";
import LandingStats from "@/components/landing/LandingStats.vue";
import LandingSteps from "@/components/landing/LandingSteps.vue";
import type { LandingSeoProps } from "@/types/landing";

defineProps<LandingSeoProps>();
defineOptions({ layout: "" });

const title = "Peermitly — The fast, beautiful local dev environment";
const description =
    "Peermitly is a blazing-fast local development environment for macOS. PHP, Node, Python, Ruby and Go, databases and services — zero config, instant .peer domains with automatic HTTPS.";
</script>

<template>
    <Head :title="title">
        <meta
            name="description"
            :content="description"
            head-key="description"
        />
        <link rel="canonical" :href="canonical" head-key="canonical" />
        <meta name="robots" content="index, follow" head-key="robots" />
    </Head>

    <div class="bg-background text-foreground min-h-screen">
        <LandingNav />
        <main>
            <LandingHero />
            <LandingStats />
            <LandingFeatures />
            <LandingShowcase />
            <LandingSteps />
            <LandingStacks />
            <LandingDownload />
            <LandingPricing />
            <LandingFaq />
            <LandingCta />
        </main>
        <LandingFooter />
    </div>
</template>
