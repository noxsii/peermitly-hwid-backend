<script setup lang="ts">
import { Deferred, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import StatsOverview from "@/components/dashboard/StatsOverview.vue";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type { DashboardStats, PageProps } from "@/types";

defineProps<{
    stats?: DashboardStats | null;
}>();

const page = usePage<PageProps>();

const greeting = computed(() => {
    const name = page.props.auth?.user?.name ?? "";
    const first = name.split(/\s+/)[0] ?? "";
    return first ? `Welcome back, ${first}` : "Welcome back";
});
</script>

<template>
    <PageLayout :title="greeting">
        <div class="space-y-4">
            <Deferred data="stats">
                <template #fallback>
                    <div
                        class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4"
                    >
                        <Skeleton
                            v-for="i in 4"
                            :key="i"
                            class="h-28 w-full rounded-2xl"
                        />
                    </div>
                </template>
                <StatsOverview v-if="stats" :stats="stats" />
            </Deferred>
        </div>
    </PageLayout>
</template>
