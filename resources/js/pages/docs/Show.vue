<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import DocsLayout from "@/components/docs/DocsLayout.vue";
import { getDoc } from "@/lib/docs";
import type { DocSection } from "@/types";

const props = defineProps<{
    slug: string;
    sections: DocSection[];
    canonical: string;
}>();

defineOptions({ layout: "" });

const page = computed(() => getDoc(props.slug));

const title = computed(
    () => `${page.value?.meta.title ?? "Documentation"} — Peermitly Docs`,
);

const description = computed(
    () =>
        page.value?.meta.description ??
        "Documentation for Peermitly, the fast local development environment.",
);
</script>

<template>
    <Head :title="title">
        <meta
            name="description"
            :content="description"
            head-key="description"
        />
        <link rel="canonical" :href="canonical" head-key="canonical" />
        <meta name="theme-color" content="#f97316" head-key="theme-color" />
    </Head>

    <DocsLayout :sections="sections" :current-slug="slug" :page="page" />
</template>
