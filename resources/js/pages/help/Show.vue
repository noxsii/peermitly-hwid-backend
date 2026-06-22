<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ArrowLeft } from "@lucide/vue";
import { computed } from "vue";
import PageLayout from "@/layout/PageLayout.vue";
import type { HelpArticle } from "@/types";

const props = defineProps<{
    article: { data: HelpArticle };
}>();

const article = computed(() => props.article.data);

const formatDate = (iso: string | null): string => {
    if (!iso) return "";
    try {
        return new Date(iso).toLocaleDateString(undefined, {
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
    <PageLayout :title="article.title">
        <template #actions>
            <Link
                href="/help"
                class="text-muted-foreground hover:text-foreground inline-flex items-center gap-1 text-xs font-medium transition-colors"
            >
                <ArrowLeft class="size-3" />
                Back to all articles
            </Link>
        </template>

        <article
            class="bg-card text-card-foreground border-border/70 mx-auto max-w-3xl rounded-2xl border p-6 shadow-sm md:p-10"
        >
            <p
                v-if="article.published_at"
                class="text-muted-foreground mb-3 text-xs"
            >
                Published {{ formatDate(article.published_at) }}
            </p>
            <p
                v-if="article.excerpt"
                class="text-muted-foreground mb-6 text-base leading-7"
            >
                {{ article.excerpt }}
            </p>

            <div
                class="prose prose-sm dark:prose-invert text-foreground/90 max-w-none [&_a]:text-primary [&_a]:underline [&_h1]:mt-6 [&_h1]:text-2xl [&_h1]:font-semibold [&_h2]:mt-5 [&_h2]:text-xl [&_h2]:font-semibold [&_h3]:mt-4 [&_h3]:text-lg [&_h3]:font-semibold [&_ol]:my-3 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:my-3 [&_p]:leading-7 [&_pre]:bg-muted [&_pre]:rounded-md [&_pre]:p-3 [&_pre]:text-xs [&_ul]:my-3 [&_ul]:list-disc [&_ul]:pl-6"
                v-html="article.content"
            />
        </article>
    </PageLayout>
</template>
