<script setup lang="ts">
import { computed } from "vue";
import { Badge, type BadgeVariants } from "@/components/ui/badge";
import type { LicenseKeyStatus } from "@/types";

const props = defineProps<{ status: LicenseKeyStatus }>();

const variant = computed<BadgeVariants["variant"]>(() => {
    switch (props.status) {
        case "active":
            return "success";
        case "pending":
            return "info";
        case "expired":
            return "warning";
        case "revoked":
        case "blocked":
            return "destructive";
        default:
            return "default";
    }
});

const label = computed(() => {
    return props.status.charAt(0).toUpperCase() + props.status.slice(1);
});
</script>

<template>
    <Badge :variant="variant">{{ label }}</Badge>
</template>
