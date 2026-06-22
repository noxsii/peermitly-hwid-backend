<script setup lang="ts">
import { Deferred, Link, router } from "@inertiajs/vue3";
import { ArrowRight, LifeBuoy, Search } from "@lucide/vue";
import { useDebounceFn } from "@vueuse/core";
import { ref, watch } from "vue";
import Card from "@/components/Card.vue";
import { DataTablePagination } from "@/components/table";
import type { PaginationMeta } from "@/components/table";
import { Input } from "@/components/ui/input";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type { HelpArticle } from "@/types";

const props = defineProps<{
    filters?: { q: string };
    articles?: { data: HelpArticle[]; meta: PaginationMeta } | null;
}>();

const query = ref<string>(props.filters?.q ?? "");

const submit = useDebounceFn((value: string) => {
    router.get("/help", value === "" ? {} : { q: value }, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}, 250);

watch(query, (value) => {
    submit(value);
});

const formatDate = (iso: string | null): string => {
    if (!iso) return "";
    try {
        return new Date(iso).toLocaleDateString(undefined, {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    } catch {
        return "";
    }
};
</script>

<template>
    <PageLayout title="Help">
        <template #actions>
            <div class="relative w-full sm:w-72">
                <Search
                    class="text-muted-foreground pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2"
                />
                <Input
                    v-model="query"
                    type="search"
                    placeholder="Search articles…"
                    aria-label="Search help articles"
                    class="h-9 w-full rounded-full pl-9"
                />
            </div>
        </template>

        <div class="space-y-4">
            <Deferred data="articles">
                <template #fallback>
                    <div
                        class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <Skeleton
                            v-for="i in 6"
                            :key="i"
                            class="h-40 w-full rounded-2xl"
                        />
                    </div>
                </template>

                <template v-if="articles?.data?.length">
                    <div
                        class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <Link
                            v-for="article in articles.data"
                            :key="article.uuid"
                            :href="`/help/${article.slug}`"
                            class="group bg-card text-card-foreground border-border/70 hover:border-primary/60 flex flex-col gap-3 rounded-2xl border p-5 shadow-sm transition-colors"
                        >
                            <h2
                                class="text-foreground text-lg font-semibold tracking-tight"
                            >
                                {{ article.title }}
                            </h2>
                            <p
                                v-if="article.excerpt"
                                class="text-muted-foreground line-clamp-3 text-sm leading-6"
                            >
                                {{ article.excerpt }}
                            </p>
                            <div
                                class="mt-auto flex items-center justify-between gap-2"
                            >
                                <time
                                    v-if="article.published_at"
                                    :datetime="article.published_at"
                                    class="text-muted-foreground text-xs"
                                >
                                    {{ formatDate(article.published_at) }}
                                </time>
                                <span
                                    class="text-primary inline-flex items-center gap-1 text-xs font-medium"
                                >
                                    Read more
                                    <ArrowRight
                                        class="size-3 transition-transform group-hover:translate-x-0.5"
                                    />
                                </span>
                            </div>
                        </Link>
                    </div>

                    <DataTablePagination
                        v-if="articles.meta"
                        :pagination="articles.meta"
                    />
                </template>

                <Card v-else title="Help">
                    <div
                        class="flex flex-col items-center gap-2 py-12 text-center"
                    >
                        <LifeBuoy
                            class="text-muted-foreground size-8"
                            aria-hidden="true"
                        />
                        <p class="text-foreground text-sm font-medium">
                            <template v-if="query">
                                No articles match „{{ query }}".
                            </template>
                            <template v-else> No help articles yet. </template>
                        </p>
                        <p class="text-muted-foreground text-xs">
                            Articles can be created from the admin panel under
                            Content → Help.
                        </p>
                    </div>
                </Card>
            </Deferred>
        </div>
    </PageLayout>
</template>
