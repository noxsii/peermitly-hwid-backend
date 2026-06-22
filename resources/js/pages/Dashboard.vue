<script setup lang="ts">
import { Deferred, Link, usePage } from "@inertiajs/vue3";
import { Plus } from "@lucide/vue";
import { computed } from "vue";
import RecentApiCalls from "@/components/dashboard/RecentApiCalls.vue";
import RecentLicenseKeys from "@/components/dashboard/RecentLicenseKeys.vue";
import StatsOverview from "@/components/dashboard/StatsOverview.vue";
import TeamMembers from "@/components/dashboard/TeamMembers.vue";
import Card from "@/components/Card.vue";
import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type {
    DashboardApiCall,
    DashboardStats,
    DashboardTeamMember,
    LicenseKey,
    PageProps,
} from "@/types";

defineProps<{
    stats?: DashboardStats | null;
    recentLicenseKeys?: { data: LicenseKey[] } | null;
    teamMembers?: { data: DashboardTeamMember[] } | null;
    recentApiCalls?: { data: DashboardApiCall[] } | null;
}>();

const page = usePage<PageProps>();

const greeting = computed(() => {
    const name = page.props.auth?.user?.name ?? "";
    const first = name.split(/\s+/)[0] ?? "";
    return first ? `Welcome back, ${first}` : "Welcome back";
});

const teamName = computed(
    () => page.props.auth?.user?.current_team?.name ?? null,
);
</script>

<template>
    <PageLayout :title="greeting">
        <template #actions>
            <Link href="/license-keys">
                <Button size="sm">
                    <Plus class="size-4" />
                    New license key
                </Button>
            </Link>
        </template>

        <div class="space-y-4">
            <p v-if="teamName" class="text-muted-foreground -mt-2 text-sm">
                Team:
                <span class="text-foreground font-medium">{{ teamName }}</span>
            </p>

            <!-- Stats -->
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

            <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3">
                <!-- Recent license keys -->
                <Card title="Recent license keys" class="lg:col-span-2">
                    <template #actions>
                        <Link
                            href="/license-keys"
                            class="text-muted-foreground hover:text-foreground text-xs font-medium transition-colors"
                        >
                            View all
                        </Link>
                    </template>
                    <Deferred data="recentLicenseKeys">
                        <template #fallback>
                            <div class="space-y-2">
                                <Skeleton
                                    v-for="i in 4"
                                    :key="i"
                                    class="h-12 w-full"
                                />
                            </div>
                        </template>
                        <RecentLicenseKeys
                            :rows="recentLicenseKeys?.data ?? []"
                        />
                    </Deferred>
                </Card>

                <!-- Right column: Team members + Recent API calls stacked -->
                <div class="space-y-4">
                    <Card title="Team members">
                        <Deferred data="teamMembers">
                            <template #fallback>
                                <div class="space-y-3">
                                    <Skeleton
                                        v-for="i in 3"
                                        :key="i"
                                        class="h-10 w-full"
                                    />
                                </div>
                            </template>
                            <TeamMembers :members="teamMembers?.data ?? []" />
                        </Deferred>
                    </Card>

                    <Card title="Recent API calls">
                        <Deferred data="recentApiCalls">
                            <template #fallback>
                                <div class="space-y-2">
                                    <Skeleton
                                        v-for="i in 4"
                                        :key="i"
                                        class="h-8 w-full"
                                    />
                                </div>
                            </template>
                            <RecentApiCalls
                                :rows="recentApiCalls?.data ?? []"
                            />
                        </Deferred>
                    </Card>
                </div>
            </div>
        </div>
    </PageLayout>
</template>
