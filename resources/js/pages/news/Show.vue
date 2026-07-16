<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import NewsContent from "@/components/news/NewsContent.vue";
import NewsHero from "@/components/news/NewsHero.vue";
import NewsPageShell from "@/components/news/NewsPageShell.vue";
import type { News } from "@/types";

defineOptions({ layout: "" });

const props = defineProps<{
    article: News;
    url: string;
}>();

const twitterCard = props.article.image_url ? "summary_large_image" : "summary";
</script>

<template>
    <Head :title="`${article.title} — Peermitly`">
        <meta
            name="description"
            :content="article.description"
            head-key="description"
        />
        <link rel="canonical" :href="url" head-key="canonical" />

        <meta property="og:type" content="article" head-key="og:type" />
        <meta
            property="og:site_name"
            content="Peermitly"
            head-key="og:site_name"
        />
        <meta
            property="og:title"
            :content="article.title"
            head-key="og:title"
        />
        <meta
            property="og:description"
            :content="article.description"
            head-key="og:description"
        />
        <meta property="og:url" :content="url" head-key="og:url" />
        <meta
            v-if="article.image_url"
            property="og:image"
            :content="article.image_url"
            head-key="og:image"
        />
        <meta
            v-if="article.published_at"
            property="article:published_time"
            :content="article.published_at"
            head-key="article:published_time"
        />

        <meta
            name="twitter:card"
            :content="twitterCard"
            head-key="twitter:card"
        />
        <meta
            name="twitter:title"
            :content="article.title"
            head-key="twitter:title"
        />
        <meta
            name="twitter:description"
            :content="article.description"
            head-key="twitter:description"
        />
        <meta
            v-if="article.image_url"
            name="twitter:image"
            :content="article.image_url"
            head-key="twitter:image"
        />
    </Head>

    <NewsPageShell>
        <main class="mx-auto max-w-3xl px-6 py-12 md:py-16">
            <NewsHero :article="article" />
            <div class="mt-10">
                <NewsContent :html="article.content" />
            </div>
        </main>
    </NewsPageShell>
</template>
