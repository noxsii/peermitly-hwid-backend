<script setup lang="ts">
import { Head, Link, usePage } from "@inertiajs/vue3";
import { Cpu, Fingerprint, ShieldCheck, Zap } from "@lucide/vue";
import { computed } from "vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import type { PageProps } from "@/types";
import type { LandingSeoProps } from "@/types/landing";

defineProps<LandingSeoProps>();
defineOptions({ layout: "" });

const page = usePage<PageProps>();
const isAuthenticated = computed(() => page.props.auth?.user != null);

const title = "Peermitly — HWID Spoofer";
const description =
    "Peermitly HWID Spoofer — mask and rotate your hardware identifiers safely. One backend, one dashboard.";

const features = [
    {
        icon: Fingerprint,
        title: "HWID Spoofing",
        description:
            "Generate and rotate hardware identifiers on demand so your machine stays anonymous.",
    },
    {
        icon: ShieldCheck,
        title: "Safe by design",
        description:
            "Reversible changes with backups. Restore your original identifiers any time.",
    },
    {
        icon: Zap,
        title: "Instant apply",
        description:
            "Spoof profiles apply in seconds — no reboot juggling, no manual registry edits.",
    },
    {
        icon: Cpu,
        title: "Multi-component",
        description:
            "Covers disk, network, GPU and board identifiers from a single control panel.",
    },
];
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
    </Head>

    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="flex items-center gap-2.5">
                <LogoMark size="size-9" />
                <span class="text-lg font-semibold tracking-tight">{{
                    siteName
                }}</span>
            </Link>
            <nav class="flex items-center gap-2">
                <Link v-if="isAuthenticated" href="/dashboard">
                    <Button size="sm">Dashboard</Button>
                </Link>
                <Link v-else href="/login">
                    <Button size="sm" variant="ghost">Log in</Button>
                </Link>
            </nav>
        </header>

        <main>
            <section class="mx-auto max-w-3xl px-6 py-24 text-center">
                <span
                    class="bg-primary/10 text-primary inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                >
                    <Fingerprint class="size-3.5" />
                    Hardware identity, on your terms
                </span>
                <h1 class="mt-6 text-4xl font-bold tracking-tight sm:text-5xl">
                    Spoof your HWID. Stay in control.
                </h1>
                <p class="text-muted-foreground mx-auto mt-5 max-w-xl text-lg">
                    {{ description }}
                </p>
                <div class="mt-8 flex items-center justify-center gap-3">
                    <Link :href="isAuthenticated ? '/dashboard' : '/login'">
                        <Button size="lg">Get started</Button>
                    </Link>
                </div>
            </section>

            <section class="mx-auto max-w-6xl px-6 pb-24">
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="bg-card border-border/70 rounded-2xl border p-5"
                    >
                        <span
                            class="bg-primary/10 text-primary inline-flex size-9 items-center justify-center rounded-lg"
                        >
                            <component :is="feature.icon" class="size-4" />
                        </span>
                        <h3 class="mt-4 font-semibold">{{ feature.title }}</h3>
                        <p class="text-muted-foreground mt-1 text-sm">
                            {{ feature.description }}
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <footer
            class="border-border/60 text-muted-foreground mx-auto max-w-6xl border-t px-6 py-8 text-sm"
        >
            <div class="flex items-center justify-between">
                <span>© {{ new Date().getFullYear() }} {{ siteName }}</span>
                <Link href="/privacy" class="hover:text-foreground"
                    >Privacy</Link
                >
            </div>
        </footer>
    </div>
</template>
