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
const imageAlt =
    "Peermitly — a fast, beautiful local development environment for macOS";
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
        <meta name="theme-color" content="#f97316" head-key="theme-color" />

        <meta property="og:type" content="website" head-key="og:type" />
        <meta property="og:locale" content="en_US" head-key="og:locale" />
        <meta
            property="og:site_name"
            :content="siteName"
            head-key="og:site_name"
        />
        <meta property="og:title" :content="title" head-key="og:title" />
        <meta
            property="og:description"
            :content="description"
            head-key="og:description"
        />
        <meta property="og:url" :content="canonical" head-key="og:url" />
        <meta property="og:image" :content="ogImage" head-key="og:image" />
        <meta
            property="og:image:width"
            content="1200"
            head-key="og:image:width"
        />
        <meta
            property="og:image:height"
            content="630"
            head-key="og:image:height"
        />
        <meta
            property="og:image:alt"
            :content="imageAlt"
            head-key="og:image:alt"
        />

        <meta
            name="twitter:card"
            content="summary_large_image"
            head-key="tw:card"
        />
        <meta name="twitter:title" :content="title" head-key="tw:title" />
        <meta
            name="twitter:description"
            :content="description"
            head-key="tw:description"
        />
        <meta name="twitter:image" :content="ogImage" head-key="tw:image" />
        <meta
            name="twitter:image:alt"
            :content="imageAlt"
            head-key="tw:image:alt"
        />
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
