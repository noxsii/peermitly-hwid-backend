<script setup lang="ts">
import Reveal from "@/components/landing/Reveal.vue";

const featured = "/images/screenshots/landing/1.png?v=2";

const tiles = [
    {
        src: "/images/screenshots/landing/2.png",
        title: "Scaffold any framework",
        description:
            "Spin up a fresh Laravel, Symfony, Vue, React, Nuxt or Astro project in a single click.",
    },
    {
        src: "/images/screenshots/landing/4.png",
        title: "Databases, fully managed",
        description:
            "MariaDB, MySQL, PostgreSQL and MongoDB — install, start and open in Adminer without touching a terminal.",
    },
    {
        src: "/images/screenshots/landing/7.png",
        title: "Catch every email",
        description:
            "A built-in local mail catcher inspects outgoing mail without ever sending a real message.",
    },
    {
        src: "/images/screenshots/landing/6.png",
        title: "Make it yours",
        description:
            "Show, hide and reorder every sidebar entry so the app looks exactly the way you work.",
    },
];
</script>

<template>
    <section id="product" class="scroll-mt-20 py-24">
        <div class="mx-auto max-w-6xl px-6">
            <Reveal class="mx-auto max-w-2xl text-center">
                <span
                    class="text-primary inline-flex items-center gap-2 text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    <span class="bg-primary/60 size-1.5 rounded-full" />
                    A look inside
                </span>
                <h2
                    class="from-foreground to-primary/70 mt-3 bg-gradient-to-br bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                >
                    One calm dashboard for everything
                </h2>
                <p class="text-muted-foreground mt-4 text-lg">
                    Every runtime, service and database in a single, native app
                    — no terminal gymnastics required.
                </p>
            </Reveal>

            <!-- Featured window -->
            <Reveal class="relative mt-14">
                <div
                    class="from-primary/25 pointer-events-none absolute -inset-x-8 -top-10 bottom-0 -z-10 bg-gradient-to-b to-transparent opacity-60 blur-[90px]"
                    aria-hidden="true"
                />
                <figure
                    class="ring-border/60 bg-card overflow-hidden rounded-2xl border shadow-2xl ring-1"
                >
                    <div
                        class="border-border/60 bg-muted/40 flex items-center gap-1.5 border-b px-4 py-3"
                    >
                        <span class="size-3 rounded-full bg-red-400/80" />
                        <span class="size-3 rounded-full bg-amber-400/80" />
                        <span class="size-3 rounded-full bg-emerald-400/80" />
                    </div>
                    <img
                        :src="featured"
                        alt="The Peermitly dashboard managing PHP versions"
                        class="block h-auto w-full"
                        loading="lazy"
                        decoding="async"
                    />
                </figure>
            </Reveal>

            <!-- Feature tiles -->
            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                <Reveal
                    v-for="(tile, i) in tiles"
                    :key="tile.title"
                    :delay="(i % 2) * 80"
                >
                    <figure
                        class="group bg-card border-border/60 hover:border-primary/40 h-full overflow-hidden rounded-2xl border transition-colors"
                    >
                        <div
                            class="border-border/60 bg-muted/30 aspect-[16/10] overflow-hidden border-b"
                        >
                            <img
                                :src="tile.src"
                                :alt="tile.title"
                                class="size-full object-cover object-top transition-transform duration-500 group-hover:scale-[1.03]"
                                loading="lazy"
                                decoding="async"
                            />
                        </div>
                        <figcaption class="p-6">
                            <h3 class="text-lg font-semibold">
                                {{ tile.title }}
                            </h3>
                            <p
                                class="text-muted-foreground mt-2 text-sm leading-6"
                            >
                                {{ tile.description }}
                            </p>
                        </figcaption>
                    </figure>
                </Reveal>
            </div>
        </div>
    </section>
</template>
