<script setup lang="ts">
import { Deferred } from "@inertiajs/vue3";
import TeamCard from "@/components/team/TeamCard.vue";
import PageLayout from "@/layout/PageLayout.vue";
import type { OwnedTeam } from "@/types";

defineProps<{
    teams?: OwnedTeam[] | null;
}>();
</script>

<template>
    <PageLayout title="Team">
        <Deferred data="teams">
            <template #fallback>
                <div
                    class="grid grid-cols-1 items-start gap-4 lg:grid-cols-2"
                    aria-busy="true"
                >
                    <div
                        v-for="i in 2"
                        :key="i"
                        class="bg-muted/60 h-40 animate-pulse rounded-2xl"
                    />
                </div>
            </template>

            <div
                v-if="teams && teams.length > 0"
                class="grid grid-cols-1 items-start gap-4 lg:grid-cols-2"
            >
                <TeamCard v-for="team in teams" :key="team.uuid" :team="team" />
            </div>

            <p v-else class="text-muted-foreground text-sm">
                You don't own any teams yet.
            </p>
        </Deferred>
    </PageLayout>
</template>
