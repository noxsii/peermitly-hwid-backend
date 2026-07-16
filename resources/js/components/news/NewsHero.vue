<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ArrowLeft } from "@lucide/vue";
import { formatDate } from "@/lib/date";
import type { News } from "@/types";

defineProps<{
    article: News;
}>();
</script>

<template>
    <div class="space-y-8">
        <Link
            href="/news"
            class="text-muted-foreground hover:text-foreground inline-flex items-center gap-1.5 text-sm transition-colors"
        >
            <ArrowLeft class="size-4" aria-hidden="true" />
            All news
        </Link>

        <div class="space-y-4">
            <p
                class="text-primary text-xs font-semibold tracking-[0.18em] uppercase"
            >
                News
                <span v-if="article.published_at" class="text-muted-foreground">
                    · {{ formatDate(article.published_at) }}
                </span>
            </p>

            <h1
                class="from-foreground to-primary bg-gradient-to-br bg-clip-text text-4xl font-extrabold tracking-tight text-transparent md:text-5xl md:leading-[1.1]"
            >
                {{ article.title }}
            </h1>

            <p class="text-muted-foreground max-w-2xl text-lg leading-7">
                {{ article.description }}
            </p>
        </div>

        <figure v-if="article.image_url" class="not-prose">
            <img
                :src="article.image_url"
                :alt="article.title"
                class="ring-border/60 max-h-[460px] w-full rounded-2xl object-cover shadow-lg ring-1"
            />
        </figure>
    </div>
</template>
