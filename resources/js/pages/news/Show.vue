<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft } from "@lucide/vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LogoMark from "@/components/Logo.vue";
import type { News } from "@/types";

defineOptions({ layout: "" });

const props = defineProps<{
    article: News;
    url: string;
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

        <main class="mx-auto max-w-3xl px-6 py-12 md:py-16">
            <Link
                href="/news"
                class="text-muted-foreground hover:text-foreground mb-8 inline-flex items-center gap-1.5 text-sm"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                All news
            </Link>

            <article class="space-y-6">
                <div class="space-y-3">
                    <time
                        v-if="article.published_at"
                        :datetime="article.published_at"
                        class="text-muted-foreground text-xs"
                    >
                        {{ formatDate(article.published_at) }}
                    </time>
                    <h1
                        class="text-foreground text-3xl font-semibold tracking-tight md:text-4xl"
                    >
                        {{ article.title }}
                    </h1>
                    <p class="text-muted-foreground text-lg leading-7">
                        {{ article.description }}
                    </p>
                </div>

                <img
                    v-if="article.image_url"
                    :src="article.image_url"
                    :alt="article.title"
                    class="max-h-[440px] w-full rounded-xl object-cover"
                />

                <div
                    class="prose prose-sm dark:prose-invert text-foreground/90 max-w-none [&_a]:text-primary [&_a]:underline [&_h1]:mt-4 [&_h1]:text-xl [&_h1]:font-semibold [&_h2]:mt-3 [&_h2]:text-lg [&_h2]:font-semibold [&_h3]:mt-2 [&_h3]:font-semibold [&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:my-2 [&_p]:leading-6 [&_pre]:bg-muted [&_pre]:rounded-md [&_pre]:p-3 [&_pre]:text-xs [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-5"
                    v-html="article.content"
                />
            </article>
        </main>

        <LandingFooter />
    </div>
</template>
