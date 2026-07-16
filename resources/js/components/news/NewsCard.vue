<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ArrowUpRight, Newspaper } from "@lucide/vue";
import { formatDate } from "@/lib/date";
import type { News } from "@/types";

defineProps<{
    article: News;
}>();
</script>

<template>
    <Link
        :href="`/news/${article.slug}`"
        class="group border-border/60 bg-card hover:border-primary/50 hover:shadow-primary/5 relative flex flex-col overflow-hidden rounded-2xl border transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
    >
        <div class="bg-muted relative aspect-video w-full overflow-hidden">
            <img
                v-if="article.image_url"
                :src="article.image_url"
                :alt="article.title"
                loading="lazy"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div
                v-else
                class="from-primary/25 via-primary/10 relative h-full w-full bg-gradient-to-br to-transparent"
                aria-hidden="true"
            >
                <Newspaper
                    class="text-primary/30 absolute top-1/2 left-1/2 size-12 -translate-x-1/2 -translate-y-1/2"
                />
            </div>
            <div
                class="ring-border/50 pointer-events-none absolute inset-0 rounded-t-2xl ring-1 ring-inset"
                aria-hidden="true"
            />
        </div>

        <div class="flex flex-1 flex-col gap-2.5 p-5">
            <time
                v-if="article.published_at"
                :datetime="article.published_at"
                class="text-muted-foreground text-xs font-medium tracking-wide uppercase"
            >
                {{ formatDate(article.published_at) }}
            </time>
            <h2
                class="text-foreground group-hover:text-primary text-lg font-semibold tracking-tight transition-colors"
            >
                {{ article.title }}
            </h2>
            <p class="text-muted-foreground line-clamp-3 text-sm leading-6">
                {{ article.description }}
            </p>
            <span
                class="text-primary mt-auto inline-flex items-center gap-1 pt-2 text-sm font-medium opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            >
                Read more
                <ArrowUpRight class="size-4" aria-hidden="true" />
            </span>
        </div>
    </Link>
</template>
