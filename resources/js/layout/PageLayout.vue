<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

defineProps<{
    title: string;
}>();
</script>

<template>
    <Head :title="title" />

    <div class="flex h-full flex-col">
        <header class="flex flex-wrap items-center justify-between gap-3 pb-6">
            <h1 class="text-2xl font-semibold tracking-tight">{{ title }}</h1>

            <div class="flex items-center gap-2">
                <slot name="actions" />
            </div>
        </header>

        <div class="flex-1">
            <slot />
        </div>
    </div>
</template>
