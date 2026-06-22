<script setup lang="ts">
import {
    Boxes,
    KeyRound,
    Layers,
    Package,
    Signal,
    Timer,
    Users,
} from "@lucide/vue";
import { computed } from "vue";
import StatCard from "@/components/dashboard/StatCard.vue";
import type { DashboardStats } from "@/types";

const props = defineProps<{
    stats: DashboardStats;
}>();

const fmt = (n: number): string => new Intl.NumberFormat().format(n);

const cards = computed(() => [
    {
        label: "Active license keys",
        value: fmt(props.stats.active_license_keys),
        icon: KeyRound,
        hint: `${fmt(props.stats.pending_license_keys)} pending`,
        tone: "success" as const,
    },
    {
        label: "Expiring in 30 days",
        value: fmt(props.stats.expiring_soon),
        icon: Timer,
        hint: props.stats.expiring_soon > 0 ? "Review renewals" : "All clear",
        tone: (props.stats.expiring_soon > 0 ? "warning" : "default") as
            | "warning"
            | "default",
    },
    {
        label: "Customers",
        value: fmt(props.stats.customers),
        icon: Users,
        hint: null,
        tone: "default" as const,
    },
    {
        label: "Products",
        value: fmt(props.stats.products),
        icon: Package,
        hint: `${fmt(props.stats.license_key_types)} key types`,
        tone: "default" as const,
    },
    {
        label: "API calls · 24h",
        value: fmt(props.stats.api_calls_last_24h),
        icon: Signal,
        hint: null,
        tone: "default" as const,
    },
    {
        label: "Total catalogue",
        value: fmt(
            props.stats.products +
                props.stats.license_key_types +
                props.stats.customers,
        ),
        icon: Boxes,
        hint: "Products + types + customers",
        tone: "default" as const,
    },
    {
        label: "Key types",
        value: fmt(props.stats.license_key_types),
        icon: Layers,
        hint: null,
        tone: "default" as const,
    },
]);
</script>

<template>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <StatCard
            v-for="card in cards.slice(0, 4)"
            :key="card.label"
            :label="card.label"
            :value="card.value"
            :icon="card.icon"
            :hint="card.hint"
            :tone="card.tone"
        />
    </div>
</template>
