<script setup lang="ts">
import { CheckCircle2, ShieldCheck, UserPlus, Users } from "@lucide/vue";
import StatCard from "@/components/dashboard/StatCard.vue";
import type { DashboardStats } from "@/types";

defineProps<{
    stats: DashboardStats;
}>();
</script>

<template>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <StatCard
            label="Total users"
            :value="stats.total_users"
            :icon="Users"
        />
        <StatCard
            label="Active users"
            :value="stats.active_users"
            :icon="CheckCircle2"
            tone="success"
        />
        <StatCard
            label="Verified users"
            :value="stats.verified_users"
            :icon="ShieldCheck"
        />
        <StatCard
            label="New (7 days)"
            :value="stats.new_users_last_7_days"
            :icon="UserPlus"
        />
    </div>
</template>
