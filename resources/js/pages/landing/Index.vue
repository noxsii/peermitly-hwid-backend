<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import LandingApi from "@/components/landing/LandingApi.vue";
import LandingCta from "@/components/landing/LandingCta.vue";
import LandingFaq from "@/components/landing/LandingFaq.vue";
import LandingFeatures from "@/components/landing/LandingFeatures.vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LandingHero from "@/components/landing/LandingHero.vue";
import LandingHowItWorks from "@/components/landing/LandingHowItWorks.vue";
import LandingNav from "@/components/landing/LandingNav.vue";
import LandingTrustBand from "@/components/landing/LandingTrustBand.vue";
import LandingUseCases from "@/components/landing/LandingUseCases.vue";
import type { LandingSeoProps } from "@/types/landing";

defineProps<LandingSeoProps>();
defineOptions({ layout: "" });

const title = "Peermitly — License keys, HWID binding & activation API";
const description =
    "Issue license keys for your own software. Optional HWID locking, pending-until-activation, and one token-secured API endpoint — all in one calm dashboard.";
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
        <meta name="theme-color" content="#6366f1" head-key="theme-color" />

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
        <meta property="og:locale" content="en_US" head-key="og:locale" />

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
            <LandingTrustBand />
            <LandingHowItWorks />
            <LandingFeatures />
            <LandingUseCases />
            <LandingApi />
            <LandingFaq />
            <LandingCta />
        </main>
        <LandingFooter />
    </div>
</template>
