<script setup lang="ts">
import { Head, InfiniteScroll } from "@inertiajs/vue3";
import { Newspaper } from "@lucide/vue";
import NewsCard from "@/components/news/NewsCard.vue";
import NewsPageShell from "@/components/news/NewsPageShell.vue";
import type { News } from "@/types";

defineOptions({ layout: "" });

defineProps<{
    entries: { data: News[] };
}>();
</script>

<template>
    <Head title="News — Peermitly">
        <meta
            name="description"
            content="Product news, announcements and stories from the Peermitly team."
            head-key="description"
        />
    </Head>

    <NewsPageShell>
        <main class="mx-auto max-w-5xl px-6 py-16 md:py-24">
            <header class="mb-16 space-y-3">
                <p
                    class="text-primary text-xs font-semibold tracking-[0.18em] uppercase"
                >
                    What's new
                </p>
                <h1
                    class="from-foreground to-primary bg-gradient-to-br bg-clip-text text-4xl font-extrabold tracking-tight text-transparent md:text-5xl"
                >
                    News
                </h1>
                <p class="text-muted-foreground max-w-xl text-sm leading-6">
                    Product news, announcements and stories from the Peermitly
                    team.
                </p>
            </header>

            <InfiniteScroll
                v-if="entries.data.length"
                data="entries"
                only-next
                preserve-url
            >
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <NewsCard
                        v-for="entry in entries.data"
                        :key="entry.uuid"
                        :article="entry"
                    />
                </div>

                <template #loading>
                    <div
                        class="text-muted-foreground flex items-center justify-center gap-2 py-10 text-sm"
                    >
                        <span
                            class="border-primary size-4 animate-spin rounded-full border-2 border-t-transparent"
                            aria-hidden="true"
                        />
                        Loading more news…
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
                <p class="text-foreground text-sm font-medium">No news yet.</p>
                <p class="text-muted-foreground text-xs">
                    New articles will appear here as soon as they are published.
                </p>
            </div>
        </main>
    </NewsPageShell>
</template>
