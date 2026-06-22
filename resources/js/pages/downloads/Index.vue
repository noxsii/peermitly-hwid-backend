<script setup lang="ts">
import { Deferred } from "@inertiajs/vue3";
import { Download, PackageOpen } from "@lucide/vue";
import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type { AppRelease } from "@/types";

defineProps<{
    releases?: { data: AppRelease[] } | null;
}>();

const formatSize = (bytes: number): string => {
    if (bytes <= 0) return "—";
    const mb = bytes / 1_048_576;
    return `${mb.toFixed(1)} MB`;
};

const formatDate = (value: string | null): string => {
    if (!value) return "";
    return new Date(value).toLocaleDateString(undefined, {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>

<template>
    <PageLayout title="Downloads">
        <div class="mx-auto w-full max-w-3xl space-y-4">
            <p class="text-muted-foreground text-sm">
                Download the Peermitly spoofer. Always grab the latest version.
            </p>

            <Deferred data="releases">
                <template #fallback>
                    <div class="space-y-3">
                        <Skeleton
                            v-for="i in 3"
                            :key="i"
                            class="h-24 w-full rounded-2xl"
                        />
                    </div>
                </template>

                <!-- Empty state -->
                <div
                    v-if="(releases?.data.length ?? 0) === 0"
                    class="bg-card border-border/70 flex flex-col items-center rounded-3xl border p-12 text-center"
                >
                    <PackageOpen class="text-muted-foreground size-10" />
                    <h2 class="mt-4 text-lg font-semibold">No versions yet</h2>
                    <p class="text-muted-foreground mt-1 text-sm">
                        New releases will show up here as soon as they're
                        published.
                    </p>
                </div>

                <!-- Releases -->
                <ul v-else class="space-y-3">
                    <li
                        v-for="(release, index) in releases?.data ?? []"
                        :key="release.uuid"
                        class="bg-card text-card-foreground border-border/70 flex flex-col gap-4 rounded-2xl border p-5 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-base font-semibold">
                                    {{ release.version }}
                                </span>
                                <span
                                    v-if="index === 0"
                                    class="bg-primary/10 text-primary rounded-full px-2 py-0.5 text-xs font-medium"
                                >
                                    Latest
                                </span>
                            </div>
                            <p class="text-muted-foreground mt-1 text-xs">
                                {{ release.file_name }} ·
                                {{ formatSize(release.file_size) }} ·
                                {{ formatDate(release.created_at) }}
                            </p>
                            <p
                                v-if="release.notes"
                                class="text-muted-foreground mt-2 text-sm leading-6"
                            >
                                {{ release.notes }}
                            </p>
                        </div>

                        <a :href="release.download_url" class="shrink-0">
                            <Button>
                                <Download class="size-4" />
                                Download
                            </Button>
                        </a>
                    </li>
                </ul>
            </Deferred>
        </div>
    </PageLayout>
</template>
