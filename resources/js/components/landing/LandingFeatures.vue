<script setup lang="ts">
import {
    Boxes,
    Bug,
    Database,
    Gauge,
    Globe,
    Lock,
    Search,
    Sparkles,
    Zap,
} from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";

const features = [
    {
        icon: Zap,
        title: "Instant sites",
        description:
            "Drop a project in your folder and it's live on a .peer domain in under a second. No config, no restarts.",
    },
    {
        icon: Boxes,
        title: "Every stack, one app",
        description:
            "PHP, Node, Python, Ruby and Go side by side. Switch runtime versions per project with a single click.",
    },
    {
        icon: Database,
        title: "Databases & services",
        description:
            "MySQL, PostgreSQL, MongoDB, Redis, Meilisearch, Typesense and more — spin them up and manage them from one dashboard.",
    },
    {
        icon: Lock,
        title: "Secure .peer domains",
        description:
            "Automatic local HTTPS with trusted certificates. Share any site over a secure tunnel in seconds.",
    },
    {
        icon: Gauge,
        title: "Built for speed",
        description:
            "Native performance, not containers. Cold starts and page loads that leave Docker setups behind.",
    },
    {
        icon: Globe,
        title: "Zero config",
        description:
            "Sensible defaults out of the box. No YAML, no Dockerfiles, no terminal gymnastics to get running.",
    },
    {
        icon: Sparkles,
        title: "Scaffold new apps",
        description:
            "Spin up fresh Vue, React, Nuxt or Astro projects in a click — dependencies installed, Vite wired up, served on HTTPS.",
    },
    {
        icon: Search,
        title: "Built-in search engines",
        description:
            "Run Meilisearch or Typesense as a managed service — install a version, start it and connect your app in seconds.",
    },
    {
        icon: Bug,
        title: "Live debug tool",
        description:
            "Call dd(), dump() or peer() in any PHP site and see the values live in a searchable window. No package to install.",
    },
];
</script>

<template>
    <section id="features" class="scroll-mt-20 py-24">
        <div class="mx-auto max-w-6xl px-6">
            <Reveal class="mx-auto max-w-2xl text-center">
                <span
                    class="text-primary inline-flex items-center gap-2 text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    <span class="bg-primary/60 size-1.5 rounded-full" />
                    Features
                </span>
                <h2
                    class="from-foreground to-primary/70 mt-3 bg-gradient-to-br bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                >
                    Everything you need to ship locally
                </h2>
                <p class="text-muted-foreground mt-4 text-lg">
                    A complete local dev environment, wrapped in one calm
                    dashboard.
                </p>
            </Reveal>

            <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Reveal
                    v-for="(feature, i) in features"
                    :key="feature.title"
                    :delay="(i % 3) * 80"
                >
                    <div
                        class="group bg-card border-border/60 hover:border-primary/40 relative h-full overflow-hidden rounded-2xl border p-6 transition-all duration-300 hover:-translate-y-1"
                    >
                        <div
                            class="bg-primary/10 pointer-events-none absolute -top-16 -right-16 size-32 rounded-full opacity-0 blur-2xl transition-opacity duration-300 group-hover:opacity-100"
                        />
                        <span
                            class="bg-primary/10 text-primary ring-primary/15 inline-flex size-11 items-center justify-center rounded-xl ring-1"
                        >
                            <component :is="feature.icon" class="size-5" />
                        </span>
                        <h3 class="mt-5 text-lg font-semibold">
                            {{ feature.title }}
                        </h3>
                        <p class="text-muted-foreground mt-2 text-sm leading-6">
                            {{ feature.description }}
                        </p>
                    </div>
                </Reveal>
            </div>
        </div>
    </section>
</template>
