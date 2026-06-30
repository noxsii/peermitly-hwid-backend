<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ArrowLeft, ArrowRight } from "@lucide/vue";
import { computed } from "vue";
import type { DocNavItem, DocSection } from "@/types";

const props = defineProps<{
    sections: DocSection[];
    currentSlug: string;
}>();

const flat = computed<DocNavItem[]>(() =>
    props.sections.flatMap((section) => section.items),
);

const index = computed(() =>
    flat.value.findIndex((item) => item.slug === props.currentSlug),
);

const previous = computed<DocNavItem | null>(() =>
    index.value > 0 ? flat.value[index.value - 1] : null,
);

const next = computed<DocNavItem | null>(() =>
    index.value >= 0 && index.value < flat.value.length - 1
        ? flat.value[index.value + 1]
        : null,
);
</script>

<template>
    <nav class="mt-16 grid gap-3 sm:grid-cols-2">
        <Link
            v-if="previous"
            :href="`/guide/${previous.slug}`"
            class="border-border/60 hover:border-primary/40 hover:bg-muted/40 hover:shadow-primary/5 group flex flex-col gap-1 rounded-xl border p-4 transition-all hover:-translate-y-0.5 hover:shadow-lg"
        >
            <span class="text-muted-foreground flex items-center gap-1 text-xs">
                <ArrowLeft
                    class="size-3 transition-transform group-hover:-translate-x-0.5"
                />
                Previous
            </span>
            <span
                class="group-hover:text-primary font-medium transition-colors"
            >
                {{ previous.title }}
            </span>
        </Link>
        <span v-else />

        <Link
            v-if="next"
            :href="`/guide/${next.slug}`"
            class="border-border/60 hover:border-primary/40 hover:bg-muted/40 hover:shadow-primary/5 group flex flex-col gap-1 rounded-xl border p-4 text-right transition-all hover:-translate-y-0.5 hover:shadow-lg sm:col-start-2"
        >
            <span
                class="text-muted-foreground flex items-center justify-end gap-1 text-xs"
            >
                Next
                <ArrowRight
                    class="size-3 transition-transform group-hover:translate-x-0.5"
                />
            </span>
            <span
                class="group-hover:text-primary font-medium transition-colors"
            >
                {{ next.title }}
            </span>
        </Link>
    </nav>
</template>
