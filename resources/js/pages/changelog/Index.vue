<script setup lang="ts">
import { Head, InfiniteScroll, Link } from "@inertiajs/vue3";
import { Newspaper } from "@lucide/vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LogoMark from "@/components/Logo.vue";
import type { Changelog } from "@/types";

defineOptions({ layout: "" });

defineProps<{
    entries: { data: Changelog[] };
}>();

const formatDate = (iso: string | null): string => {
    if (!iso) return "";
    try {
        return new Date(iso).toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    } catch {
        return "";
    }
};
</script>

<template>
    <Head title="Changelog — Peermitly">
        <meta
            name="description"
            content="Every new feature, improvement and fix in Peermitly — release by release."
            head-key="description"
        />
    </Head>

    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-3xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="flex items-center gap-2.5">
                <LogoMark size="size-9" />
                <span class="text-lg font-semibold tracking-tight"
                    >Peermitly</span
                >
            </Link>
            <Link
                href="/login"
                class="text-muted-foreground hover:text-foreground text-sm"
                >Log in</Link
            >
        </header>

        <main class="mx-auto max-w-3xl px-6 py-16 md:py-24">
            <header class="mb-16 space-y-3">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
                >
                    What's new
                </p>
                <h1
                    class="text-foreground text-4xl font-semibold tracking-tight md:text-5xl"
                >
                    Changelog
                </h1>
                <p class="text-muted-foreground max-w-xl text-sm leading-6">
                    Every new feature, improvement and fix in Peermitly —
                    release by release.
                </p>
            </header>

            <InfiniteScroll
                v-if="entries.data.length"
                data="entries"
                only-next
                preserve-url
            >
                <ol
                    class="border-border/60 relative space-y-14 border-l pl-8 md:pl-10"
                >
                    <li
                        v-for="entry in entries.data"
                        :key="entry.uuid"
                        class="relative"
                    >
                        <span
                            class="border-primary/50 bg-background absolute top-1.5 -left-[41px] flex size-4 items-center justify-center rounded-full border md:-left-[49px]"
                            aria-hidden="true"
                        >
                            <span class="bg-primary size-1.5 rounded-full" />
                        </span>

                        <article class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <span
                                    v-if="entry.version"
                                    class="border-primary/30 bg-primary/10 text-primary inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold"
                                >
                                    {{ entry.version }}
                                </span>
                                <time
                                    v-if="entry.published_at"
                                    :datetime="entry.published_at"
                                    class="text-muted-foreground text-xs"
                                >
                                    {{ formatDate(entry.published_at) }}
                                </time>
                            </div>

                            <h2
                                class="text-foreground text-xl font-semibold tracking-tight"
                            >
                                {{ entry.title }}
                            </h2>

                            <div
                                class="prose prose-sm dark:prose-invert text-foreground/90 max-w-none [&_a]:text-primary [&_a]:underline [&_h1]:mt-4 [&_h1]:text-xl [&_h1]:font-semibold [&_h2]:mt-3 [&_h2]:text-lg [&_h2]:font-semibold [&_h3]:mt-2 [&_h3]:font-semibold [&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:my-2 [&_p]:leading-6 [&_pre]:bg-muted [&_pre]:rounded-md [&_pre]:p-3 [&_pre]:text-xs [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-5"
                                v-html="entry.content"
                            />
                        </article>
                    </li>
                </ol>

                <template #loading>
                    <div
                        class="text-muted-foreground flex items-center justify-center gap-2 py-10 text-sm"
                    >
                        <span
                            class="border-primary size-4 animate-spin rounded-full border-2 border-t-transparent"
                            aria-hidden="true"
                        />
                        Loading more releases…
                    </div>
                </template>
            </InfiniteScroll>

            <div
                v-else
                class="border-border/60 flex flex-col items-center gap-2 rounded-xl border py-16 text-center"
            >
                <Newspaper
                    class="text-muted-foreground size-8"
                    aria-hidden="true"
                />
                <p class="text-foreground text-sm font-medium">
                    No releases yet.
                </p>
                <p class="text-muted-foreground text-xs">
                    New releases will appear here as soon as they ship.
                </p>
            </div>
        </main>

        <LandingFooter />
    </div>
</template>
