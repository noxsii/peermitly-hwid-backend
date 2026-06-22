<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PageLayout from "@/layout/PageLayout.vue";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();

const greeting = computed(() => {
    const name = page.props.auth?.user?.name ?? "";
    const first = name.split(/\s+/)[0] ?? "";
    return first ? `Welcome back, ${first}` : "Welcome back";
});
</script>

<template>
    <PageLayout :title="greeting" />
</template>
