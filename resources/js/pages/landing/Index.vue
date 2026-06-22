<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import LandingCta from "@/components/landing/LandingCta.vue";
import LandingFaq from "@/components/landing/LandingFaq.vue";
import LandingFeatures from "@/components/landing/LandingFeatures.vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LandingGames from "@/components/landing/LandingGames.vue";
import LandingHero from "@/components/landing/LandingHero.vue";
import LandingNav from "@/components/landing/LandingNav.vue";
import LandingPricing from "@/components/landing/LandingPricing.vue";
import LandingStats from "@/components/landing/LandingStats.vue";
import LandingSteps from "@/components/landing/LandingSteps.vue";
import type { LandingSeoProps } from "@/types/landing";

defineProps<LandingSeoProps>();
defineOptions({ layout: "" });

const title = "Peermitly — Undetectable HWID Spoofer for CoD, Valorant & More";
const description =
    "Peermitly is an undetectable HWID spoofer. Mask and rotate every hardware identifier, beat HWID bans and get back in the game in seconds. 20+ games supported.";
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
    </Head>

    <div class="bg-background text-foreground min-h-screen">
        <LandingNav />
        <main>
            <LandingHero />
            <LandingStats />
            <LandingFeatures />
            <LandingSteps />
            <LandingGames />
            <LandingPricing />
            <LandingFaq />
            <LandingCta />
        </main>
        <LandingFooter />
    </div>
</template>
