<script setup lang="ts">
import { Deferred } from "@inertiajs/vue3";
import { Newspaper } from "@lucide/vue";
import Card from "@/components/Card.vue";
import { DataTablePagination } from "@/components/table";
import type { PaginationMeta } from "@/components/table";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type { Changelog } from "@/types";

defineProps<{
    entries?: { data: Changelog[]; meta: PaginationMeta } | null;
}>();

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
    <PageLayout title="Changelog">
        <div class="mx-auto w-full max-w-3xl space-y-4">
            <Deferred data="entries">
                <template #fallback>
                    <div class="space-y-3">
                        <Skeleton class="h-32 w-full" />
                        <Skeleton class="h-32 w-full" />
                    </div>
                </template>

                <template v-if="entries?.data?.length">
                    <div class="space-y-4">
                        <Card
                            v-for="entry in entries.data"
                            :key="entry.uuid"
                            :title="entry.version ?? undefined"
                        >
                            <article class="space-y-3">
                                <header
                                    class="flex flex-wrap items-baseline justify-between gap-2"
                                >
                                    <h2
                                        class="text-foreground text-lg font-semibold tracking-tight"
                                    >
                                        {{ entry.title }}
                                    </h2>
                                    <time
                                        v-if="entry.published_at"
                                        :datetime="entry.published_at"
                                        class="text-muted-foreground text-xs"
                                    >
                                        {{ formatDate(entry.published_at) }}
                                    </time>
                                </header>
                                <div
                                    class="prose prose-sm dark:prose-invert text-foreground/90 max-w-none [&_a]:text-primary [&_a]:underline [&_h1]:mt-4 [&_h1]:text-xl [&_h1]:font-semibold [&_h2]:mt-3 [&_h2]:text-lg [&_h2]:font-semibold [&_h3]:mt-2 [&_h3]:font-semibold [&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:my-2 [&_p]:leading-6 [&_pre]:bg-muted [&_pre]:rounded-md [&_pre]:p-3 [&_pre]:text-xs [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-5"
                                    v-html="entry.content"
                                />
                            </article>
                        </Card>
                    </div>

                    <DataTablePagination
                        v-if="entries.meta"
                        :pagination="entries.meta"
                    />
                </template>

                <Card v-else title="Changelog">
                    <div
                        class="flex flex-col items-center gap-2 py-12 text-center"
                    >
                        <Newspaper
                            class="text-muted-foreground size-8"
                            aria-hidden="true"
                        />
                        <p class="text-foreground text-sm font-medium">
                            No changelog entries yet.
                        </p>
                        <p class="text-muted-foreground text-xs">
                            New releases will appear here once published from
                            the admin panel.
                        </p>
                    </div>
                </Card>
            </Deferred>
        </div>
    </PageLayout>
</template>
